import 'dart:ui';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_customer/core/widgets/custom_avatar.dart';
import 'package:provider/provider.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import '../../../home/data/home_service.dart';
import '../../../home/presentation/providers/home_provider.dart';

class FreelancerCategoryPage extends StatefulWidget {
  final Map<String, dynamic> category;

  const FreelancerCategoryPage({super.key, required this.category});

  @override
  State<FreelancerCategoryPage> createState() => _FreelancerCategoryPageState();
}

class _FreelancerCategoryPageState extends State<FreelancerCategoryPage> {
  final HomeService _homeService = HomeService();
  List<dynamic> _gigs = [];
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadGigs();
  }

  Future<void> _loadGigs() async {
    try {
      final categoryId = widget.category['id'] is int 
          ? widget.category['id'] 
          : int.tryParse(widget.category['id'].toString());
      
      if (categoryId == null) throw Exception('Invalid Category ID');

      final gigs = await _homeService.getGigsByCategory(categoryId);
      if (mounted) {
        setState(() {
          _gigs = gigs;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final categoryName = widget.category['name'] ?? 'Category';
    final categoryImage = widget.category['image'] ?? widget.category['icon'];
    final isUrl = categoryImage is String && (categoryImage.startsWith('http') || categoryImage.startsWith('assets'));

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: CustomScrollView(
        physics: const BouncingScrollPhysics(),
        slivers: [
          _buildAppBar(context, categoryName, isUrl ? categoryImage : null),
          
          if (_isLoading)
            SliverPadding(
              padding: const EdgeInsets.all(20),
              sliver: SliverGrid(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.72,
                  crossAxisSpacing: 16,
                  mainAxisSpacing: 16,
                ),
                delegate: SliverChildBuilderDelegate(
                  (context, index) => _buildShimmerCard(),
                  childCount: 6,
                ),
              ),
            )
          else if (_error != null)
             SliverFillRemaining(
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.error_outline_rounded, size: 48, color: Colors.red),
                    const SizedBox(height: 16),
                    Text('Failed to load gigs', style: GoogleFonts.plusJakartaSans(color: Colors.red)),
                    const SizedBox(height: 8),
                    TextButton(onPressed: _loadGigs, child: const Text('Retry')),
                  ],
                ),
              ),
            )
          else if (_gigs.isEmpty)
            SliverFillRemaining(
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Container(
                      padding: const EdgeInsets.all(24),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        shape: BoxShape.circle,
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withValues(alpha: 0.03),
                            blurRadius: 6,
                            offset: const Offset(0, 0),
                          ),
                        ],
                      ),
                      child: Icon(Icons.search_off_rounded, size: 48, color: Colors.grey[400]),
                    ),
                    const SizedBox(height: 24),
                    Text(
                      'No gigs found',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1E293B),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Try browsing other categories',
                      style: GoogleFonts.plusJakartaSans(
                        color: const Color(0xFF64748B),
                      ),
                    ),
                  ],
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.all(20),
              sliver: SliverGrid(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.72, // Taller cards for better layout
                  crossAxisSpacing: 16,
                  mainAxisSpacing: 16,
                ),
                delegate: SliverChildBuilderDelegate(
                  (context, index) => _buildGigCard(context, _gigs[index], index),
                  childCount: _gigs.length,
                ),
              ),
            ),
            
          const SliverPadding(padding: EdgeInsets.only(bottom: 40)),
        ],
      ),
    );
  }

  Widget _buildAppBar(BuildContext context, String title, String? imageUrl) {
    return SliverAppBar(
      expandedHeight: 140,
      pinned: true,
      stretch: true,
      backgroundColor: Colors.white,
      surfaceTintColor: Colors.transparent,
      flexibleSpace: FlexibleSpaceBar(
        title: Text(
          title,
          style: GoogleFonts.plusJakartaSans(
            color: const Color(0xFF0F172A),
            fontWeight: FontWeight.bold,
            fontSize: 16,
          ),
        ),
        centerTitle: true,
        background: Stack(
          fit: StackFit.expand,
          children: [
            // Background Pattern/Image
            if (imageUrl != null)
              CachedNetworkImage(
                imageUrl: imageUrl,
                fit: BoxFit.cover,
                color: Colors.white.withValues(alpha: 0.9), // Fade it out a bit
                colorBlendMode: BlendMode.lighten,
              )
            else
              Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [Color(0xFFF8FAFC), Color(0xFFEEF2FF)],
                  ),
                ),
              ),
              
            // Decorative blobs
            Positioned(
              top: -50,
              right: -50,
              child: Container(
                width: 150,
                height: 150,
                decoration: BoxDecoration(
                  color: Theme.of(context).primaryColor.withValues(alpha: 0.05),
                  shape: BoxShape.circle,
                ),
              ),
            ),
          ],
        ),
      ),
      leading: Container(
        margin: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: 0.8),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
        ),
        child: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 18, color: Color(0xFF0F172A)),
          onPressed: () => context.pop(),
        ),
      ),
    );
  }

  String _getValidUrl(String? url) {
    if (url == null || url.isEmpty || url == 'default') {
      return 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
    }
    if (url.startsWith('http') || url.startsWith('assets')) return url;
    
    // Clean up the URL path
    String cleanPath = url.startsWith('/') ? url.substring(1) : url;
    
    // Check if it already has storage prefix if needed, or just append to base
    // Assuming ApiConstants.baseUrl does NOT end with /
    return '${ApiConstants.baseUrl}/$cleanPath';
  }

  Widget _buildGigCard(BuildContext context, Map<String, dynamic> gig, int index) {
    final image = _getValidUrl(gig['thumbnail_image'] ?? gig['image'] ?? gig['thumbnail']);
    final title = gig['title'] ?? gig['name'] ?? 'Untitled Gig';
    
    // Price Logic: Check packages first, fallback to direct price
    String price = '0';
    if (gig['packages'] != null && (gig['packages'] as List).isNotEmpty) {
       final packages = gig['packages'] as List;
       // Try to find Basic package or min price
       final basic = packages.firstWhere((p) => p['tier'] == 'Basic', orElse: () => packages.first);
       price = basic['price']?.toString() ?? '0';
    } else {
       price = gig['price']?.toString() ?? '0';
    }

    // Provider Logic
    final provider = gig['provider'] ?? {};
    final providerName = provider['name'] ?? 'Freelancer';
    final providerImage = _getValidUrl(provider['provider_profile']?['profile_image'] ?? provider['image']);
    
    // Rating Logic
    final reviews = (gig['reviews'] as List?) ?? [];
    double rating = double.tryParse(gig['rating']?.toString() ?? '0') ?? 0.0;
    
    // Calculate if missing
    if (rating == 0 && reviews.isNotEmpty) {
      double total = 0;
      for (var r in reviews) {
        total += (r['rating'] as num?)?.toDouble() ?? 0.0;
      }
      rating = total / reviews.length;
    }
    
    final ratingStr = rating.toStringAsFixed(1);
    final reviewCount = reviews.length;

    return GestureDetector(
      onTap: () {
        context.push('/freelancer-gig-details', extra: gig);
      },
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.03),
              blurRadius: 6,
              offset: const Offset(0, 2),
            ),
          ],
          border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
              child: CachedNetworkImage(
                imageUrl: image.isEmpty ? 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop' : image,
                height: 140,
                width: double.infinity,
                fit: BoxFit.cover,
                placeholder: (context, url) => Container(
                  color: Colors.grey[200],
                  child: const Center(child: CircularProgressIndicator(strokeWidth: 2)),
                ),
                errorWidget: (context, url, error) => CachedNetworkImage(
                   imageUrl: 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop',
                   fit: BoxFit.cover,
                ),
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        CustomAvatar(
                          imageUrl: providerImage,
                          name: providerName,
                          size: 20,
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Text(
                            providerName,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 12,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF0F172A),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Text(
                      title,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF0F172A),
                        height: 1.3,
                      ),
                    ),
                    const Spacer(),
                    Row(
                      children: [
                        const Icon(Icons.star_rounded, size: 14, color: Colors.amber),
                        const SizedBox(width: 4),
                        Text(
                          '$ratingStr ($reviewCount)',
                          style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey[600]),
                        ),
                        const Spacer(),
                        Text(
                          'From \$$price',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.bold,
                            fontSize: 14,
                            color: const Color(0xFF0F172A),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ).animate().fadeIn(delay: (index * 50).ms).scale(),
    );
  }

  Widget _buildShimmerCard() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            height: 120,
            decoration: BoxDecoration(
              color: Colors.grey[200],
              borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(width: 80, height: 10, color: Colors.grey[200]),
                const SizedBox(height: 8),
                Container(width: double.infinity, height: 12, color: Colors.grey[200]),
                const SizedBox(height: 4),
                Container(width: 100, height: 12, color: Colors.grey[200]),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(width: 40, height: 10, color: Colors.grey[200]),
                    Container(width: 60, height: 14, color: Colors.grey[200]),
                  ],
                ),
              ],
            ),
          ),
        ],
      ).animate(onPlay: (controller) => controller.repeat())
       .shimmer(duration: 1200.ms, color: Colors.white.withOpacity(0.5)),
    );
  }
}
