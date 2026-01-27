import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/services.dart';
import '../../../home/presentation/providers/home_provider.dart';
import '../../../home/presentation/widgets/spark_interest_section.dart';
import '../../../home/presentation/widgets/testimonials_section.dart';
import '../../../home/presentation/widgets/flash_sale_section.dart';
import '../../../home/presentation/widgets/trust_safety_section.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import 'package:flutter_customer/core/widgets/custom_avatar.dart';

class FreelancerHomeContent extends StatefulWidget {
  const FreelancerHomeContent({super.key});

  @override
  State<FreelancerHomeContent> createState() => _FreelancerHomeContentState();
}

class _FreelancerHomeContentState extends State<FreelancerHomeContent> {
  int _currentBannerIndex = 0;

  @override
  Widget build(BuildContext context) {
    return Consumer<HomeProvider>(
      builder: (context, provider, child) {
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Banners
            if (provider.banners.isNotEmpty) ...[
              const SizedBox(height: 24),
              _buildBanners(provider.banners).animate().fadeIn(delay: 200.ms),
            ],

            // Flash Sale (New)
            if (provider.flashSale != null) ...[
              const SizedBox(height: 32),
              FlashSaleSection(data: provider.flashSale!).animate().fadeIn(delay: 220.ms),
            ],

            // Categories
            const SizedBox(height: 32),
            _buildSectionHeader(context, 'Categories', 'View All', () => context.push('/category?type=freelancer'))
                .animate().fadeIn(delay: 250.ms),
            const SizedBox(height: 16),
            _buildCategories(context, provider).animate().fadeIn(delay: 300.ms),

            // Popular Services
            if (provider.popularServices.isNotEmpty || provider.isLoading) ...[
              const SizedBox(height: 32),
              _buildSectionHeader(context, 'Popular Freelancers', 'View All', () => context.push('/popular?type=freelancer'))
                  .animate().fadeIn(delay: 350.ms),
              const SizedBox(height: 16),
              _buildHorizontalGigList(context, provider.popularServices, provider.isLoading)
                  .animate().fadeIn(delay: 400.ms),
            ],

            // Recently Viewed (New)
            if (provider.recentlyViewed.isNotEmpty) ...[
              const SizedBox(height: 32),
              _buildSectionHeader(context, 'Recently Viewed', 'History', () => {})
                  .animate().fadeIn(delay: 450.ms),
              const SizedBox(height: 16),
              _buildHorizontalGigList(context, provider.recentlyViewed, false)
                  .animate().fadeIn(delay: 500.ms),
            ],

            // Single Promotional Banner (New)
            if (provider.singleBanner != null) ...[
              const SizedBox(height: 32),
              _buildSingleBanner(provider.singleBanner!).animate().fadeIn(delay: 550.ms),
            ],

            // Left/Right Banners (New)
            if (provider.promotionalBanners.length >= 2) ...[
              const SizedBox(height: 32),
              _buildLeftRightBanners(provider.promotionalBanners).animate().fadeIn(delay: 600.ms),
            ],

            // Recently Saved (New)
            if (provider.recentlySaved.isNotEmpty) ...[
              const SizedBox(height: 32),
              _buildSectionHeader(context, 'Recently Saved', 'See All', () => {})
                  .animate().fadeIn(delay: 650.ms),
              const SizedBox(height: 16),
              _buildHorizontalGigList(context, provider.recentlySaved, false)
                  .animate().fadeIn(delay: 700.ms),
            ],

            // What Sparks Your Interest (Selection)
            const SizedBox(height: 32),
            const SparkInterestSection()
                .animate().fadeIn(delay: 750.ms),

            // Referral Card (New)
            if (provider.referral != null) ...[
              const SizedBox(height: 32),
              _buildReferralCard(provider.referral!).animate().fadeIn(delay: 850.ms),
            ],

            // Testimonials (New)
            if (provider.testimonials.isNotEmpty) ...[
              const SizedBox(height: 32),
              TestimonialsSection(items: provider.testimonials).animate().fadeIn(delay: 880.ms),
            ],

            // Trust & Safety (New)
            if (provider.trustSafety.isNotEmpty) ...[
              const SizedBox(height: 32),
              TrustSafetySection(items: provider.trustSafety).animate().fadeIn(delay: 890.ms),
            ],

            // Inspired by Browsing History (New)
            const SizedBox(height: 32),
            _buildSectionHeader(context, 'Inspired by your history', '', () => {})
                .animate().fadeIn(delay: 900.ms),
            const SizedBox(height: 16),
            _buildHorizontalServiceList(context, provider.recommendedServices, provider.isLoading)
                .animate().fadeIn(delay: 950.ms),

            // New Gigs (New)
          if (provider.newServices.isNotEmpty) ...[
            const SizedBox(height: 32),
            _buildSectionHeader(context, 'New Gigs', 'View All', () => context.push('/new-gigs'))
                .animate().fadeIn(delay: 1000.ms),
            const SizedBox(height: 16),
            _buildHorizontalServiceList(context, provider.newServices, provider.isLoading)
                .animate().fadeIn(delay: 1050.ms),
          ],
          ],
        );
      },
    );
  }

  // --- Widgets ---

  Widget _buildSectionHeader(BuildContext context, String title, String action, VoidCallback onTap) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            title,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: const Color(0xFF0F172A),
              letterSpacing: -0.5,
            ),
          ),
          if (action.isNotEmpty)
            GestureDetector(
              onTap: onTap,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  action,
                  style: GoogleFonts.plusJakartaSans(
                    color: const Color(0xFF6366F1),
                    fontWeight: FontWeight.w600,
                    fontSize: 12,
                  ),
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildBanners(List<dynamic> banners) {
    return Column(
      children: [
        CarouselSlider(
          options: CarouselOptions(
            height: 180,
            autoPlay: true,
            enlargeCenterPage: true,
            viewportFraction: 0.92,
            autoPlayInterval: const Duration(seconds: 5),
            autoPlayAnimationDuration: const Duration(milliseconds: 800),
            autoPlayCurve: Curves.fastOutSlowIn,
            onPageChanged: (index, reason) {
              setState(() {
                _currentBannerIndex = index;
              });
            },
          ),
          items: banners.map((banner) {
            return Builder(
              builder: (BuildContext context) {
                return Container(
                  width: MediaQuery.of(context).size.width,
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(24),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.1),
                        blurRadius: 15,
                        offset: const Offset(0, 8),
                      ),
                    ],
                  ),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(24),
                    child: CachedNetworkImage(
                      imageUrl: (banner['image'] != null && banner['image'].toString().isNotEmpty) 
                          ? banner['image'] 
                          : 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Container(
                          color: Colors.grey[200],
                        ),
                        errorWidget: (context, url, error) => Container(
                          color: Colors.grey[200],
                          child: const Icon(Icons.error),
                        ),
                    ),
                  ),
                );
              },
            );
          }).toList(),
        ),
        const SizedBox(height: 16),
        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: banners.asMap().entries.map((entry) {
            return AnimatedContainer(
              duration: const Duration(milliseconds: 300),
              width: _currentBannerIndex == entry.key ? 24 : 8,
              height: 8,
              margin: const EdgeInsets.symmetric(horizontal: 4),
              decoration: BoxDecoration(
                color: _currentBannerIndex == entry.key
                    ? const Color(0xFF0F172A)
                    : Colors.grey[300],
                borderRadius: BorderRadius.circular(4),
              ),
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildCategories(BuildContext context, HomeProvider provider) {
    if (provider.isLoading) {
      return SizedBox(
        height: 110,
        child: ListView.separated(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 24),
          itemCount: 6,
          separatorBuilder: (context, index) => const SizedBox(width: 16),
          itemBuilder: (context, index) {
            return Column(
              children: [
                _ShimmerBox(
                  width: 72,
                  height: 72,
                  borderRadius: BorderRadius.circular(36),
                ),
                const SizedBox(height: 8),
                _ShimmerBox(
                  width: 60,
                  height: 10,
                  borderRadius: BorderRadius.circular(6),
                ),
              ],
            );
          },
        ),
      );
    }
    
    final categories = provider.categories.isNotEmpty 
        ? provider.categories 
        : [
            {'icon': Icons.code, 'name': 'Development', 'color': 0xFF6366F1},
            {'icon': Icons.design_services, 'name': 'Design', 'color': 0xFFEC4899},
            {'icon': Icons.video_camera_back, 'name': 'Video', 'color': 0xFFF59E0B},
            {'icon': Icons.campaign, 'name': 'Marketing', 'color': 0xFF10B981},
            {'icon': Icons.translate, 'name': 'Writing', 'color': 0xFF8B5CF6},
          ];

    return SizedBox(
      height: 110,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 24),
        itemCount: categories.length,
        separatorBuilder: (context, index) => const SizedBox(width: 16),
        itemBuilder: (context, index) {
          final cat = categories[index];
          return _buildCategoryItem(context, cat, index);
        },
      ),
    );
  }

  Widget _buildCategoryItem(BuildContext context, dynamic cat, int index) {
    var iconValue = cat['image'] ?? cat['icon'];
    final name = cat['name'] as String? ?? 'Unknown';
    
    if (iconValue is String && !iconValue.startsWith('http') && !iconValue.startsWith('assets')) {
        iconValue = '${ApiConstants.baseUrl}/storage/$iconValue';
    }

    final isUrl = iconValue is String && (iconValue.startsWith('http') || iconValue.startsWith('assets'));

    return GestureDetector(
      onTap: () {
        HapticFeedback.lightImpact();
        context.push('/freelancer-category', extra: cat);
      },
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            width: 72,
            height: 72,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(24),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.05),
                  blurRadius: 10,
                  offset: const Offset(0, 4),
                ),
              ],
              border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
            ),
            child: isUrl 
                ? CachedNetworkImage(imageUrl: iconValue, fit: BoxFit.cover)
                : Icon(
                    iconValue is IconData ? iconValue : Icons.category_rounded,
                    color: const Color(0xFF0F172A),
                    size: 32,
                  ),
          ),
          const SizedBox(height: 10),
          Text(
            name,
            style: GoogleFonts.plusJakartaSans(
              color: const Color(0xFF64748B),
              fontWeight: FontWeight.w600,
              fontSize: 12,
            ),
          ),
        ],
      ).animate().fadeIn(delay: (index * 50).ms).scale(),
    );
  }

  Widget _buildHorizontalServiceList(BuildContext context, List<dynamic> items, bool isLoading) {
    return _buildHorizontalGigList(context, items, isLoading);
  }

  Widget _buildHorizontalGigList(BuildContext context, List<dynamic> items, bool isLoading) {
    return _buildGigList(context, items, isLoading);
  }

  Widget _buildGigList(BuildContext context, List<dynamic> gigs, bool isLoading) {
    debugPrint('[FreelancerHomeView] _buildGigList: isLoading=$isLoading gigsLen=${gigs.length}');
    if (isLoading) {
      debugPrint('[FreelancerHomeView] _buildGigList: showing shimmer (loading)');
      return SizedBox(
        height: 220,
        child: ListView.separated(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 24),
          itemCount: 3,
          separatorBuilder: (context, index) => const SizedBox(width: 16),
          itemBuilder: (context, index) {
            return Container(
              width: 280,
              decoration: BoxDecoration(
                color: Theme.of(context).cardColor,
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.03),
                    blurRadius: 6,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    child: ClipRRect(
                      borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                      child: _ShimmerBox(
                        width: 280,
                        height: double.infinity,
                        borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: const [
                         // Add shimmer content if needed
                      ],
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      );
    }
    
    if (gigs.isEmpty) {
      return Padding(
        padding: const EdgeInsets.symmetric(horizontal: 24),
        child: Text(
          'No gigs found.',
          style: GoogleFonts.plusJakartaSans(color: Colors.grey),
        ),
      );
    }

    return SizedBox(
      height: 280,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 24),
        itemCount: gigs.length,
        separatorBuilder: (context, index) => const SizedBox(width: 16),
        itemBuilder: (context, index) {
          final gig = gigs[index];
          return _buildGigCard(context, gig);
        },
      ),
    );
  }

  Widget _buildGigCard(BuildContext context, dynamic gig) {
    var imageUrl = gig['thumbnail'] ?? gig['image'] ?? '';
    if (imageUrl.toString().isNotEmpty && !imageUrl.toString().startsWith('http') && !imageUrl.toString().startsWith('assets')) {
         imageUrl = '${ApiConstants.baseUrl}/storage/$imageUrl';
    }
    final hasImage = imageUrl.toString().isNotEmpty;

    // Extract seller info
    final provider = gig['provider'];
    final sellerName = provider is Map ? (provider['name'] ?? 'Seller') : 'Seller';
    var sellerImage = provider is Map && provider['image'] != null ? provider['image'] : '';
    if (sellerImage.toString().isNotEmpty && !sellerImage.toString().startsWith('http') && !sellerImage.toString().startsWith('assets')) {
         sellerImage = '${ApiConstants.baseUrl}/storage/$sellerImage';
    }

    final reviewsData = gig['reviews'];
    final reviewsCount = (reviewsData is List) ? reviewsData.length : (gig['reviews_count'] ?? 0);
    final ratingVal = gig['rating'] ?? 0.0;
    final rating = ratingVal.toString();

    final price = gig['price'] ?? '0';

    return GestureDetector(
          onTap: () {
            context.push('/freelancer-gig-details', extra: gig);
          },
          child: Container(
        width: 220,
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
            // Image
            ClipRRect(
              borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
              child: CachedNetworkImage(
                imageUrl: hasImage ? imageUrl : 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop',
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
            
            // Content
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
                    gig['name'] ?? 'Untitled Gig',
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                      color: const Color(0xFF0F172A),
                      height: 1.3,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      const Icon(Icons.star_rounded, size: 14, color: Colors.amber),
                      const SizedBox(width: 4),
                      Text(
                        '$rating ($reviewsCount)', 
                        style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey[600])
                      ),
                      const Spacer(),
                      Text(
                        'From \$$price',
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.bold, 
                          fontSize: 14, 
                          color: const Color(0xFF0F172A)
                        ),
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

  Widget _buildSingleBanner(dynamic banner) {
    var imageUrl = banner['image_url'] ?? banner['image'] ?? '';
    if (imageUrl.toString().isNotEmpty && !imageUrl.toString().startsWith('http')) {
        imageUrl = '${ApiConstants.baseUrl}/storage/$imageUrl';
    }

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 24),
      height: 160,
      width: double.infinity,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.04),
            blurRadius: 12,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(24),
        child: CachedNetworkImage(
          imageUrl: imageUrl,
          fit: BoxFit.cover,
        ),
      ),
    );
  }

  Widget _buildLeftRightBanners(List<dynamic> banners) {
    return Container(
      height: 140,
      margin: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        children: [
          Expanded(
            child: ClipRRect(
              borderRadius: BorderRadius.circular(20),
              child: CachedNetworkImage(
                imageUrl: banners[0]['image'] ?? '',
                fit: BoxFit.cover,
                height: double.infinity,
              ),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: ClipRRect(
              borderRadius: BorderRadius.circular(20),
              child: CachedNetworkImage(
                imageUrl: banners[1]['image'] ?? '',
                fit: BoxFit.cover,
                height: double.infinity,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildReferralCard(Map<String, dynamic> referral) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 24),
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF6366F1), Color(0xFF8B5CF6)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF6366F1).withValues(alpha: 0.25),
            blurRadius: 15,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  referral['title'] ?? 'Refer & Earn',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 20,
                    fontWeight: FontWeight.w800,
                    color: Colors.white,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  referral['subtitle'] ?? 'Invite friends and earn rewards',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 14,
                    color: Colors.white.withValues(alpha: 0.9),
                  ),
                ),
                const SizedBox(height: 16),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Text(
                    'Invite Now',
                    style: GoogleFonts.plusJakartaSans(
                      color: const Color(0xFF6366F1),
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
              ],
            ),
          ),
          const Icon(Icons.card_giftcard, size: 80, color: Colors.white),
        ],
      ),
    );
  }
}

class _ShimmerBox extends StatelessWidget {
  final double width;
  final double height;
  final BorderRadius? borderRadius;

  const _ShimmerBox({required this.width, required this.height, this.borderRadius});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: width,
      height: height,
      decoration: BoxDecoration(
        color: Colors.grey[300],
        borderRadius: borderRadius ?? BorderRadius.circular(12),
      ),
    ).animate(onPlay: (controller) => controller.repeat())
    .shimmer(duration: 1200.ms, color: Colors.grey[100]);
  }
}
