import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:shimmer/shimmer.dart';
import 'package:liquid_pull_to_refresh/liquid_pull_to_refresh.dart';
import '../../../../../core/utils/image_helper.dart';
import '../../data/models/gig_model.dart';
import '../providers/gig_provider.dart';
import 'gig_details_page.dart';

class GigsPage extends ConsumerWidget {
  const GigsPage({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final gigsAsyncValue = ref.watch(providerGigsProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC), // Slate 50
      appBar: AppBar(
        title: Text(
          'My Gigs',
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.w700,
            color: const Color(0xFF0F172A), // Slate 900
            fontSize: 20,
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: false,
        actions: [
          Container(
            margin: const EdgeInsets.only(right: 16),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [
                  Color(0xFF6366F1),
                  Color(0xFF8B5CF6),
                ], // Indigo to Violet
              ),
              borderRadius: BorderRadius.circular(12),
              boxShadow: [
                BoxShadow(
                  color: const Color(0xFF6366F1).withOpacity(0.3),
                  blurRadius: 8,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: IconButton(
              onPressed: () => context.push('/create-gig'),
              icon: const Icon(Icons.add_rounded, color: Colors.white),
              tooltip: 'Create New Gig',
            ),
          ),
        ],
      ),
      body: gigsAsyncValue.when(
        data: (gigs) {
          if (gigs.isEmpty) {
            return _buildEmptyState(context);
          }
          return LiquidPullToRefresh(
            onRefresh: () => ref.refresh(providerGigsProvider.future),
            color: const Color(0xFF6366F1),
            backgroundColor: Colors.white,
            height: 100,
            animSpeedFactor: 2.0,
            showChildOpacityTransition: false,
            child: CustomScrollView(
              physics: const BouncingScrollPhysics(),
              slivers: [
                SliverPadding(
                  padding: const EdgeInsets.fromLTRB(20, 20, 20, 100),
                  sliver: SliverList(
                    delegate: SliverChildBuilderDelegate((context, index) {
                      final gig = gigs[index];
                      return _buildGigCard(context, ref, gig, index);
                    }, childCount: gigs.length),
                  ),
                ),
              ],
            ),
          );
        },
        loading: () => _buildShimmerLoading(),
        error: (error, stack) => Center(
          child: Padding(
            padding: const EdgeInsets.all(24.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(
                  Icons.error_outline_rounded,
                  size: 48,
                  color: Colors.red[400],
                ),
                const SizedBox(height: 16),
                Text(
                  'Something went wrong',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF1E293B),
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  error.toString().replaceAll('Exception: ', ''),
                  textAlign: TextAlign.center,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 14,
                    color: const Color(0xFF64748B),
                  ),
                ),
                const SizedBox(height: 16),
                TextButton(
                  onPressed: () => ref.refresh(providerGigsProvider),
                  child: const Text('Try Again'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildShimmerLoading() {
    return ListView.builder(
      padding: const EdgeInsets.all(20),
      itemCount: 5,
      itemBuilder: (context, index) {
        return Container(
          margin: const EdgeInsets.only(bottom: 16),
          height: 124, // Approx height of card
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
          ),
          child: Shimmer.fromColors(
            baseColor: Colors.grey[300]!,
            highlightColor: Colors.grey[100]!,
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Row(
                children: [
                  Container(
                    width: 100,
                    height: 100,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Container(
                              width: 60,
                              height: 20,
                              color: Colors.white,
                            ),
                            Container(
                              width: 24,
                              height: 24,
                              color: Colors.white,
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Container(
                          width: double.infinity,
                          height: 16,
                          color: Colors.white,
                        ),
                        const SizedBox(height: 4),
                        Container(width: 150, height: 16, color: Colors.white),
                        const SizedBox(height: 12),
                        Container(width: 80, height: 20, color: Colors.white),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }

  Widget _buildEmptyState(BuildContext context) {
    return Center(
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
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 20,
                  offset: const Offset(0, 10),
                ),
              ],
            ),
            child: Icon(
              Icons.rocket_launch_rounded,
              size: 64,
              color: const Color(0xFF6366F1),
            ),
          ).animate().scale(duration: 500.ms, curve: Curves.easeOutBack),
          const SizedBox(height: 24),
          Text(
            'No gigs created yet',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF1E293B),
            ),
          ).animate().fadeIn(delay: 200.ms).slideY(begin: 0.2),
          const SizedBox(height: 8),
          Text(
            'Start your journey by creating your first gig.',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 16,
              color: const Color(0xFF64748B),
            ),
          ).animate().fadeIn(delay: 300.ms).slideY(begin: 0.2),
          const SizedBox(height: 32),
          Container(
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(16),
              gradient: const LinearGradient(
                colors: [Color(0xFF6366F1), Color(0xFF8B5CF6)],
              ),
              boxShadow: [
                BoxShadow(
                  color: const Color(0xFF6366F1).withOpacity(0.3),
                  blurRadius: 12,
                  offset: const Offset(0, 6),
                ),
              ],
            ),
            child: ElevatedButton.icon(
              onPressed: () => context.push('/create-gig'),
              icon: const Icon(Icons.add_rounded, color: Colors.white),
              label: Text(
                'Create First Gig',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                  color: Colors.white,
                ),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.transparent,
                shadowColor: Colors.transparent,
                padding: const EdgeInsets.symmetric(
                  horizontal: 32,
                  vertical: 16,
                ),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                ),
              ),
            ),
          ).animate().fadeIn(delay: 400.ms).slideY(begin: 0.2),
        ],
      ),
    );
  }

  Widget _buildGigCard(BuildContext context, WidgetRef widgetRef, GigModel gig, int index) {
    final basicPrice = gig.packages.isNotEmpty
        ? gig.packages
              .firstWhere(
                (p) => p.tier == 'Basic',
                orElse: () => gig.packages.first,
              )
              .price
        : 0.0;

    return Container(
          margin: const EdgeInsets.only(bottom: 16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF64748B).withOpacity(0.08),
                blurRadius: 16,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Material(
            color: Colors.transparent,
            borderRadius: BorderRadius.circular(20),
            child: InkWell(
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => GigDetailsPage(gig: gig)),
                );
              },
              borderRadius: BorderRadius.circular(20),
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Image
                    Hero(
                      tag: 'gig_image_${gig.id}',
                      child: Container(
                        width: 100,
                        height: 100,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(16),
                          image: gig.images.isNotEmpty
                              ? DecorationImage(
                                  image: NetworkImage(resolveImageUrl(gig.images.first)),
                                  fit: BoxFit.cover,
                                )
                              : (gig.thumbnail != null
                                  ? DecorationImage(
                                      image: NetworkImage(resolveImageUrl(gig.thumbnail!)),
                                      fit: BoxFit.cover,
                                    )
                                  : null),
                          color: const Color(0xFFF1F5F9),
                        ),
                        child: gig.images.isEmpty && gig.thumbnail == null
                            ? Icon(
                                Icons.image_outlined,
                                color: Colors.grey[400],
                                size: 32,
                              )
                            : null,
                      ),
                    ),
                    const SizedBox(width: 16),
                    // Content
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              _buildStatusChip(gig.status),
                              _buildMenuButton(context, widgetRef, gig),
                            ],
                          ),
                          const SizedBox(height: 8),
                          Text(
                            gig.title,
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.w700,
                              fontSize: 16,
                              height: 1.3,
                              color: const Color(0xFF1E293B),
                            ),
                          ),
                          const SizedBox(height: 12),
                          Row(
                            children: [
                              Text(
                                'Starting at',
                                style: GoogleFonts.plusJakartaSans(
                                  fontSize: 12,
                                  color: const Color(0xFF64748B),
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                              const SizedBox(width: 4),
                              Text(
                                '\$${basicPrice.toStringAsFixed(0)}',
                                style: GoogleFonts.plusJakartaSans(
                                  fontSize: 16,
                                  color: const Color(0xFF6366F1), // Primary
                                  fontWeight: FontWeight.w800,
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
            ),
          ),
        )
        .animate()
        .fadeIn(delay: (index * 50).ms)
        .slideX(begin: 0.1, curve: Curves.easeOut);
  }

  Widget _buildStatusChip(String status) {
    Color color;
    Color textColor;
    String text;

    switch (status.toLowerCase()) {
      case 'published':
      case 'live':
      case 'active':
        color = const Color(0xFFDCFCE7); // Green 100
        textColor = const Color(0xFF15803D); // Green 700
        text = 'Published';
        break;
      case 'pending':
        color = const Color(0xFFFEF3C7); // Amber 100
        textColor = const Color(0xFFB45309); // Amber 700
        text = 'Pending';
        break;
      case 'rejected':
        color = const Color(0xFFFEE2E2); // Red 100
        textColor = const Color(0xFFB91C1C); // Red 700
        text = 'Rejected';
        break;
      case 'suspended':
        color = const Color(0xFFF3F4F6); // Grey 100
        textColor = const Color(0xFF374151); // Grey 700
        text = 'Suspended';
        break;
      default:
        color = const Color(0xFFE0E7FF); // Indigo 100
        textColor = const Color(0xFF4338CA); // Indigo 700
        text = status.isEmpty
            ? 'Unknown'
            : status[0].toUpperCase() + status.substring(1);
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(
        text,
        style: GoogleFonts.plusJakartaSans(
          fontSize: 10,
          fontWeight: FontWeight.w700,
          color: textColor,
          letterSpacing: 0.5,
        ),
      ),
    );
  }

  Widget _buildMenuButton(BuildContext context, WidgetRef ref, GigModel gig) {
    return SizedBox(
      width: 32,
      height: 32,
      child: PopupMenuButton<String>(
        onSelected: (value) {
          switch (value) {
            case 'edit':
              context.push('/create-gig', extra: gig);
              break;
            case 'delete':
              _showDeleteConfirmation(context, ref, gig);
              break;
            case 'view':
              Navigator.push(
                context,
                MaterialPageRoute(builder: (_) => GigDetailsPage(gig: gig)),
              );
              break;
          }
        },
        itemBuilder: (context) => [
          PopupMenuItem(
            value: 'view',
            child: Row(
              children: [
                Icon(Icons.visibility_rounded,
                    size: 20, color: Colors.grey[700]),
                const SizedBox(width: 8),
                Text('View Details',
                    style: GoogleFonts.plusJakartaSans(fontSize: 14)),
              ],
            ),
          ),
          PopupMenuItem(
            value: 'edit',
            child: Row(
              children: [
                Icon(Icons.edit_rounded, size: 20, color: Colors.blue[700]),
                const SizedBox(width: 8),
                Text('Edit Gig',
                    style: GoogleFonts.plusJakartaSans(fontSize: 14)),
              ],
            ),
          ),
          PopupMenuItem(
            value: 'delete',
            child: Row(
              children: [
                Icon(Icons.delete_rounded, size: 20, color: Colors.red[700]),
                const SizedBox(width: 8),
                Text('Delete',
                    style: GoogleFonts.plusJakartaSans(
                        fontSize: 14, color: Colors.red[700])),
              ],
            ),
          ),
        ],
        icon: const Icon(Icons.more_horiz_rounded,
            size: 20, color: Color(0xFF94A3B8)),
        padding: EdgeInsets.zero,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }

  void _showDeleteConfirmation(
      BuildContext context, WidgetRef ref, GigModel gig) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Delete Gig?',
            style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold)),
        content: Text(
          'Are you sure you want to delete "${gig.title}"? This action cannot be undone.',
          style: GoogleFonts.plusJakartaSans(),
        ),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text('Cancel',
                style: GoogleFonts.plusJakartaSans(color: Colors.grey[600])),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              if (gig.id != null) {
                ref.read(gigControllerProvider.notifier).deleteGig(gig.id!);
              }
            },
            child: Text('Delete',
                style: GoogleFonts.plusJakartaSans(
                    color: Colors.red, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }
}
