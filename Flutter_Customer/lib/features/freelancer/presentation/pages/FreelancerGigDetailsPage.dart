import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:share_plus/share_plus.dart';
import 'package:provider/provider.dart';
import '../../../chat/presentation/pages/chat_page.dart';
import '../../../chat/presentation/pages/chat_details_page.dart';
import '../../../auth/data/models/user_model.dart';
import '../../data/services/recently_viewed_service.dart';
import '../../data/services/gig_service.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import '../../../home/presentation/providers/home_provider.dart';
import '../widgets/all_reviews_bottom_sheet.dart';
import 'package:flutter_customer/core/widgets/custom_avatar.dart';

class FreelancerGigDetailsPage extends StatefulWidget {
  final Map<String, dynamic>? service;

  const FreelancerGigDetailsPage({super.key, this.service});

  @override
  State<FreelancerGigDetailsPage> createState() => _FreelancerGigDetailsPageState();
}

class _FreelancerGigDetailsPageState extends State<FreelancerGigDetailsPage> {
  int _selectedPackageIndex = 0;
  int _currentImageIndex = 0;
  bool _isDescriptionExpanded = false;
  final ScrollController _scrollController = ScrollController();
  final CarouselSliderController _carouselController = CarouselSliderController();
  final RecentlyViewedService _recentlyViewedService = RecentlyViewedService();
  List<Map<String, dynamic>> _recentlyViewed = [];
  Map<String, dynamic>? _service;
  bool _isLoading = false;
  bool _isFavorite = false;

  @override
  void initState() {
    super.initState();
    _service = widget.service;
    if (_service != null) {
      _isFavorite = _service!['is_favorite'] == true || _service!['is_favorite'] == 1;
    }
    _fetchGigDetails();
    _initRecentlyViewed();
  }

  Future<void> _fetchGigDetails() async {
    if (widget.service != null && widget.service!['id'] != null) {
      if (mounted) setState(() => _isLoading = true);
      try {
        final gigService = GigService();
        final gigData = await gigService.getGigDetails(int.tryParse(widget.service!['id'].toString()) ?? 0);
        if (mounted && gigData != null) {
          setState(() {
            _service = gigData;
            _isFavorite = gigData['is_favorite'] == true || gigData['is_favorite'] == 1;
          });
        }
      } catch (e) {
        debugPrint('Error fetching gig details: $e');
      } finally {
        if (mounted) setState(() => _isLoading = false);
      }
    }
  }

  void _toggleFavorite() {
    if (_service == null || _service!['id'] == null) return;
    
    final gigId = int.tryParse(_service!['id'].toString()) ?? 0;
    if (gigId == 0) return;

    setState(() {
      _isFavorite = !_isFavorite;
    });

    // Call Provider
    Provider.of<HomeProvider>(context, listen: false).toggleGigFavorite(gigId);
  }

  Future<void> _shareGig() async {
    if (_service == null || _service!['id'] == null) return;
    
    final gigId = _service!['id'];
    // Construct shareable URL
    // Format: BASE_URL/gigs/{id}
    final String url = '${ApiConstants.baseUrl}/gigs/$gigId';
    final String title = _asString(_service?['name'], fallback: 'Check out this gig!');
    
    try {
      await Share.share('$title\n$url');
    } catch (e) {
      debugPrint('Error sharing gig: $e');
    }
  }

  Future<void> _initRecentlyViewed() async {
    if (widget.service != null) {
      await _recentlyViewedService.addService(widget.service!);
    }
    final services = await _recentlyViewedService.getServices();
    if (mounted) {
      setState(() {
        _recentlyViewed = services;
      });
    }
  }

  String _asString(dynamic v, {String fallback = ''}) {
    if (v == null) return fallback;
    if (v is String) return v;
    if (v is num || v is bool) return v.toString();
    if (v is Map) {
      for (final key in ['name', 'title', 'label', 'value']) {
        final val = v[key];
        if (val is String) return val;
        if (val is num || val is bool) return val.toString();
      }
    }
    return fallback;
  }

  List<String> _getImages() {
    final service = _service;
    final List<String> images = [];
    
    // Thumbnail (Highest Priority)
    final thumbnail = _safeImageUrl(service?['thumbnail_image']);
    if (thumbnail != null) images.add(thumbnail);

    // Main image (Legacy/Fallback) - Only add if different from thumbnail
    final mainImg = _safeImageUrl(service?['image']);
    if (mainImg != null && !images.contains(mainImg)) {
       images.add(mainImg);
    }

    // Gallery/Images list
    // Check 'images' first (standard)
    if (service?['images'] is List) {
       for (var item in service!['images']) {
          final url = _safeImageUrl(item);
          if (url != null && !images.contains(url)) images.add(url);
       }
    } 
    // Check 'gallery' (legacy)
    else if (service?['gallery'] is List) {
      for (var item in service!['gallery']) {
        final url = _safeImageUrl(item);
        if (url != null && !images.contains(url)) images.add(url);
      }
    }

    // Fallback if empty
    if (images.isEmpty) {
      images.add('https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');
    }

    return images;
  }

  String? _safeImageUrl(dynamic v) {
    if (v == null) return null;
    String? url;
    if (v is String && v.isNotEmpty) {
      url = v;
    } else if (v is Map) {
      for (final key in ['url', 'src', 'image', 'full']) {
        final val = v[key];
        if (val is String && val.isNotEmpty) {
          url = val;
          break;
        }
      }
    }
    
    if (url != null) {
      if (url == 'default' || 
          url.contains('via.placeholder.com') || 
          url.contains('default.png') ||
          url.contains('unsplash.com')) {
        return null;
      }

      if (url.startsWith('http') || url.startsWith('assets')) return url;
      
      String cleanPath = url.startsWith('/') ? url.substring(1) : url;
      return '${ApiConstants.baseUrl}/$cleanPath';
    }
    return null;
  }

  @override
  Widget build(BuildContext context) {
    final service = _service;
    final images = _getImages();
    final packages = (service?['packages'] as List<dynamic>?) ?? [];
    
    final selectedPackage = packages.length > _selectedPackageIndex 
        ? packages[_selectedPackageIndex] 
        : (packages.isNotEmpty ? packages[0] : {});

    return Scaffold(
      backgroundColor: Colors.white,
      body: Stack(
        children: [
          CustomScrollView(
            controller: _scrollController,
            slivers: [
              _buildSliverAppBar(context, images),
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.only(bottom: 120), // Space for bottom bar
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildHeaderSection(context, service),
                      const Divider(height: 1, color: Color(0xFFE2E8F0)),
                      _buildSellerProfile(context, service),
                      const Divider(height: 32, thickness: 8, color: Color(0xFFF8FAFC)),
                      _buildPackageSection(context, packages, selectedPackage),
                      const Divider(height: 32, thickness: 8, color: Color(0xFFF8FAFC)),
                      _buildDescriptionSection(context, service),
                      const Divider(height: 32, thickness: 8, color: Color(0xFFF8FAFC)),
                      _buildPortfolioSection(context),
                      const Divider(height: 32, thickness: 8, color: Color(0xFFF8FAFC)),
                      _buildReviewsSection(context, service),
                      const Divider(height: 32, thickness: 8, color: Color(0xFFF8FAFC)),
                      _buildFAQSection(context),
                      const SizedBox(height: 32),
                      if (_recentlyViewed.isNotEmpty) ...[
                        _buildRecentlyViewedSection(context),
                        const SizedBox(height: 32),
                      ],
                    ],
                  ),
                ),
              ),
            ],
          ),
          _buildBottomBar(context, selectedPackage, service),
        ],
      ),
    );
  }

  Widget _buildSliverAppBar(BuildContext context, List<String> images) {
    return SliverAppBar(
      expandedHeight: 320,
      pinned: true,
      backgroundColor: Colors.white,
      elevation: 0,
      leading: Container(
        margin: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: 0.9),
          shape: BoxShape.circle,
          boxShadow: [
            BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8),
          ],
        ),
        child: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.black, size: 20),
          onPressed: () => context.pop(),
        ),
      ),
      actions: [
        Container(
          margin: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: Colors.white.withValues(alpha: 0.9),
            shape: BoxShape.circle,
            boxShadow: [
              BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8),
            ],
          ),
          child: IconButton(
            icon: const Icon(Icons.share_outlined, color: Colors.black, size: 20),
            onPressed: _shareGig,
          ),
        ),
        Container(
          margin: const EdgeInsets.only(right: 16, top: 8, bottom: 8),
          decoration: BoxDecoration(
            color: Colors.white.withValues(alpha: 0.9),
            shape: BoxShape.circle,
            boxShadow: [
              BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8),
            ],
          ),
          child: IconButton(
            icon: Icon(
              _isFavorite ? Icons.favorite_rounded : Icons.favorite_border_rounded, 
              color: _isFavorite ? Colors.red : Colors.black, 
              size: 20
            ),
            onPressed: _toggleFavorite,
          ),
        ),
      ],
      flexibleSpace: FlexibleSpaceBar(
        background: Stack(
          children: [
            CarouselSlider(
              carouselController: _carouselController,
              options: CarouselOptions(
                height: 360,
                viewportFraction: 1.0,
                enableInfiniteScroll: images.length > 1,
                onPageChanged: (index, reason) {
                  setState(() {
                    _currentImageIndex = index;
                  });
                },
              ),
              items: images.map((img) {
                return Builder(
                  builder: (BuildContext context) {
                    // Check for empty string or local asset path
                    if (img.isEmpty || !img.startsWith('http')) {
                      return Image.asset(
                        'assets/images/placeholder.png',
                        fit: BoxFit.cover,
                        width: double.infinity,
                      );
                    }
                    return CachedNetworkImage(
                      imageUrl: img,
                      fit: BoxFit.cover,
                      width: double.infinity,
                      placeholder: (context, url) => Image.asset(
                        'assets/images/placeholder.png',
                        fit: BoxFit.cover,
                      ),
                      errorWidget: (context, url, error) => Image.asset(
                        'assets/images/placeholder.png',
                        fit: BoxFit.cover,
                      ),
                    );
                  },
                );
              }).toList(),
            ),
            // Gradient Overlay
            Positioned.fill(
              child: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      Colors.black.withValues(alpha: 0.3),
                      Colors.transparent,
                      Colors.transparent,
                      Colors.black.withValues(alpha: 0.1),
                    ],
                  ),
                ),
              ),
            ),
            // Navigation Arrows
            if (images.length > 1) ...[
              Positioned(
                left: 16,
                top: 0,
                bottom: 0,
                child: Center(
                  child: GestureDetector(
                    onTap: () => _carouselController.previousPage(),
                    child: Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.black.withValues(alpha: 0.3),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.arrow_back_ios_new, color: Colors.white, size: 20),
                    ),
                  ),
                ),
              ),
              Positioned(
                right: 16,
                top: 0,
                bottom: 0,
                child: Center(
                  child: GestureDetector(
                    onTap: () => _carouselController.nextPage(),
                    child: Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.black.withValues(alpha: 0.3),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.arrow_forward_ios, color: Colors.white, size: 20),
                    ),
                  ),
                ),
              ),
            ],
            Positioned(
              bottom: 16,
              right: 16,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(
                  color: Colors.black.withValues(alpha: 0.6),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
                ),
                child: Text(
                  '${_currentImageIndex + 1}/${images.length}',
                  style: GoogleFonts.plusJakartaSans(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                    fontSize: 12,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeaderSection(BuildContext context, Map<String, dynamic>? service) {
    final category = _asString(service?['category'], fallback: 'Gig Category');
    final title = _asString(service?['name'], fallback: 'I will do professional freelancer work for you');
    
    final reviewsList = (service?['reviews'] as List?) ?? [];
    final reviewsCount = reviewsList.length;
    
    // Calculate rating if missing or 0
    double avgRating = double.tryParse(service?['rating']?.toString() ?? '0') ?? 0.0;
    if (avgRating == 0 && reviewsList.isNotEmpty) {
       double total = 0;
       for(var r in reviewsList) {
          total += (r['rating'] as num?)?.toDouble() ?? 0.0;
       }
       avgRating = total / reviewsList.length;
    }
    final rating = avgRating.toStringAsFixed(1);

    return Padding(
      padding: const EdgeInsets.all(20.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Breadcrumb-ish
          Row(
            children: [
              Text(
                'Home',
                style: GoogleFonts.plusJakartaSans(color: Colors.grey[400], fontSize: 12),
              ),
              const Icon(Icons.chevron_right, size: 14, color: Colors.grey),
              Text(
                category,
                style: GoogleFonts.plusJakartaSans(color: Colors.grey[600], fontSize: 12, fontWeight: FontWeight.w600),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            title,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 22,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
              height: 1.3,
            ),
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Icon(Icons.star_rounded, color: Colors.amber[400], size: 20),
              const SizedBox(width: 4),
              Text(
                rating,
                style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 16, color: const Color(0xFF0F172A)),
              ),
              const SizedBox(width: 4),
              Text(
                '($reviewsCount reviews)',
                style: GoogleFonts.plusJakartaSans(color: Colors.grey[500], fontSize: 14),
              ),
              const Spacer(),
              Container(
                 padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                 decoration: BoxDecoration(
                   color: Colors.green.withValues(alpha: 0.1),
                   borderRadius: BorderRadius.circular(4),
                 ),
                 child: Text(
                   'Available',
                   style: GoogleFonts.plusJakartaSans(
                     color: Colors.green,
                     fontWeight: FontWeight.bold,
                     fontSize: 12,
                   ),
                 ),
              )
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSellerProfile(BuildContext context, Map<String, dynamic>? service) {
    final provider = service?['provider'];
    final name = provider is Map ? (provider['name'] ?? 'Unknown Seller') : 'Expert Freelancer';
    final image = provider is Map ? _safeImageUrl(provider['image']) : null;
    final level = 'Level 2 Seller'; // Mock or fetch

    // Prepare provider data for profile page
    final Map<String, dynamic> providerData = provider is Map 
        ? Map<String, dynamic>.from(provider as Map) 
        : {'name': name, 'image': image};
    
    final reviewsList = (service?['reviews'] as List?) ?? [];
    
    // Add mock data if missing
    if (!providerData.containsKey('username')) providerData['username'] = '@expert_dev';
    if (!providerData.containsKey('rating')) providerData['rating'] = service?['rating'] ?? 5.0;
    if (!providerData.containsKey('reviews_count')) providerData['reviews_count'] = reviewsList.length;

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Seller',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 16),
          GestureDetector(
            onTap: () => context.push('/freelancer-profile', extra: providerData),
            child: Container(
              color: Colors.transparent, // Hit test behavior
              child: Row(
                children: [
                  Stack(
                    children: [
                      CustomAvatar(
                        imageUrl: image,
                        name: name,
                        size: 56,
                      ),
                      Positioned(
                        bottom: 0,
                        right: 0,
                        child: Container(
                          width: 14,
                          height: 14,
                          decoration: BoxDecoration(
                            color: Colors.green,
                            shape: BoxShape.circle,
                            border: Border.all(color: Colors.white, width: 2),
                          ),
                        ),
                      )
                    ],
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Text(
                              name,
                              style: GoogleFonts.plusJakartaSans(
                                fontWeight: FontWeight.bold,
                                fontSize: 16,
                                color: const Color(0xFF0F172A),
                              ),
                            ),
                            const SizedBox(width: 4),
                            const Icon(Icons.verified, size: 16, color: Colors.blue),
                          ],
                        ),
                        const SizedBox(height: 4),
                        Text(
                          level,
                          style: GoogleFonts.plusJakartaSans(
                            color: Colors.grey[500],
                            fontSize: 13,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                      ],
                    ),
                  ),
                  OutlinedButton(
                    onPressed: () {
                       final providerId = (provider is Map && provider['id'] != null) 
                           ? (provider['id'] is int ? provider['id'] : int.tryParse(provider['id'].toString())) 
                           : null;
                       
                       if (providerId != null) {
                          final user = UserModel(
                            id: providerId,
                            name: name,
                            email: '', // Not needed for chat
                            role: 'provider',
                            profileImage: image,
                          );
                          context.push('/chat-details', extra: user);
                       }
                    },
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Color(0xFF0F172A)),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
                      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                    ),
                    child: Text('Contact Me', style: GoogleFonts.plusJakartaSans(color: const Color(0xFF0F172A), fontWeight: FontWeight.bold, fontSize: 13)),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPackageSection(BuildContext context, List<dynamic> packages, dynamic selectedPackage) {
    return Column(
      children: [
        // Tabs
        if (packages.length > 1)
          Container(
            height: 48,
            margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            decoration: BoxDecoration(
              color: const Color(0xFFF1F5F9),
              borderRadius: BorderRadius.circular(24),
            ),
            child: Row(
              children: List.generate(packages.length, (index) {
                final pkg = packages[index];
                final isSelected = _selectedPackageIndex == index;
                final tierName = pkg['tier']?.toString().toUpperCase() ?? 'TIER ${index + 1}';
                
                return Expanded(
                  child: GestureDetector(
                    onTap: () => setState(() => _selectedPackageIndex = index),
                    child: AnimatedContainer(
                      duration: const Duration(milliseconds: 200),
                      margin: const EdgeInsets.all(4),
                      decoration: BoxDecoration(
                        color: isSelected ? Colors.white : Colors.transparent,
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: isSelected
                            ? [
                                BoxShadow(
                                  color: Colors.black.withValues(alpha: 0.05),
                                  blurRadius: 4,
                                  offset: const Offset(0, 2),
                                )
                              ]
                            : null,
                      ),
                      alignment: Alignment.center,
                      child: Text(
                        tierName,
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.bold,
                          color: isSelected ? const Color(0xFF0F172A) : Colors.grey[500],
                          fontSize: 12,
                        ),
                      ),
                    ),
                  ),
                );
              }),
            ),
          ),
        
        // Content
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(
                      selectedPackage['name'] ?? selectedPackage['tier'] ?? 'Standard Package',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF0F172A),
                      ),
                    ),
                  ),
                  Text(
                    '\$${selectedPackage['price']}',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF0F172A),
                      height: 1.0,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              Text(
                selectedPackage['description'] ?? 'No description available for this package.',
                style: GoogleFonts.plusJakartaSans(
                  color: Colors.grey[600],
                  fontSize: 15,
                  height: 1.5,
                ),
              ),
              const SizedBox(height: 24),
              Row(
                children: [
                  const Icon(Icons.schedule, size: 18, color: Color(0xFF0F172A)),
                  const SizedBox(width: 8),
                  Text(
                    '${selectedPackage['delivery_days'] ?? 2} Days Delivery',
                    style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, color: const Color(0xFF0F172A), fontSize: 14),
                  ),
                  const SizedBox(width: 24),
                  const Icon(Icons.cached, size: 18, color: Color(0xFF0F172A)),
                  const SizedBox(width: 8),
                  Text(
                    '${selectedPackage['revisions'] ?? 1} Revisions',
                    style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, color: const Color(0xFF0F172A), fontSize: 14),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              if (selectedPackage['features'] is List)
                ...((selectedPackage['features'] as List).map((f) => Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: Row(
                    children: [
                      const Icon(Icons.check, size: 18, color: Colors.green),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Text(
                          f.toString(),
                          style: GoogleFonts.plusJakartaSans(color: Colors.grey[600], fontSize: 14),
                        ),
                      ),
                    ],
                  ),
                ))),
            ],
          ).animate().fadeIn(),
        ),
      ],
    );
  }

  Widget _buildDescriptionSection(BuildContext context, Map<String, dynamic>? service) {
    final description = _asString(service?['description'], fallback: 'No description.');
    
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'About This Gig',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 16),
          Text(
            description,
            maxLines: _isDescriptionExpanded ? null : 6,
            overflow: _isDescriptionExpanded ? TextOverflow.visible : TextOverflow.ellipsis,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 15,
              height: 1.6,
              color: const Color(0xFF334155),
            ),
          ),
          if (description.length > 200)
            GestureDetector(
              onTap: () => setState(() => _isDescriptionExpanded = !_isDescriptionExpanded),
              child: Padding(
                padding: const EdgeInsets.only(top: 8),
                child: Text(
                  _isDescriptionExpanded ? 'Read Less' : 'Read More',
                  style: GoogleFonts.plusJakartaSans(
                    color: Theme.of(context).primaryColor,
                    fontWeight: FontWeight.bold,
                    fontSize: 14,
                    decoration: TextDecoration.underline,
                  ),
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildPortfolioSection(BuildContext context) {
    final rawPortfolio = _service?['provider']?['freelancer_portfolios'] as List?;
    if (rawPortfolio == null || rawPortfolio.isEmpty) return const SizedBox.shrink();

    // Filter valid items first (must have a valid image)
    final portfolio = rawPortfolio.where((item) => _safeImageUrl(item) != null).toList();
    
    if (portfolio.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Text(
            'My Portfolio',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 180,
          child: ListView.separated(
            padding: const EdgeInsets.symmetric(horizontal: 20),
            scrollDirection: Axis.horizontal,
            itemCount: portfolio.length,
            separatorBuilder: (_, __) => const SizedBox(width: 12),
            itemBuilder: (context, index) {
              final item = portfolio[index];
              final image = _safeImageUrl(item);
              if (image == null) return const SizedBox.shrink();

              return ClipRRect(
                borderRadius: BorderRadius.circular(16),
                child: CachedNetworkImage(
                  imageUrl: image,
                  width: 260,
                  height: 180,
                  fit: BoxFit.cover,
                  errorWidget: (context, url, error) => Container(color: Colors.grey[200]),
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildFAQSection(BuildContext context) {
    final faqs = _service?['faqs'] as List?;
    if (faqs == null || faqs.isEmpty) return const SizedBox.shrink();

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'FAQ',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 16),
          ...faqs.map((faq) => Container(
            margin: const EdgeInsets.only(bottom: 12),
            decoration: BoxDecoration(
              border: Border.all(color: Colors.grey[200]!),
              borderRadius: BorderRadius.circular(12),
            ),
            child: ExpansionTile(
              title: Text(
                _asString(faq['question']),
                style: GoogleFonts.plusJakartaSans(
                  fontWeight: FontWeight.w600,
                  fontSize: 15,
                  color: const Color(0xFF0F172A),
                ),
              ),
              shape: const RoundedRectangleBorder(side: BorderSide.none),
              tilePadding: const EdgeInsets.symmetric(horizontal: 16),
              childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
              children: [
                Text(
                  _asString(faq['answer']),
                  style: GoogleFonts.plusJakartaSans(
                    color: Colors.grey[600],
                    fontSize: 14,
                    height: 1.5,
                  ),
                ),
              ],
            ),
          )),
        ],
      ),
    );
  }

  Widget _buildReviewsSection(BuildContext context, Map<String, dynamic>? service) {
    final reviewsList = (service?['reviews'] as List?) ?? [];
    final reviewCount = reviewsList.length;
    
    // Calculate rating if missing or 0
    double avgRating = double.tryParse(service?['rating']?.toString() ?? '0') ?? 0.0;
    if (avgRating == 0 && reviewsList.isNotEmpty) {
       double total = 0;
       for(var r in reviewsList) {
          total += (r['rating'] as num?)?.toDouble() ?? 0.0;
       }
       avgRating = total / reviewsList.length;
    }
    final rating = avgRating.toStringAsFixed(1);

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Text(
                '$reviewCount Reviews',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF0F172A),
                ),
              ),
              const Spacer(),
              Icon(Icons.star_rounded, color: Colors.amber[400], size: 24),
              const SizedBox(width: 4),
              Text(
                rating,
                style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 18, color: const Color(0xFF0F172A)),
              ),
            ],
          ),
          const SizedBox(height: 24),
          if (reviewsList.isEmpty)
            Text(
              'No reviews yet.',
              style: GoogleFonts.plusJakartaSans(color: Colors.grey[500], fontSize: 14),
            )
          else
            ...reviewsList.take(3).map((review) {
              String reviewerName = 'Anonymous';
              final user = review['user'];
              if (user != null) {
                final firstName = user['first_name'] ?? '';
                final lastName = user['last_name'] ?? '';
                final fullName = '$firstName $lastName'.trim();
                if (fullName.isNotEmpty) {
                  reviewerName = fullName;
                } else if (user['name'] != null) {
                  reviewerName = user['name'];
                }
              } else if (review['reviewer_name'] != null) {
                reviewerName = review['reviewer_name'];
              }

              final reviewText = review['review'] ?? '';
              final reviewRating = (review['rating'] as num?)?.toDouble() ?? 0.0;
              final reviewDate = review['created_at'] != null 
                  ? DateFormat('MMM d').format(DateTime.parse(review['created_at'])) 
                  : '';

              String? profileImage = user != null ? user['profile_image'] : null;
              if (profileImage != null && !profileImage.startsWith('http')) {
                  profileImage = '${ApiConstants.baseUrl}/storage/$profileImage';
              }
              
              return Padding(
                padding: const EdgeInsets.only(bottom: 24),
                child: _buildReviewItem(
                  reviewerName, 
                  'Verified User', 
                  profileImage,  
                  reviewText,
                  reviewRating,
                  reviewDate
                ),
              );
            }),
          if (reviewsList.length > 3)
            SizedBox(
              width: double.infinity,
              child: OutlinedButton(
                onPressed: () {
                  showModalBottomSheet(
                    context: context,
                    isScrollControlled: true,
                    backgroundColor: Colors.transparent,
                    builder: (context) => AllReviewsBottomSheet(
                      reviews: reviewsList,
                      avgRating: double.tryParse(rating) ?? 0.0,
                    ),
                  );
                },
                style: OutlinedButton.styleFrom(
                  side: BorderSide(color: Colors.grey[300]!),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
                child: Text('See All Reviews', style: GoogleFonts.plusJakartaSans(color: const Color(0xFF0F172A), fontWeight: FontWeight.bold)),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildReviewItem(String name, String country, String? imageUrl, String text, double rating, String date) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            CustomAvatar(
              imageUrl: imageUrl,
              name: name,
              size: 40,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    name,
                    style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 14, color: const Color(0xFF0F172A)),
                  ),
                  Row(
                    children: [
                      Icon(Icons.flag, size: 12, color: Colors.grey[400]),
                      const SizedBox(width: 4),
                      Text(
                        country,
                        style: GoogleFonts.plusJakartaSans(color: Colors.grey[500], fontSize: 12),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            Text(
              date,
              style: GoogleFonts.plusJakartaSans(color: Colors.grey[400], fontSize: 12),
            ),
          ],
        ),
        const SizedBox(height: 12),
        Text(
          text,
          style: GoogleFonts.plusJakartaSans(
            color: const Color(0xFF334155),
            fontSize: 14,
            height: 1.5,
          ),
        ),
        const SizedBox(height: 8),
        Row(
          children: [
            const Icon(Icons.star_rounded, size: 16, color: Colors.amber),
            const SizedBox(width: 4),
            Text(
              rating.toString(),
              style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 12, color: const Color(0xFF0F172A)),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildGigCard(Map<String, dynamic> item) {
    final image = item['image'] ?? 'https://images.unsplash.com/photo-1558655146-d09347e92766?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
    final name = item['name'] ?? 'Professional Web Development and Design';
    final price = item['price'] ?? '100';
    
    final reviewsData = item['reviews'];
    final reviewsCount = (reviewsData is List) ? reviewsData.length : (reviewsData ?? 0);

    // Calculate rating
    double avgRating = double.tryParse(item['rating']?.toString() ?? '0') ?? 0.0;
    if (avgRating == 0 && reviewsData is List && reviewsData.isNotEmpty) {
       double total = 0;
       for(var r in reviewsData) {
          total += (r['rating'] as num?)?.toDouble() ?? 0.0;
       }
       avgRating = total / reviewsData.length;
    }
    final rating = avgRating.toStringAsFixed(1);
    
    // Extract seller info if available, otherwise mock
    final provider = item['provider'];
    final sellerName = provider is Map ? (provider['name'] ?? 'Seller') : 'Seller Name';
    final sellerImage = provider is Map && provider['image'] != null 
        ? provider['image'] 
        : '';

    return GestureDetector(
      onTap: () {
        // Navigate to details page for this gig
        // Since we are already on details page, we can push a new one
        context.push('/freelancer-gig-details', extra: item);
      },
      child: Container(
        width: 220,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 12,
              offset: const Offset(0, 6),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
              child: CachedNetworkImage(
                imageUrl: image.toString(),
                height: 140,
                width: double.infinity,
                fit: BoxFit.cover,
                placeholder: (context, url) => Container(color: Colors.grey[200]),
                errorWidget: (context, url, error) => Container(color: Colors.grey[200], child: const Icon(Icons.error)),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      CustomAvatar(
                        imageUrl: sellerImage.toString(),
                        name: sellerName.toString(),
                        size: 20,
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          sellerName.toString(),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: GoogleFonts.plusJakartaSans(fontSize: 12, fontWeight: FontWeight.bold, color: const Color(0xFF0F172A)),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    name.toString(),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.w600,
                      fontSize: 13,
                      color: const Color(0xFF0F172A),
                      height: 1.3,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      const Icon(Icons.star_rounded, size: 14, color: Colors.amber),
                      const SizedBox(width: 4),
                      Text('$rating ($reviewsCount)', style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey[600])),
                      const Spacer(),
                      Text(
                        'From \$$price',
                        style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 14, color: const Color(0xFF0F172A)),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildRecentlyViewedSection(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Recently Viewed Gigs',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF0F172A),
                ),
              ),
              if (_recentlyViewed.length > 5)
                Text(
                  'See All',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: Theme.of(context).primaryColor,
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 290,
          child: ListView.separated(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
            scrollDirection: Axis.horizontal,
            itemCount: _recentlyViewed.length,
            separatorBuilder: (_, __) => const SizedBox(width: 16),
            itemBuilder: (context, index) {
              return _buildGigCard(_recentlyViewed[index]);
            },
          ),
        ),
      ],
    );
  }

  Widget _buildBottomBar(BuildContext context, dynamic selectedPackage, Map<String, dynamic>? service) {
    return Positioned(
      bottom: 0,
      left: 0,
      right: 0,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 10,
              offset: const Offset(0, -5),
            ),
          ],
          border: Border(top: BorderSide(color: Colors.grey[100]!)),
        ),
        child: SafeArea(
          top: false,
          child: Row(
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    'Total',
                    style: GoogleFonts.plusJakartaSans(
                      color: Colors.grey[500],
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    '\$${selectedPackage['price']}',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF0F172A),
                    ),
                  ),
                ],
              ),
              const SizedBox(width: 24),
              Expanded(
                child: SizedBox(
                  height: 48,
                  child: ElevatedButton(
                    onPressed: () {
                      // Prepare booking data
                      final bookingData = {
                        'service_id': service?['id'],
                        'service_name': service?['name'],
                        'provider_id': service?['provider']?['id'],
                        'provider_name': service?['provider']?['name'],
                        'price': selectedPackage['price'].toString(),
                        'image': _getImages().first,
                        'package_id': selectedPackage['id'], // Added package_id
                        'package_name': selectedPackage['name'] ?? selectedPackage['tier'],
                        'package_features': selectedPackage['features'],
                        'delivery_days': selectedPackage['delivery_days'],
                        'service_extras': service?['extras'] ?? [],
                      };
                      
                      context.push('/order-upgrade', extra: bookingData);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF0F172A),
                      foregroundColor: Colors.white,
                      elevation: 0,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                      padding: EdgeInsets.zero,
                    ),
                    child: Text(
                      'Continue',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
