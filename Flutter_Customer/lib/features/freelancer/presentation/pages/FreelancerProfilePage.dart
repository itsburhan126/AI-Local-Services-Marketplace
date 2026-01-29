import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:flutter_customer/core/widgets/custom_avatar.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import '../../data/services/gig_service.dart';
import 'package:intl/intl.dart';
import 'package:share_plus/share_plus.dart';

class FreelancerProfilePage extends StatefulWidget {
  final Map<String, dynamic> provider;

  const FreelancerProfilePage({Key? key, required this.provider}) : super(key: key);

  @override
  State<FreelancerProfilePage> createState() => _FreelancerProfilePageState();
}

class _FreelancerProfilePageState extends State<FreelancerProfilePage> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  bool _isBioExpanded = false;
  List<Map<String, dynamic>> _gigs = [];
  bool _isLoadingGigs = false;
  Map<String, dynamic> _providerDetails = {};
  bool _isLoadingProvider = true;
  bool _isFavorite = false;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _providerDetails = widget.provider;
    _fetchGigs();
    _fetchProviderDetails();
  }

  Future<void> _fetchProviderDetails() async {
    final providerId = int.tryParse(widget.provider['id']?.toString() ?? '0') ?? 0;
    if (providerId == 0) return;

    setState(() => _isLoadingProvider = true);
    try {
      final details = await GigService().getProviderDetails(providerId);
      if (details != null && mounted) {
        setState(() {
          _providerDetails = details;
          _isFavorite = details['is_favorite'] == true || details['is_favorite'] == 1;
        });
      }
    } catch (e) {
      debugPrint('Error fetching provider details: $e');
    } finally {
      if (mounted) setState(() => _isLoadingProvider = false);
    }
  }

  Future<void> _fetchGigs() async {
    final providerId = int.tryParse(widget.provider['id']?.toString() ?? '0') ?? 0;
    if (providerId == 0) return;

    setState(() => _isLoadingGigs = true);
    try {
      final gigs = await GigService().getGigsByProvider(providerId);
      if (mounted) {
        setState(() {
          _gigs = gigs;
        });
      }
    } catch (e) {
      debugPrint('Error fetching provider gigs: $e');
    } finally {
      if (mounted) setState(() => _isLoadingGigs = false);
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final name = _providerDetails['name'] ?? widget.provider['name'] ?? 'Robius Sani';
    final image = _providerDetails['image'] ?? widget.provider['image'] ?? '';
    final username = _providerDetails['username'] ?? widget.provider['username'] ?? '@l33tgaming';
    final rating = double.tryParse(_providerDetails['rating']?.toString() ?? '0')?.toStringAsFixed(1) ?? '5.0';
    final reviews = _providerDetails['reviews_count']?.toString() ?? '0';
    final coverImage = _providerDetails['provider_profile']?['cover_image'] ?? '';

    return Scaffold(
      backgroundColor: Colors.white,
      body: NestedScrollView(
        headerSliverBuilder: (context, innerBoxIsScrolled) {
          return [
            SliverAppBar(
              backgroundColor: Colors.white,
              elevation: 0,
              pinned: true,
              floating: false,
              expandedHeight: 360,
              leading: Container(
                margin: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: Colors.white.withValues(alpha: 0.9),
                  shape: BoxShape.circle,
                ),
                child: IconButton(
                  icon: const Icon(Icons.arrow_back, color: Color(0xFF0F172A), size: 20),
                  onPressed: () => context.pop(),
                ),
              ),
              actions: [
                Container(
                  margin: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.9),
                    shape: BoxShape.circle,
                  ),
                  child: IconButton(
                    icon: const Icon(Icons.share_outlined, color: Color(0xFF0F172A), size: 20),
                    onPressed: () {},
                  ),
                ),
                Container(
                  margin: const EdgeInsets.only(right: 16, top: 8, bottom: 8),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.9),
                    shape: BoxShape.circle,
                  ),
                  child: IconButton(
                    icon: const Icon(Icons.favorite_border, color: Color(0xFF0F172A), size: 20),
                    onPressed: () {},
                  ),
                ),
              ],
              flexibleSpace: FlexibleSpaceBar(
                background: Stack(
                  fit: StackFit.expand,
                  children: [
                    // Cover Image with Gradient
                    Column(
                      children: [
                        Container(
                          height: 140,
                          decoration: const BoxDecoration(
                            gradient: LinearGradient(
                              colors: [Color(0xFF6366F1), Color(0xFF8B5CF6)],
                              begin: Alignment.topLeft,
                              end: Alignment.bottomRight,
                            ),
                          ),
                          child: Stack(
                            children: [
                              Positioned.fill(
                              child: Opacity(
                                opacity: 0.2,
                                child: CachedNetworkImage(
                                  imageUrl: (coverImage.isNotEmpty && coverImage.startsWith('http')) 
                                      ? coverImage 
                                      : 'https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=800&q=80',
                                  fit: BoxFit.cover,
                                  placeholder: (context, url) => Container(color: const Color(0xFF6366F1)),
                                  errorWidget: (context, url, error) => Container(color: const Color(0xFF6366F1)),
                                ),
                              ),
                            ),
                            ],
                          ),
                        ),
                      ],
                    ),
                    
                    // Profile Content
                    Positioned.fill(
                      top: 80,
                      child: Column(
                        children: [
                          Container(
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              border: Border.all(color: Colors.white, width: 4),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withValues(alpha: 0.1),
                                  blurRadius: 20,
                                  offset: const Offset(0, 10),
                                ),
                              ],
                            ),
                            child: CustomAvatar(
                              imageUrl: _getValidUrl(image),
                              name: name,
                              size: 110,
                            ),
                          ).animate().scale(duration: 400.ms, curve: Curves.easeOutBack),
                          
                          const SizedBox(height: 12),
                          
                          Text(
                            name,
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 24,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF0F172A),
                            ),
                          ),
                          
                          const SizedBox(height: 4),
                          
                          Text(
                            username,
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 15,
                              color: Colors.grey[500],
                            ),
                          ),

                          const SizedBox(height: 16),

                          // Action Buttons
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              ElevatedButton(
                                onPressed: () {
                                  _tabController.animateTo(1);
                                },
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: const Color(0xFF0F172A),
                                  foregroundColor: Colors.white,
                                  padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 12),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(24),
                                  ),
                                  elevation: 0,
                                ),
                                child: Text(
                                  'Hire Me',
                                  style: GoogleFonts.plusJakartaSans(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 14,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 12),
                              OutlinedButton(
                                onPressed: () {
                                  final providerId = _providerDetails['id'] ?? widget.provider['id'];
                                  if (providerId != null) {
                                    context.push('/chat-details', extra: {
                                      'id': providerId,
                                      'name': _providerDetails['name'] ?? widget.provider['name'],
                                      'image': _providerDetails['image'] ?? widget.provider['image']
                                    });
                                  }
                                },
                                style: OutlinedButton.styleFrom(
                                  foregroundColor: const Color(0xFF0F172A),
                                  side: const BorderSide(color: Color(0xFFE2E8F0)),
                                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(24),
                                  ),
                                ),
                                child: Text(
                                  'Message',
                                  style: GoogleFonts.plusJakartaSans(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 14,
                                  ),
                                ),
                              ),
                            ],
                          ),

                          const SizedBox(height: 24),

                          // Quick Stats
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              _buildQuickStat('Rating', rating, Icons.star_rounded, Colors.amber),
                              _buildVerticalDivider(),
                              _buildQuickStat('Reviews', reviews, Icons.chat_bubble_outline, Colors.blue),
                              _buildVerticalDivider(),
                              _buildQuickStat('Orders', '${_providerDetails['completed_orders'] ?? '0'}+', Icons.check_circle_outline, Colors.green),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              bottom: PreferredSize(
                preferredSize: const Size.fromHeight(48),
                child: Container(
                  decoration: BoxDecoration(
                    color: Colors.white,
                    border: Border(bottom: BorderSide(color: Colors.grey[100]!)),
                  ),
                  child: TabBar(
                    controller: _tabController,
                    labelColor: const Color(0xFF0F172A),
                    unselectedLabelColor: Colors.grey[500],
                    indicatorColor: const Color(0xFF0F172A),
                    indicatorWeight: 3,
                    labelStyle: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 14),
                    unselectedLabelStyle: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.w600, fontSize: 14),
                    tabs: const [
                      Tab(text: 'About'),
                      Tab(text: 'Gigs'),
                      Tab(text: 'Reviews'),
                      Tab(text: 'Portfolio'),
                    ],
                  ),
                ),
              ),
            ),
          ];
        },
        body: TabBarView(
          controller: _tabController,
          children: [
            _buildAboutTab(),
            _buildGigsTab(),
            _buildReviewsTab(),
            _buildPortfolioTab(),
          ],
        ),
      ),
    );
  }

  Widget _buildVerticalDivider() {
    return Container(
      height: 24,
      width: 1,
      margin: const EdgeInsets.symmetric(horizontal: 24),
      color: Colors.grey[200],
    );
  }

  Widget _buildQuickStat(String label, String value, IconData icon, Color iconColor) {
    return Column(
      children: [
        Row(
          children: [
            Icon(icon, size: 16, color: iconColor),
            const SizedBox(width: 4),
            Text(
              value,
              style: GoogleFonts.plusJakartaSans(
                fontWeight: FontWeight.bold,
                fontSize: 15,
                color: const Color(0xFF0F172A),
              ),
            ),
          ],
        ),
        const SizedBox(height: 2),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 12,
            color: Colors.grey[500],
            fontWeight: FontWeight.w500,
          ),
        ),
      ],
    );
  }

  String _getValidUrl(String? url) {
    if (url == null || url.isEmpty || url == 'default') {
      return '';
    }
    if (url.startsWith('http') || url.startsWith('assets')) return url;
    
    String cleanPath = url.startsWith('/') ? url.substring(1) : url;
    
    // Check if path already has storage/ prefix
    if (cleanPath.startsWith('storage/')) {
      return '${ApiConstants.baseUrl}/$cleanPath';
    }
    
    // Default to storage folder for relative paths
    return '${ApiConstants.baseUrl}/storage/$cleanPath';
  }

  Widget _buildAboutTab() {
    if (_isLoadingProvider) {
       return const Center(child: CircularProgressIndicator());
    }

    final profile = _providerDetails['provider_profile'] ?? {};
    final bio = profile['about'] ?? profile['bio'] ?? "Hi, I'm ${_providerDetails['name']}. I am a professional freelancer ready to help you with your projects.";
    
    String memberSince = 'N/A';
    if (_providerDetails['created_at'] != null) {
      try {
        final date = DateTime.parse(_providerDetails['created_at']);
        memberSince = DateFormat.yMMMM().format(date);
      } catch (e) {}
    }

    final from = _providerDetails['country']?['name'] ?? profile['address'] ?? 'Global';
    final lastDelivery = _providerDetails['last_delivery']?.toString() ?? 'N/A';
    final activeOrders = _providerDetails['active_orders']?.toString() ?? '0';

    List<Map<String, String>> languages = [];
    if (profile['languages'] is List) {
       for (var l in profile['languages']) {
         if (l is Map) {
            languages.add({
              'language': l['language']?.toString() ?? 'Unknown',
              'level': l['level']?.toString() ?? 'Fluent'
            });
         } else if (l is String) {
            languages.add({
              'language': l,
              'level': 'Fluent'
            });
         }
       }
    }
    if (languages.isEmpty) {
        languages.add({'language': 'English', 'level': 'Fluent'});
    }

    List<String> skills = [];
    if (profile['skills'] is List) {
      skills = (profile['skills'] as List).map((e) => e.toString()).toList();
    } else if (profile['skills'] is String) {
       skills = profile['skills'].toString().split(',').map((e) => e.trim()).toList();
    }
    
    // Fallback if no skills
    if (skills.isEmpty) skills = ['Freelancer', 'Professional'];

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'About Me',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 16),
          Text(
            bio,
            maxLines: _isBioExpanded ? null : 4,
            overflow: _isBioExpanded ? TextOverflow.visible : TextOverflow.ellipsis,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 15,
              height: 1.8,
              color: const Color(0xFF334155),
            ),
          ),
          GestureDetector(
            onTap: () => setState(() => _isBioExpanded = !_isBioExpanded),
            child: Padding(
              padding: const EdgeInsets.only(top: 8),
              child: Text(
                _isBioExpanded ? 'Read less' : 'Read more',
                style: GoogleFonts.plusJakartaSans(
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF6366F1),
                ),
              ),
            ),
          ),
          const SizedBox(height: 32),
          
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: const Color(0xFFF8FAFC),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Column(
              children: [
                _buildInfoRow(Icons.location_on_outlined, 'From', from),
                _buildInfoRow(Icons.person_outline, 'Member since', memberSince),
                _buildInfoRow(Icons.access_time, 'Last delivery', lastDelivery),
                _buildInfoRow(Icons.work_outline, 'Active orders', activeOrders),
                _buildInfoRow(Icons.visibility_outlined, 'Last active', 'Today', isLast: true),
              ],
            ),
          ),
          
          const SizedBox(height: 32),
          
          Text(
            'Languages',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 16),
          Wrap(
            spacing: 12,
            runSpacing: 12,
            children: languages.map((l) => Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    l['language']!,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                      color: const Color(0xFF0F172A),
                    ),
                  ),
                  Container(
                    width: 1,
                    height: 12,
                    color: Colors.grey[300],
                    margin: const EdgeInsets.symmetric(horizontal: 8),
                  ),
                  Text(
                    l['level']!,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 14,
                      color: Colors.grey[500],
                    ),
                  ),
                ],
              ),
            )).toList(),
          ),

          const SizedBox(height: 32),

          Text(
            'Skills',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 16),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: skills.map((skill) => Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                color: const Color(0xFFF1F5F9),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(
                skill,
                style: GoogleFonts.plusJakartaSans(
                  color: const Color(0xFF475569),
                  fontSize: 14,
                  fontWeight: FontWeight.w500,
                ),
              ),
            )).toList(),
          ),
          const SizedBox(height: 40),
        ],
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value, {bool isLast = false}) {
    return Padding(
      padding: EdgeInsets.only(bottom: isLast ? 0 : 16),
      child: Row(
        children: [
          Icon(icon, size: 20, color: Colors.grey[400]),
          const SizedBox(width: 12),
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              color: Colors.grey[500],
            ),
          ),
          const Spacer(),
          Text(
            value,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: const Color(0xFF0F172A),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildGigsTab() {
    if (_isLoadingGigs) {
      return const Center(child: CircularProgressIndicator());
    }
    if (_gigs.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.work_off_outlined, size: 64, color: Colors.grey[300]),
            const SizedBox(height: 16),
            Text('No gigs available', style: GoogleFonts.plusJakartaSans(color: Colors.grey[500])),
          ],
        ),
      );
    }

    final name = widget.provider['name'] ?? 'Seller';
    final image = widget.provider['image'] ?? '';

    return GridView.builder(
      padding: const EdgeInsets.all(20),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        childAspectRatio: 0.68,
        crossAxisSpacing: 16,
        mainAxisSpacing: 16,
      ),
      itemCount: _gigs.length,
      itemBuilder: (context, index) {
        final gig = _gigs[index];
        final gigImage = _getValidUrl(gig['thumbnail_image'] ?? gig['image']);
        final title = gig['title'] ?? gig['name'] ?? 'Untitled';
        final rating = double.tryParse(gig['rating']?.toString() ?? '0') ?? 0.0;
        final reviews = (gig['reviews'] as List?)?.length ?? 0;
        
        // Price logic
        String price = '0';
        if (gig['packages'] != null && (gig['packages'] as List).isNotEmpty) {
           final packages = gig['packages'] as List;
           final basic = packages.firstWhere((p) => p['tier'] == 'Basic', orElse: () => packages.first);
           price = basic['price']?.toString() ?? '0';
        } else {
           price = gig['price']?.toString() ?? '0';
        }

        return GestureDetector(
          onTap: () {
             // Navigate to gig details
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
                    imageUrl: gigImage.isNotEmpty 
                        ? gigImage 
                        : 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop',
                    height: 140,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Container(
                      color: Colors.grey[200],
                      child: const Center(child: CircularProgressIndicator(strokeWidth: 2)),
                    ),
                    errorWidget: (context, url, error) => Container(
                      color: Colors.grey[200],
                      child: const Icon(Icons.error),
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
                              imageUrl: (image.isNotEmpty && !image.contains('default.png') && !image.contains('via.placeholder.com')) ? image : null,
                              name: name,
                              size: 20,
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: Text(
                                name,
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                                style: GoogleFonts.plusJakartaSans(
                                  fontSize: 12, 
                                  fontWeight: FontWeight.bold, 
                                  color: const Color(0xFF0F172A)
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
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                const Icon(Icons.star_rounded, size: 16, color: Colors.amber),
                                const SizedBox(width: 4),
                                Text(
                                  rating.toStringAsFixed(1), 
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 13, 
                                    fontWeight: FontWeight.bold,
                                    color: const Color(0xFF0F172A)
                                  )
                                ),
                                const SizedBox(width: 2),
                                Text(
                                  '($reviews)', 
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 12, 
                                    color: Colors.grey[500]
                                  )
                                ),
                              ],
                            ),
                            Text(
                              '\$$price',
                              style: GoogleFonts.plusJakartaSans(
                                fontWeight: FontWeight.bold, 
                                fontSize: 16, 
                                color: const Color(0xFF0F172A)
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
          ),
        );
      },
    );
  }

  Widget _buildReviewsTab() {
    if (_isLoadingProvider) return const Center(child: CircularProgressIndicator());

    final List reviews = _providerDetails['reviews'] ?? [];
    
    if (reviews.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.chat_bubble_outline, size: 64, color: Colors.grey[300]),
            const SizedBox(height: 16),
            Text('No reviews yet', style: GoogleFonts.plusJakartaSans(color: Colors.grey[500])),
          ],
        ),
      );
    }

    return ListView.separated(
      padding: const EdgeInsets.all(20),
      itemCount: reviews.length,
      separatorBuilder: (_, __) => const SizedBox(height: 24),
      itemBuilder: (context, index) {
        final review = reviews[index];
        final user = review['user'] ?? {};
        final userName = user['name'] ?? 'Anonymous';
        final userImage = user['profile_image'] ?? user['image'];
        final rating = review['rating']?.toString() ?? '5.0';
        final comment = review['review'] ?? review['comment'] ?? '';
        final date = review['created_at'] != null 
            ? DateFormat.yMMMd().format(DateTime.parse(review['created_at']))
            : '';

        return Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
             color: Colors.white,
             borderRadius: BorderRadius.circular(12),
             border: Border.all(color: const Color(0xFFF1F5F9)),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  CustomAvatar(
                    imageUrl: (userImage != null && !userImage.contains('default')) ? userImage : null,
                    name: userName,
                    size: 40,
                  ),
                  const SizedBox(width: 12),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        userName,
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.bold,
                          fontSize: 14,
                          color: const Color(0xFF0F172A),
                        ),
                      ),
                      Row(
                        children: [
                          const Icon(Icons.star_rounded, size: 14, color: Colors.amber),
                          const SizedBox(width: 4),
                          Text(
                            rating,
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.bold,
                              fontSize: 12,
                              color: const Color(0xFF0F172A),
                            ),
                          ),
                          const SizedBox(width: 8),
                          Text(
                            date,
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 12,
                              color: Colors.grey[500],
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
              const SizedBox(height: 12),
              Text(
                comment,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 14,
                  color: const Color(0xFF334155),
                  height: 1.5,
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildPortfolioTab() {
    if (_isLoadingProvider) return const Center(child: CircularProgressIndicator());

    final portfolios = _providerDetails['freelancer_portfolios'] ?? [];
    
    if (portfolios.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
             Icon(Icons.image_not_supported_outlined, size: 64, color: Colors.grey[300]),
             const SizedBox(height: 16),
             Text('No portfolio items', style: GoogleFonts.plusJakartaSans(color: Colors.grey[500])),
          ],
        ),
      );
    }

    return GridView.builder(
      padding: const EdgeInsets.all(20),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        crossAxisSpacing: 12,
        mainAxisSpacing: 12,
        childAspectRatio: 1,
      ),
      itemCount: portfolios.length,
      itemBuilder: (context, index) {
        final item = portfolios[index];
        final image = _getValidUrl(item['image_url'] ?? item['image']);
        final title = item['title'] ?? '';

        return ClipRRect(
          borderRadius: BorderRadius.circular(16),
          child: Stack(
            fit: StackFit.expand,
            children: [
              CachedNetworkImage(
                imageUrl: image.isNotEmpty ? image : 'https://placehold.co/400x400?text=No+Image',
                fit: BoxFit.cover,
                placeholder: (context, url) => Container(
                  color: Colors.grey[200],
                  child: const Center(child: CircularProgressIndicator(strokeWidth: 2)),
                ),
                errorWidget: (context, url, error) => Container(
                  color: Colors.grey[200],
                  child: const Icon(Icons.error),
                ),
              ),
              if (title.isNotEmpty)
                Positioned(
                  bottom: 0,
                  left: 0,
                  right: 0,
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.bottomCenter,
                        end: Alignment.topCenter,
                        colors: [Colors.black.withValues(alpha: 0.8), Colors.transparent],
                      ),
                    ),
                    child: Text(
                      title,
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white,
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                ),
            ],
          ),
        );
      },
    );
  }
}
