import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'dart:ui';
import '../providers/home_provider.dart';
import 'spark_interest_section.dart';
import 'testimonials_section.dart';
import 'package:flutter/services.dart';
import 'flash_sale_section.dart';
import 'trust_safety_section.dart';

class HomeView extends StatefulWidget {
  const HomeView({super.key});

  @override
  State<HomeView> createState() => _HomeViewState();
}

class _HomeViewState extends State<HomeView> {
  int _currentBannerIndex = 0;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<HomeProvider>().loadHomeData();
    });
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: () => context.read<HomeProvider>().loadHomeData(),
      color: const Color(0xFF0F172A),
      backgroundColor: Colors.white,
      child: CustomScrollView(
        slivers: [
          // 1. Header & Search
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.only(top: 60, left: 24, right: 24, bottom: 10),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildHeader().animate().fadeIn().slideY(begin: -0.5),
                  const SizedBox(height: 24),
                  _buildSearchBar(context).animate().fadeIn(delay: 100.ms).slideY(begin: 0.2),
                ],
              ),
            ),
          ),

          // 2. Main Content
          SliverPadding(
            padding: const EdgeInsets.only(bottom: 120),
            sliver: SliverToBoxAdapter(
              child: Consumer<HomeProvider>(
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
                      _buildSectionHeader(context, 'Categories', 'View All', () => context.go('/category'))
                          .animate().fadeIn(delay: 250.ms),
                      const SizedBox(height: 16),
                      _buildCategories(context, provider).animate().fadeIn(delay: 300.ms),

                      // Popular Services
                      const SizedBox(height: 32),
                      _buildSectionHeader(context, 'Popular Services', 'View All', () => context.push('/popular'))
                          .animate().fadeIn(delay: 350.ms),
                      const SizedBox(height: 16),
                      _buildHorizontalServiceList(context, provider.popularServices, provider.isLoading)
                          .animate().fadeIn(delay: 400.ms),

                      // Recently Viewed (New)
                      if (provider.recentlyViewed.isNotEmpty) ...[
                        const SizedBox(height: 32),
                        _buildSectionHeader(context, 'Recently Viewed', 'History', () => {})
                            .animate().fadeIn(delay: 450.ms),
                        const SizedBox(height: 16),
                        _buildHorizontalServiceList(context, provider.recentlyViewed, false)
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
                        _buildHorizontalServiceList(context, provider.recentlySaved, false)
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
                    ],
                  );
                },
              ),
            ),
          ),
        ],
      ),
    );
  }

  // --- Widgets ---

  Widget _buildHeader() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Good Morning,',
              style: GoogleFonts.plusJakartaSans(
                color: Colors.grey[600],
                fontSize: 14,
                fontWeight: FontWeight.w500,
                letterSpacing: 0.5,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              'Find Your Service',
              style: GoogleFonts.plusJakartaSans(
                color: const Color(0xFF0F172A),
                fontSize: 26,
                fontWeight: FontWeight.w800,
                letterSpacing: -0.5,
              ),
            ),
          ],
        ),
        Container(
          padding: const EdgeInsets.all(3),
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            gradient: const LinearGradient(
              colors: [Color(0xFF6366F1), Color(0xFF8B5CF6)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF6366F1).withValues(alpha: 0.3),
                blurRadius: 12,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: CircleAvatar(
            radius: 24,
            backgroundColor: Colors.white,
            child: CircleAvatar(
              radius: 22,
              backgroundImage: const NetworkImage('https://i.pravatar.cc/150?img=12'),
              onBackgroundImageError: (_, __) => const Icon(Icons.person),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSearchBar(BuildContext context) {
    return GestureDetector(
      onTap: () {
        HapticFeedback.lightImpact();
        context.push('/search-page');
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.05),
              blurRadius: 20,
              offset: const Offset(0, 10),
              spreadRadius: -5,
            ),
          ],
        ),
        child: Row(
          children: [
            Icon(Icons.search_rounded, color: Colors.grey[400], size: 24),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                'Search for cleaning, repair...',
                style: GoogleFonts.plusJakartaSans(
                  color: Colors.grey[400],
                  fontSize: 15,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: const Color(0xFF0F172A),
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: const Color(0xFF0F172A).withValues(alpha: 0.3),
                    blurRadius: 8,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: const Icon(Icons.tune_rounded, color: Colors.white, size: 18),
            ),
          ],
        ),
      ),
    );
  }

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
                      imageUrl: banner['image'] ?? '',
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Image.asset(
                        'assets/images/placeholder.png',
                        fit: BoxFit.cover,
                      ),
                      errorWidget: (context, url, error) => Image.asset(
                        'assets/images/placeholder.png',
                        fit: BoxFit.cover,
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
            {'icon': Icons.cleaning_services, 'name': 'Cleaning', 'color': 0xFF6366F1},
            {'icon': Icons.plumbing, 'name': 'Plumbing', 'color': 0xFFEC4899},
            {'icon': Icons.electric_bolt, 'name': 'Electric', 'color': 0xFFF59E0B},
            {'icon': Icons.format_paint, 'name': 'Painting', 'color': 0xFF10B981},
            {'icon': Icons.spa, 'name': 'Beauty', 'color': 0xFF8B5CF6},
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
    final iconValue = cat['icon'];
    final name = cat['name'] as String? ?? 'Unknown';
    final isUrl = iconValue is String && (iconValue.startsWith('http') || iconValue.startsWith('assets'));

    return GestureDetector(
      onTap: () {
        HapticFeedback.lightImpact();
        context.push('/category', extra: cat);
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
    return _buildServiceList(context, items, isLoading);
  }

  Widget _buildServiceList(BuildContext context, List<dynamic> services, bool isLoading) {
    debugPrint('[HomeView] _buildServiceList: isLoading=$isLoading servicesLen=${services.length}');
    if (isLoading) {
      debugPrint('[HomeView] _buildServiceList: showing shimmer (loading)');
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
                borderRadius: BorderRadius.circular(24),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.05),
                    blurRadius: 15,
                    offset: const Offset(0, 8),
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    child: ClipRRect(
                      borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
                      child: _ShimmerBox(
                        width: 280,
                        height: double.infinity,
                        borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: const [
                        _ShimmerBox(width: 180, height: 14, borderRadius: BorderRadius.all(Radius.circular(8))),
                        SizedBox(height: 8),
                        _ShimmerBox(width: 120, height: 12, borderRadius: BorderRadius.all(Radius.circular(8))),
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

    if (services.isEmpty) {
      debugPrint('[HomeView] _buildServiceList: empty services, showing shimmer');
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
                borderRadius: BorderRadius.circular(24),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.05),
                    blurRadius: 15,
                    offset: const Offset(0, 8),
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    child: ClipRRect(
                      borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
                      child: _ShimmerBox(
                        width: 280,
                        height: double.infinity,
                        borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: const [
                        _ShimmerBox(width: 180, height: 14, borderRadius: BorderRadius.all(Radius.circular(8))),
                        SizedBox(height: 8),
                        _ShimmerBox(width: 120, height: 12, borderRadius: BorderRadius.all(Radius.circular(8))),
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

    debugPrint('[HomeView] _buildServiceList: rendering ${services.length} services');
    return SizedBox(
      height: 290,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 10),
        itemCount: services.length,
        separatorBuilder: (context, index) => const SizedBox(width: 20),
        itemBuilder: (context, index) {
          final service = services[index];
          return _buildServiceCard(context, service);
        },
      ),
    );
  }

  Widget _buildServiceCard(BuildContext context, dynamic service) {
    final title = service?['name'] ?? 'Professional Cleaning';
    final price = service?['price'] ?? '80.00';
    final rating = service?['rating'] ?? 4.8;
    final reviews = service?['reviews_count'] ?? 120;
    final image = service?['image'] ?? 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
    final providerName = service?['provider']?['name'] ?? 'John Doe';

    return GestureDetector(
      onTap: service != null
          ? () {
              HapticFeedback.selectionClick();
              context.push('/service-details', extra: service);
            }
          : null,
      child: Container(
        width: 240,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.06),
              blurRadius: 20,
              offset: const Offset(0, 10),
              spreadRadius: -2,
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image
            Stack(
              children: [
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
                  child: CachedNetworkImage(
                    imageUrl: image,
                    height: 140,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Container(color: Colors.grey[200]),
                    errorWidget: (context, url, error) => Container(
                      height: 140,
                      color: Colors.grey[200],
                      alignment: Alignment.center,
                      child: Icon(Icons.image_not_supported_rounded, color: Colors.grey[500]),
                    ),
                  ),
                ),
                Positioned(
                  top: 12,
                  right: 12,
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.9),
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withValues(alpha: 0.1),
                          blurRadius: 8,
                          offset: const Offset(0, 2),
                        ),
                      ],
                    ),
                    child: const Icon(Icons.favorite_border_rounded, size: 20, color: Color(0xFFEF4444)),
                  ),
                ),
                Positioned(
                  top: 12,
                  left: 12,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    decoration: BoxDecoration(
                      color: const Color(0xFF0F172A).withValues(alpha: 0.8),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.star_rounded, color: Color(0xFFF59E0B), size: 14),
                        const SizedBox(width: 4),
                        Text(
                          '$rating',
                          style: GoogleFonts.plusJakartaSans(
                            color: Colors.white,
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
            // Details
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF0F172A),
                    ),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      CircleAvatar(
                        radius: 10,
                        backgroundColor: Colors.grey[200],
                        backgroundImage: const NetworkImage('https://i.pravatar.cc/150?img=5'),
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          providerName,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 12,
                            color: const Color(0xFF64748B),
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        '\$$price',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 18,
                          fontWeight: FontWeight.w800,
                          color: const Color(0xFF6366F1),
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: const Color(0xFFF1F5F9),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Text(
                          'Book',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                            color: const Color(0xFF0F172A),
                          ),
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

  Widget _buildSingleBanner(Map<String, dynamic> banner) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(24),
        child: SizedBox(
          height: 160,
          width: double.infinity,
          child: Stack(
            children: [
              CachedNetworkImage(
                imageUrl: banner['image'] ?? 'https://via.placeholder.com/800x400',
                fit: BoxFit.cover,
                width: double.infinity,
                height: double.infinity,
                errorWidget: (context, url, error) => Container(
                  color: Colors.grey[200],
                  alignment: Alignment.center,
                  child: Icon(Icons.image_not_supported_rounded, color: Colors.grey[500]),
                ),
              ),
              Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [Colors.black.withValues(alpha: 0.6), Colors.transparent],
                    begin: Alignment.bottomLeft,
                    end: Alignment.topRight,
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    if (banner['title'] != null)
                      Text(
                        banner['title'],
                        style: GoogleFonts.plusJakartaSans(
                          color: Colors.white,
                          fontSize: 20,
                          fontWeight: FontWeight.w800,
                        ),
                      ),
                    const SizedBox(height: 8),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(30),
                      ),
                      child: Text(
                        'Explore Now',
                        style: GoogleFonts.plusJakartaSans(
                          color: Colors.black,
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildLeftRightBanners(List<dynamic> banners) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        children: [
          Expanded(
            child: _buildMiniBanner(banners[0]),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: _buildMiniBanner(banners[1]),
          ),
        ],
      ),
    );
  }

  Widget _buildMiniBanner(dynamic banner) {
    return Container(
      height: 180,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        image: DecorationImage(
          image: CachedNetworkImageProvider(banner['image'] ?? 'https://via.placeholder.com/400x600'),
          fit: BoxFit.cover,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 10,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Stack(
        children: [
          Container(
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(24),
              gradient: LinearGradient(
                colors: [Colors.black.withValues(alpha: 0.5), Colors.transparent],
                begin: Alignment.bottomCenter,
                end: Alignment.topCenter,
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.end,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  banner['title'] ?? 'Special Offer',
                  style: GoogleFonts.plusJakartaSans(
                    color: Colors.white,
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }



  Widget _buildReferralCard(Map<String, dynamic> referral) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(32),
          gradient: const LinearGradient(
            colors: [Color(0xFF6366F1), Color(0xFF8B5CF6)],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF6366F1).withValues(alpha: 0.4),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
        ),
        child: Row(
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.2),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      'EARN CASH',
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white,
                        fontSize: 10,
                        fontWeight: FontWeight.w800,
                        letterSpacing: 1,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    referral['title'] ?? 'Refer a friend & get \$200',
                    style: GoogleFonts.plusJakartaSans(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.w800,
                      height: 1.2,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    referral['description'] ?? 'Invite friends and earn rewards when they book.',
                    style: GoogleFonts.plusJakartaSans(
                      color: Colors.white.withValues(alpha: 0.9),
                      fontSize: 13,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                    ),
                    child: Text(
                      'Invite Now',
                      style: GoogleFonts.plusJakartaSans(
                        color: const Color(0xFF6366F1),
                        fontWeight: FontWeight.w700,
                        fontSize: 13,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(width: 16),
            // Decorative Icon/Image
            Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                color: Colors.white.withValues(alpha: 0.1),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.card_giftcard_rounded, color: Colors.white, size: 40),
            ),
          ],
        ),
      ),
    );
  }
}

class _ShimmerBox extends StatefulWidget {
  final double width;
  final double height;
  final BorderRadius? borderRadius;

  const _ShimmerBox({
    required this.width,
    required this.height,
    this.borderRadius,
  });

  @override
  State<_ShimmerBox> createState() => _ShimmerBoxState();
}

class _ShimmerBoxState extends State<_ShimmerBox> with SingleTickerProviderStateMixin {
  late final AnimationController _controller;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: const Duration(milliseconds: 1500))
      ..repeat();
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final baseColor = Colors.grey[200]!;
    final highlight = Colors.white.withValues(alpha: 0.7);

    return LayoutBuilder(
      builder: (context, constraints) {
        final w = widget.width == double.infinity ? constraints.maxWidth : widget.width;
        final h = widget.height == double.infinity ? constraints.maxHeight : widget.height;
        final overlayWidth = w * 0.45;

        return ClipRRect(
          borderRadius: widget.borderRadius ?? BorderRadius.circular(12),
          child: Stack(
            children: [
              Container(width: w, height: h, color: baseColor),
              AnimatedBuilder(
                animation: _controller,
                builder: (context, child) {
                  final dx = (-overlayWidth) + (w + overlayWidth) * _controller.value;
                  return Transform.translate(
                    offset: Offset(dx, 0),
                    child: Container(
                      width: overlayWidth,
                      height: h,
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          colors: [baseColor, highlight, baseColor],
                          stops: const [0.1, 0.5, 0.9],
                          begin: Alignment.centerLeft,
                          end: Alignment.centerRight,
                        ),
                      ),
                    ),
                  );
                },
              ),
            ],
          ),
        );
      },
    );
  }
}
