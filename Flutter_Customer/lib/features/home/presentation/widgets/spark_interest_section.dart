import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_customer/features/auth/presentation/providers/auth_provider.dart';
import '../providers/home_provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';

class SparkInterestSection extends StatelessWidget {
  const SparkInterestSection({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Consumer<HomeProvider>(
      builder: (context, provider, child) {
        final interests = provider.interests;

        // Even if empty, we might want to show something or just hide
        if (interests.isEmpty) {
          return const SizedBox.shrink();
        }

        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 8.0),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    "What sparks your interest?",
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 20,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF1A1D1E),
                    ),
                  ),
                  TextButton(
                    onPressed: () => _showAllInterests(context, provider),
                    style: TextButton.styleFrom(
                      padding: EdgeInsets.zero,
                      minimumSize: Size.zero,
                      tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                    ),
                    child: Text(
                      "See All",
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF1A1D1E),
                        decoration: TextDecoration.underline,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),
            SizedBox(
              height: 300, // 3 rows * ~85px + spacing
              child: GridView.builder(
                padding: const EdgeInsets.symmetric(horizontal: 24),
                scrollDirection: Axis.horizontal,
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 3,
                  mainAxisSpacing: 12,
                  crossAxisSpacing: 12,
                  childAspectRatio: 1 / 3.5, // Height / Width (Inverted for horizontal)
                ),
                itemCount: interests.length,
                itemBuilder: (context, index) {
                  final item = interests[index];
                  return _buildInterestItem(context, provider, item, index);
                },
              ),
            ),
          ],
        );
      },
    );
  }

  Widget _buildLoadingSkeleton(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 8.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                width: 200,
                height: 24,
                decoration: BoxDecoration(
                  color: Colors.grey[200],
                  borderRadius: BorderRadius.circular(4),
                ),
              ).animate(onPlay: (controller) => controller.repeat()).shimmer(duration: 1200.ms, color: Colors.white.withOpacity(0.5)),
              Container(
                width: 60,
                height: 16,
                decoration: BoxDecoration(
                  color: Colors.grey[200],
                  borderRadius: BorderRadius.circular(4),
                ),
              ).animate(onPlay: (controller) => controller.repeat()).shimmer(duration: 1200.ms, color: Colors.white.withOpacity(0.5)),
            ],
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 300,
          child: GridView.builder(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            scrollDirection: Axis.horizontal,
            physics: const NeverScrollableScrollPhysics(),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 3,
              mainAxisSpacing: 12,
              crossAxisSpacing: 12,
              childAspectRatio: 1 / 3.5,
            ),
            itemCount: 9, // Dummy count
            itemBuilder: (context, index) {
              return Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: Colors.grey.withOpacity(0.1)),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 40,
                      height: 40,
                      decoration: BoxDecoration(
                        color: Colors.grey[200],
                        borderRadius: BorderRadius.circular(20),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Container(
                            width: double.infinity,
                            height: 12,
                            decoration: BoxDecoration(
                              color: Colors.grey[200],
                              borderRadius: BorderRadius.circular(4),
                            ),
                          ),
                          const SizedBox(height: 6),
                          Container(
                            width: 60,
                            height: 12,
                            decoration: BoxDecoration(
                              color: Colors.grey[200],
                              borderRadius: BorderRadius.circular(4),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 8),
                    Container(
                      width: 50,
                      height: 24,
                      decoration: BoxDecoration(
                        color: Colors.grey[200],
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                  ],
                ),
              ).animate(onPlay: (controller) => controller.repeat()).shimmer(duration: 1200.ms, delay: (100 * index).ms, color: Colors.white.withOpacity(0.5));
            },
          ),
        ),
      ],
    );
  }

  Widget _buildInterestItem(BuildContext context, HomeProvider provider, dynamic item, int index) {
    final isSelected = item['is_selected'] ?? false;

    return GestureDetector(
      onTap: () async {
        final authProvider = context.read<AuthProvider>();
        if (!authProvider.isAuthenticated) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(
                'Please login to save your interests',
                style: GoogleFonts.plusJakartaSans(color: Colors.white),
              ),
              backgroundColor: const Color(0xFF1A1D1E),
              behavior: SnackBarBehavior.floating,
              action: SnackBarAction(
                label: 'Login',
                textColor: Colors.white,
                onPressed: () {
                  // Navigate to login (assuming GoRouter is available in context)
                  // or just let the user navigate manually via profile
                },
              ),
            ),
          );
          return;
        }
        
        try {
          await provider.toggleInterest(item['id']);
        } catch (e) {
          if (e.toString().contains('Unauthorized') && context.mounted) {
            // Logout the user
            await authProvider.logout();
            
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(
                  'Session expired. Please login again.',
                  style: GoogleFonts.plusJakartaSans(color: Colors.white),
                ),
                backgroundColor: Colors.redAccent,
                behavior: SnackBarBehavior.floating,
              ),
            );
          }
        }
      },
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        decoration: BoxDecoration(
          color: isSelected ? const Color(0xFFF8FAFC) : Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(
            color: isSelected 
                ? const Color(0xFF1A1D1E) 
                : Colors.grey.withOpacity(0.2),
            width: isSelected ? 2 : 1,
          ),
          boxShadow: [
            BoxShadow(
              color: isSelected 
                  ? Colors.black.withOpacity(0.05)
                  : Colors.black.withOpacity(0.03),
              blurRadius: isSelected ? 8 : 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Row(
          children: [
            // Icon
            SizedBox(
              width: 40,
              height: 40,
              child: item['icon'] != null
                  ? (item['icon'].toString().startsWith('http')
                      ? CachedNetworkImage(
                          imageUrl: item['icon'],
                          placeholder: (context, url) => const SizedBox(),
                          errorWidget: (context, url, error) => const Icon(Icons.star_outline, color: Color(0xFF64748B)),
                        )
                      : Image.asset(item['icon'])) 
                  : const Icon(Icons.star_outline, color: Color(0xFF64748B)),
            ),
            const SizedBox(width: 12),
            
            // Text
            Expanded(
              child: Text(
                item['name'] ?? '',
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 14,
                  fontWeight: isSelected ? FontWeight.w700 : FontWeight.w600,
                  color: const Color(0xFF1A1D1E),
                  height: 1.2,
                ),
              ),
            ),
            
            const SizedBox(width: 8),
            
            // Signed Mark / Add Button
            AnimatedContainer(
              duration: const Duration(milliseconds: 200),
              padding: EdgeInsets.symmetric(
                horizontal: isSelected ? 8 : 12,
                vertical: 6,
              ),
              decoration: BoxDecoration(
                color: isSelected ? const Color(0xFF1A1D1E) : Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(
                  color: const Color(0xFF1A1D1E),
                  width: 1.5,
                ),
              ),
              child: isSelected
                  ? Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(
                          Icons.check_circle,
                          size: 16,
                          color: Colors.white,
                        ),
                        const SizedBox(width: 4),
                        Text(
                          "Added",
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                          ),
                        ),
                      ],
                    )
                  : Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(
                          Icons.add,
                          size: 16,
                          color: Color(0xFF1A1D1E),
                        ),
                        const SizedBox(width: 4),
                        Text(
                          "Add",
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                            color: const Color(0xFF1A1D1E),
                          ),
                        ),
                      ],
                    ),
            ),
          ],
        ),
      ),
    ).animate().fadeIn(duration: 400.ms);
  }

  void _showAllInterests(BuildContext context, HomeProvider provider) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => DraggableScrollableSheet(
        initialChildSize: 0.85,
        minChildSize: 0.5,
        maxChildSize: 0.95,
        builder: (context, scrollController) => Container(
          decoration: const BoxDecoration(
            color: Color(0xFFF8F9FA),
            borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
          ),
          child: Column(
            children: [
              const SizedBox(height: 12),
              Container(
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                  color: Colors.grey.withOpacity(0.3),
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(24),
                child: Row(
                  children: [
                    Text(
                      "All Interests",
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 24,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF1A1D1E),
                      ),
                    ),
                    const Spacer(),
                    IconButton(
                      icon: const Icon(Icons.close),
                      onPressed: () => Navigator.pop(context),
                    ),
                  ],
                ),
              ),
              Expanded(
                child: Consumer<HomeProvider>(
                  builder: (context, provider, _) {
                    final interests = provider.interests;
                    return ListView.separated(
                      controller: scrollController,
                      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 8),
                      itemCount: interests.length,
                      separatorBuilder: (context, index) => const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        return _buildInterestItem(context, provider, interests[index], index);
                      },
                    );
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
