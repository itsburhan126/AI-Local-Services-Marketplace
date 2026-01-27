import 'dart:ui';
import 'package:shimmer/shimmer.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:share_plus/share_plus.dart';
import 'package:provider/provider.dart' as provider;
import 'package:flutter_animate/flutter_animate.dart';
import 'package:timeago/timeago.dart' as timeago;
import '../../data/models/gig_model.dart';
import '../../data/models/gig_analytics_model.dart';
import '../providers/gig_provider.dart';
import '../../../../auth/presentation/providers/auth_provider.dart';
import '../../../../auth/data/models/user_model.dart';
import '../../../../../core/utils/image_helper.dart';
import 'create_gig_page.dart';
import '../widgets/all_reviews_bottom_sheet.dart';

class GigDetailsPage extends ConsumerStatefulWidget {
  final GigModel gig;

  const GigDetailsPage({super.key, required this.gig});

  @override
  ConsumerState<GigDetailsPage> createState() => _GigDetailsPageState();
}

class _GigDetailsPageState extends ConsumerState<GigDetailsPage> {
  final ScrollController _scrollController = ScrollController();
  final GlobalKey _analyticsKey = GlobalKey();

  @override
  void initState() {
    super.initState();
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'published':
      case 'live':
      case 'active':
        return const Color(0xFF10B981); // Emerald 500
      case 'pending':
        return const Color(0xFFF59E0B); // Amber 500
      case 'rejected':
        return const Color(0xFFEF4444); // Red 500
      case 'suspended':
        return const Color(0xFF6B7280); // Gray 500
      default:
        return const Color(0xFF6366F1); // Indigo 500
    }
  }

  void _showMoreOptions(BuildContext context, GigModel gig) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled:
      true, // Allow sheet to take needed height up to limits
      builder: (context) => Container(
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
        ),
        child: SingleChildScrollView(
          // Fix overflow by making content scrollable
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const SizedBox(height: 8),
              Container(
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                  color: Colors.grey[300],
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
              const SizedBox(height: 24),
              Text(
                'Gig Options',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF1E293B),
                ),
              ),
              const SizedBox(height: 24),
              _buildOptionTile(
                icon: Icons.edit_outlined,
                title: 'Edit Gig',
                subtitle: 'Update details, pricing, and media',
                color: const Color(0xFF6366F1),
                onTap: () {
                  Navigator.pop(context);
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => CreateGigPage(gig: gig),
                    ),
                  );
                },
              ),
              _buildOptionTile(
                icon: Icons.analytics_outlined,
                title: 'View Analytics',
                subtitle: 'Check performance and engagement',
                color: const Color(0xFF8B5CF6),
                onTap: () {
                  Navigator.pop(context);
                  Future.delayed(const Duration(milliseconds: 300), () {
                     Scrollable.ensureVisible(
                       _analyticsKey.currentContext!, 
                       duration: const Duration(milliseconds: 500), 
                       curve: Curves.easeInOut
                     );
                  });
                },
              ),
              _buildOptionTile(
                icon: gig.isActive
                    ? Icons.pause_circle_outline
                    : Icons.play_circle_outline,
                title: gig.isActive ? 'Pause Gig' : 'Activate Gig',
                subtitle: gig.isActive
                    ? 'Temporarily hide from search'
                    : 'Make visible to buyers',
                color: const Color(0xFFF59E0B),
                onTap: () {
                  Navigator.pop(context);
                  ref.read(gigControllerProvider.notifier).updateGigStatus(
                    gig.id!,
                    gig.isActive ? 'paused' : 'active',
                  );
                },
              ),
              _buildOptionTile(
                icon: Icons.share_outlined,
                title: 'Share Gig',
                subtitle: 'Promote on social media',
                color: const Color(0xFF10B981),
                onTap: () {
                  Navigator.pop(context);
                  Share.share('Check out this gig: ${gig.title}');
                },
              ),
              const Divider(height: 32),
              _buildOptionTile(
                icon: Icons.delete_outline_rounded,
                title: 'Delete Gig',
                subtitle: 'Permanently remove this gig',
                color: const Color(0xFFEF4444),
                isDestructive: true,
                onTap: () {
                  Navigator.pop(context);
                  _showDeleteConfirmation(context, gig);
                },
              ),
              const SizedBox(height: 32),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildOptionTile({
    required IconData icon,
    required String title,
    required String subtitle,
    required Color color,
    required VoidCallback onTap,
    bool isDestructive = false,
  }) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: color.withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(icon, color: color, size: 24),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w600,
                      color: isDestructive
                          ? const Color(0xFFEF4444)
                          : const Color(0xFF1E293B),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    subtitle,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 13,
                      color: const Color(0xFF64748B),
                    ),
                  ),
                ],
              ),
            ),
            Icon(Icons.chevron_right_rounded, color: Colors.grey[400]),
          ],
        ),
      ),
    );
  }

  void _showDeleteConfirmation(BuildContext context, GigModel gig) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text(
          'Delete Gig?',
          style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold),
        ),
        content: Text(
          'Are you sure you want to delete this gig? This action cannot be undone.',
          style: GoogleFonts.plusJakartaSans(),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text(
              'Cancel',
              style: GoogleFonts.plusJakartaSans(
                color: const Color(0xFF64748B),
              ),
            ),
          ),
          TextButton(
            onPressed: () {
              // Perform delete
              ref.read(gigControllerProvider.notifier).deleteGig(gig.id!);
              Navigator.pop(context); // Close dialog
              Navigator.pop(context); // Go back to list
            },
            child: Text(
              'Delete',
              style: GoogleFonts.plusJakartaSans(
                color: const Color(0xFFEF4444),
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    ref.listen<AsyncValue<void>>(gigControllerProvider, (previous, next) {
      next.whenOrNull(
        error: (error, stack) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Error: $error')),
          );
        },
        data: (_) {
           // Success handling if needed
        }
      );
    });

    final gigId = widget.gig.id ?? 0;
    final gigAsync = ref.watch(gigDetailsProvider(gigId));
    final analyticsAsync = ref.watch(gigAnalyticsProvider(gigId));
    
    final gig = gigAsync.value ?? widget.gig;

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: RefreshIndicator(
        onRefresh: () async {
          // Use ref.refresh to force reload
          ref.invalidate(gigDetailsProvider(gigId));
          ref.invalidate(gigAnalyticsProvider(gigId));
          // Wait a bit for UI to update or wait for futures if we had access to them easily
          // Ideally we should wait for the new values.
          await Future.delayed(const Duration(seconds: 1));
        },
        child: CustomScrollView(
          controller: _scrollController,
          physics: const BouncingScrollPhysics(),
          slivers: [
            _buildAppBar(context, gig),
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildHeaderSection(gig, analyticsAsync),
                    const SizedBox(height: 24),
                    _buildActionButtons(gig),
                    const SizedBox(height: 32),
                    if (gig.status == 'rejected' &&
                        gig.adminNote != null)
                      _buildRejectionNote(gig),
                    Container(
                      key: _analyticsKey,
                      child: _buildPerformanceGrid(analyticsAsync),
                    ),
                    const SizedBox(height: 24),
                    _buildDaySellAnalytics(analyticsAsync),
                    const SizedBox(height: 24),
                    _buildViewAnalytics(analyticsAsync),
                    const SizedBox(height: 24),
                    _buildEarnAnalytics(analyticsAsync),
                    const SizedBox(height: 32),
                    _buildRecentOrdersSection(analyticsAsync),
                    const SizedBox(height: 32),
                    _buildPackagesSection(gig),
                    const SizedBox(height: 32),
                    _buildSellerProfilePreviewSection(context, gig),
                    const SizedBox(height: 32),
                    _buildReviewsSection(analyticsAsync),
                    const SizedBox(height: 32),
                    _buildFAQSection(gig),
                    const SizedBox(height: 100),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAppBar(BuildContext context, GigModel gig) {
    return SliverAppBar(
      expandedHeight: 300,
      pinned: true,
      backgroundColor: Colors.transparent,
      flexibleSpace: FlexibleSpaceBar(
        background: Stack(
          fit: StackFit.expand,
          children: [
            (gig.images.isNotEmpty || gig.thumbnail != null)
                ? Image.network(
                    // Ensure resolveImageUrl is used
                    resolveImageUrl(gig.images.isNotEmpty 
                        ? gig.images.first 
                        : gig.thumbnail!), 
                    fit: BoxFit.cover,
                  )
                : Container(color: const Color(0xFF1E293B)),
            Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [Colors.transparent, Colors.black.withOpacity(0.8)],
                ),
              ),
            ),
            Positioned(
              bottom: 20,
              left: 20,
              right: 20,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 6,
                    ),
                    decoration: BoxDecoration(
                      color: _getStatusColor(
                        gig.status,
                      ).withOpacity(0.9),
                      borderRadius: BorderRadius.circular(30),
                      boxShadow: [
                        BoxShadow(
                          color: _getStatusColor(
                            gig.status,
                          ).withOpacity(0.4),
                          blurRadius: 8,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: Text(
                      gig.status.toUpperCase(),
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white,
                        fontSize: 12,
                        fontWeight: FontWeight.w700,
                        letterSpacing: 1,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    gig.title,
                    style: GoogleFonts.plusJakartaSans(
                      color: Colors.white,
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      shadows: [
                        const Shadow(
                          color: Colors.black26,
                          offset: Offset(0, 2),
                          blurRadius: 4,
                        ),
                      ],
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
      leading: Container(
        margin: const EdgeInsets.all(8),
        child: ClipOval(
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
            child: Container(
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.2),
                shape: BoxShape.circle,
              ),
              child: IconButton(
                icon: const Icon(
                  Icons.arrow_back_ios_new,
                  color: Colors.white,
                  size: 20,
                ),
                onPressed: () => Navigator.pop(context),
              ),
            ),
          ),
        ),
      ),
      actions: [
        Container(
          margin: const EdgeInsets.all(8),
          child: ClipOval(
            child: BackdropFilter(
              filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
              child: Container(
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  shape: BoxShape.circle,
                ),
                child: IconButton(
                  icon: const Icon(Icons.more_horiz, color: Colors.white),
                  onPressed: () {
                    _showMoreOptions(context, gig);
                  },
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildHeaderSection(GigModel gig, AsyncValue<GigAnalyticsModel> analyticsAsync) {
    final analytics = analyticsAsync.asData?.value;
    
    // Use analytics data if available, otherwise fallback to widget.gig
    final viewCount = analytics?.viewCount ?? gig.viewCount;
    final bookingsCount = analytics?.bookingsCount ?? gig.bookingsCount;

    return Row(
      children: [
        _buildStatBadge(
            Icons.visibility_outlined, '$viewCount Views', Colors.blue),
        const SizedBox(width: 12),
        _buildStatBadge(
          Icons.shopping_bag_outlined,
          '$bookingsCount Orders',
          const Color(0xFF6366F1),
        ),
        const Spacer(),
        Column(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Text(
              'Starting at',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 12,
                color: const Color(0xFF64748B),
                fontWeight: FontWeight.w600,
              ),
            ),
            Text(
              '\$${gig.packages.isNotEmpty ? gig.packages.first.price.toStringAsFixed(0) : "0"}',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: const Color(0xFF1F2937),
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildActionButtons(GigModel gig) {
    return Row(
      children: [
        Expanded(
          child: _buildActionButton(
            icon: Icons.share_rounded,
            label: 'Share',
            color: const Color(0xFF3B82F6),
            onTap: () {
              Share.share('Check out this gig: ${gig.title}');
            },
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: _buildActionButton(
            icon: Icons.link_rounded,
            label: 'Copy Link',
            color: const Color(0xFF8B5CF6),
            onTap: () {
              Clipboard.setData(
                ClipboardData(
                  text: 'https://market.place/gig/${gig.id}',
                ),
              );
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Link copied to clipboard')),
              );
            },
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: _buildActionButton(
            icon: Icons.edit_rounded,
            label: 'Edit',
            color: const Color(0xFF10B981),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => CreateGigPage(gig: gig),
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildActionButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onTap,
  }) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: color.withOpacity(0.1),
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: color.withOpacity(0.2)),
          ),
          child: Column(
            children: [
              Icon(icon, color: color, size: 24),
              const SizedBox(height: 4),
              Text(
                label,
                style: GoogleFonts.plusJakartaSans(
                  color: color,
                  fontWeight: FontWeight.w600,
                  fontSize: 12,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatBadge(IconData icon, String text, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        children: [
          Icon(icon, size: 16, color: color),
          const SizedBox(width: 6),
          Text(
            text,
            style: GoogleFonts.plusJakartaSans(
              color: color,
              fontWeight: FontWeight.w700,
              fontSize: 13,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRejectionNote(GigModel gig) {
    return Container(
      margin: const EdgeInsets.only(bottom: 24),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFFFEF2F2),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFFECACA)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(
                Icons.info_outline_rounded,
                color: Color(0xFFDC2626),
                size: 20,
              ),
              const SizedBox(width: 8),
              Text(
                'Rejection Reason',
                style: GoogleFonts.plusJakartaSans(
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF991B1B),
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            gig.adminNote!,
            style: GoogleFonts.plusJakartaSans(
              color: const Color(0xFF7F1D1D),
              fontSize: 14,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPerformanceGrid(AsyncValue<GigAnalyticsModel> analyticsAsync) {
    if (analyticsAsync.isLoading) {
      return Shimmer.fromColors(
        baseColor: Colors.grey[300]!,
        highlightColor: Colors.grey[100]!,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(width: 150, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)))),
                const SizedBox(width: 16),
                Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)))),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)))),
                const SizedBox(width: 16),
                Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)))),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)))),
                const SizedBox(width: 16),
                Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)))),
              ],
            ),
          ],
        ),
      );
    }
    
    if (analyticsAsync.hasError) {
      return Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.red.withOpacity(0.1),
          borderRadius: BorderRadius.circular(16),
        ),
        child: Row(
          children: [
            const Icon(Icons.error_outline, color: Colors.red),
            const SizedBox(width: 12),
            Expanded(child: Text('Failed to load analytics: ${analyticsAsync.error}', style: const TextStyle(color: Colors.red))),
          ],
        ),
      );
    }

    final analytics = analyticsAsync.asData?.value;
    final totalEarnings = analytics?.totalEarnings ?? 0.0;
    final pendingAmount = analytics?.pendingAmount ?? 0.0; // Expected Earnings (Active/Pending Orders)
    final clearanceAmount = analytics?.clearanceAmount ?? 0.0; // Completed but Pending Clearance
    final clearedAmount = analytics?.clearedAmount ?? 0.0; // Completed and Cleared
    final activeOrders = analytics?.activeOrders ?? 0;
    final completedOrders = analytics?.completedOrders ?? 0;
    final pendingOrders = analytics?.pendingOrders ?? 0;
    final viewCount = analytics?.viewCount ?? 0;
    final averageRating = analytics?.averageRating ?? 0.0;
    final totalReviews = analytics?.totalReviews ?? 0;
    
    final earningsChange = analytics?.earningsChange ?? 0.0;
    
    final changeText = earningsChange >= 0 ? '+${earningsChange.toStringAsFixed(1)}%' : '${earningsChange.toStringAsFixed(1)}%';
    final changeColor = earningsChange >= 0 ? const Color(0xFF10B981) : const Color(0xFFEF4444);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Performance Overview',
          style: GoogleFonts.plusJakartaSans(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: const Color(0xFF1F2937),
          ),
        ),
        const SizedBox(height: 16),
        // Row 1: Financials
        Row(
          children: [
            Expanded(
              child: _buildInfoCard(
                'Total Sell',
                '\$${totalEarnings.toStringAsFixed(2)}',
                changeText,
                const Color(0xFF10B981),
                Icons.attach_money,
                changeColor: changeColor,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: _buildInfoCard(
                'Cleared Amount',
                '\$${clearedAmount.toStringAsFixed(2)}',
                'Available',
                const Color(0xFF3B82F6),
                Icons.account_balance_wallet,
                changeColor: const Color(0xFF3B82F6),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        // Row 2: Clearance & Active
        Row(
          children: [
            Expanded(
              child: _buildInfoCard(
                'Clearance Amount',
                '\$${clearanceAmount.toStringAsFixed(2)}',
                'Pending',
                const Color(0xFFF59E0B),
                Icons.pending_actions,
                changeColor: const Color(0xFFF59E0B),
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: _buildInfoCard(
                'Active Orders',
                '$activeOrders',
                'In Progress',
                const Color(0xFF8B5CF6),
                Icons.run_circle_outlined,
                changeColor: const Color(0xFF8B5CF6),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        // Row 3: Rating & Completed
        Row(
          children: [
             Expanded(
              child: _buildInfoCard(
                'Rating',
                '$averageRating ($totalReviews)',
                'Reviews',
                const Color(0xFFEAB308),
                Icons.star_rounded,
                changeColor: const Color(0xFFEAB308),
              ),
            ),
            const SizedBox(width: 16),
             Expanded(
              child: _buildInfoCard(
                'Completed',
                '$completedOrders',
                'Orders',
                const Color(0xFF059669),
                Icons.check_circle_outline,
                changeColor: const Color(0xFF059669),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        // Row 4: Pending & More
        Row(
          children: [
            Expanded(
              child: _buildInfoCard(
                'Pending',
                '$pendingOrders',
                'Requests',
                const Color(0xFF6366F1),
                Icons.hourglass_empty_rounded,
                changeColor: const Color(0xFF6366F1),
              ),
            ),
             const SizedBox(width: 16),
             Expanded(
               child: _buildInfoCard(
                 'Views',
                 '$viewCount',
                 'Total',
                 const Color(0xFF64748B),
                 Icons.visibility_outlined,
                 changeColor: const Color(0xFF64748B),
               ),
             ),
           ],
         ),
      ],
    );
  }

  Widget _buildInfoCard(
      String title,
      String value,
      String change,
      Color color,
      IconData icon, {
      Color? changeColor,
      }) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, color: color, size: 20),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: (changeColor ?? const Color(0xFF059669)).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  change,
                  style: GoogleFonts.plusJakartaSans(
                    color: changeColor ?? const Color(0xFF059669),
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Text(
            value,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF1F2937),
            ),
          ),
          Text(
            title,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              color: const Color(0xFF6B7280),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDaySellAnalytics(AsyncValue<GigAnalyticsModel> analyticsAsync) {
    if (analyticsAsync.isLoading) {
      return Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
        ),
        child: Shimmer.fromColors(
          baseColor: Colors.grey[300]!,
          highlightColor: Colors.grey[100]!,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Container(width: 150, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                  Container(width: 80, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20))),
                ],
              ),
              const SizedBox(height: 32),
              Container(height: 200, width: double.infinity, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16))),
            ],
          ),
        ),
      );
    }
    final salesChart = analyticsAsync.asData?.value.salesChart ?? [];
    List<FlSpot> spots = [];
    if (salesChart.isNotEmpty) {
      for (int i = 0; i < salesChart.length; i++) {
        spots.add(FlSpot(i.toDouble(), salesChart[i].value));
      }
    } else {
      for (int i = 0; i < 7; i++) {
        spots.add(FlSpot(i.toDouble(), 0));
      }
    }

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Day Sell Analytics',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF1F2937),
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 12,
                  vertical: 6,
                ),
                decoration: BoxDecoration(
                  color: const Color(0xFFF3F4F6),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  'Last 7 Days',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                    color: const Color(0xFF4B5563),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 32),
          SizedBox(
            height: 200,
            child: LineChart(
              LineChartData(
                gridData: const FlGridData(show: false),
                titlesData: const FlTitlesData(show: false),
                borderData: FlBorderData(show: false),
                lineBarsData: [
                  LineChartBarData(
                    spots: spots,
                    isCurved: true,
                    color: const Color(0xFF10B981), // Emerald
                    barWidth: 4,
                    isStrokeCapRound: true,
                    dotData: const FlDotData(show: false),
                    belowBarData: BarAreaData(
                      show: true,
                      color: const Color(0xFF10B981).withOpacity(0.1),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildViewAnalytics(AsyncValue<GigAnalyticsModel> analyticsAsync) {
    if (analyticsAsync.isLoading) {
      return Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
        ),
        child: Shimmer.fromColors(
          baseColor: Colors.grey[300]!,
          highlightColor: Colors.grey[100]!,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Container(width: 150, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                  Container(width: 24, height: 24, decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle)),
                ],
              ),
              const SizedBox(height: 32),
              Container(height: 200, width: double.infinity, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16))),
            ],
          ),
        ),
      );
    }
    final ordersChart = analyticsAsync.asData?.value.ordersChart ?? [];
    List<FlSpot> spots = [];
    if (ordersChart.isNotEmpty) {
      for (int i = 0; i < ordersChart.length; i++) {
        spots.add(FlSpot(i.toDouble(), ordersChart[i].value));
      }
    } else {
      for (int i = 0; i < 7; i++) {
        spots.add(FlSpot(i.toDouble(), 0));
      }
    }

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Orders Analytics',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF1F2937),
                ),
              ),
              Icon(Icons.more_horiz, color: Colors.grey[400]),
            ],
          ),
          const SizedBox(height: 32),
          SizedBox(
            height: 200,
            child: LineChart(
              LineChartData(
                gridData: const FlGridData(show: false),
                titlesData: const FlTitlesData(show: false),
                borderData: FlBorderData(show: false),
                lineBarsData: [
                  LineChartBarData(
                    spots: spots,
                    isCurved: true,
                    color: const Color(0xFF6366F1),
                    barWidth: 4,
                    isStrokeCapRound: true,
                    dotData: const FlDotData(show: false),
                    belowBarData: BarAreaData(
                      show: true,
                      color: const Color(0xFF6366F1).withOpacity(0.1),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEarnAnalytics(AsyncValue<GigAnalyticsModel> analyticsAsync) {
    if (analyticsAsync.isLoading) {
      return Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
        ),
        child: Shimmer.fromColors(
          baseColor: Colors.grey[300]!,
          highlightColor: Colors.grey[100]!,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Container(width: 150, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                  Container(width: 80, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20))),
                ],
              ),
              const SizedBox(height: 32),
              Container(height: 200, width: double.infinity, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16))),
            ],
          ),
        ),
      );
    }
    final salesChart = analyticsAsync.asData?.value.salesChart ?? [];
    List<BarChartGroupData> barGroups = [];

    if (salesChart.isNotEmpty) {
      for (int i = 0; i < salesChart.length; i++) {
        barGroups.add(_buildBarGroup(i, salesChart[i].value, const Color(0xFF818CF8)));
      }
    } else {
      for (int i = 0; i < 7; i++) {
        barGroups.add(_buildBarGroup(i, 0, Colors.white.withOpacity(0.2)));
      }
    }

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: const Color(0xFF1F2937),
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF1F2937).withOpacity(0.3),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Earn Analytics',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
              Icon(Icons.more_horiz, color: Colors.white.withOpacity(0.5)),
            ],
          ),
          const SizedBox(height: 32),
          SizedBox(
            height: 200,
            child: BarChart(
              BarChartData(
                gridData: const FlGridData(show: false),
                titlesData: const FlTitlesData(show: false),
                borderData: FlBorderData(show: false),
                barGroups: barGroups,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRecentOrdersSection(AsyncValue<GigAnalyticsModel> analyticsAsync) {
    if (analyticsAsync.isLoading) {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Shimmer.fromColors(
            baseColor: Colors.grey[300]!,
            highlightColor: Colors.grey[100]!,
            child: Container(width: 150, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
          ),
          const SizedBox(height: 16),
          Container(
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.03),
                  blurRadius: 20,
                  offset: const Offset(0, 10),
                ),
              ],
            ),
            child: Shimmer.fromColors(
              baseColor: Colors.grey[300]!,
              highlightColor: Colors.grey[100]!,
              child: Column(
                children: List.generate(3, (index) => Padding(
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    children: [
                      Container(width: 40, height: 40, decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle)),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Container(width: 120, height: 16, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                            const SizedBox(height: 8),
                            Container(width: 60, height: 12, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                          ],
                        ),
                      ),
                    ],
                  ),
                )),
              ),
            ),
          ),
        ],
      );
    }
    final recentOrders = analyticsAsync.asData?.value.recentOrders ?? [];

    if (recentOrders.isEmpty) return const SizedBox();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Recent Orders',
          style: GoogleFonts.plusJakartaSans(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: const Color(0xFF1F2937),
          ),
        ),
        const SizedBox(height: 16),
        Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.03),
                blurRadius: 20,
                offset: const Offset(0, 10),
              ),
            ],
          ),
          child: Column(
            children: recentOrders.map<Widget>((order) => _buildOrderTile(order)).toList(),
          ),
        ),
      ],
    );
  }

  Widget _buildOrderTile(dynamic order) {
    final user = order['user'];
    final userName = user != null ? user['name'] : 'Unknown User';
    final userPhoto = user != null ? user['profile_photo_path'] : null;
    final status = order['status'] ?? 'pending';
    final amount = order['total_amount'] ?? 0;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        border: Border(bottom: BorderSide(color: Colors.grey[100]!)),
      ),
      child: Row(
        children: [
          CircleAvatar(
            backgroundColor: const Color(0xFF6366F1),
            backgroundImage: userPhoto != null ? NetworkImage(resolveImageUrl(userPhoto)) : null,
            child: userPhoto == null
                ? Text(
                    (userName.toString())[0].toUpperCase(),
                    style: const TextStyle(color: Colors.white),
                  )
                : null,
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  userName,
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF1E293B),
                  ),
                ),
                const SizedBox(height: 4),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                  decoration: BoxDecoration(
                    color: _getStatusColor(status).withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    status.toString().toUpperCase(),
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 10,
                      fontWeight: FontWeight.bold,
                      color: _getStatusColor(status),
                    ),
                  ),
                ),
              ],
            ),
          ),
          Text(
            '\$${amount}',
            style: GoogleFonts.plusJakartaSans(
              fontWeight: FontWeight.bold,
              fontSize: 16,
              color: const Color(0xFF1E293B),
            ),
          ),
        ],
      ),
    );
  }

  BarChartGroupData _buildBarGroup(int x, double y, Color color) {
    return BarChartGroupData(
      x: x,
      barRods: [
        BarChartRodData(
          toY: y,
          color: color,
          width: 20,
          borderRadius: BorderRadius.circular(6),
        ),
      ],
    );
  }

  Widget _buildPackagesSection(GigModel gig) {
    if (gig.packages.isEmpty) return const SizedBox();

    return DefaultTabController(
      length: gig.packages.length,
      child: Column(
        children: [
          Container(
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: Colors.grey[200]!),
            ),
            child: TabBar(
              labelColor: const Color(0xFF6366F1),
              unselectedLabelColor: const Color(0xFF64748B),
              indicatorColor: const Color(0xFF6366F1),
              indicatorSize: TabBarIndicatorSize.label,
              labelStyle: GoogleFonts.plusJakartaSans(
                fontWeight: FontWeight.bold,
              ),
              tabs: gig.packages
                  .map((p) => Tab(text: '\$${p.price.toStringAsFixed(0)}'))
                  .toList(),
            ),
          ),
          const SizedBox(height: 20),
          SizedBox(
            height: 300, // Adjust height as needed
            child: TabBarView(
              children: gig.packages.map((package) {
                return Container(
                  padding: const EdgeInsets.all(24),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(24),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.03),
                        blurRadius: 20,
                        offset: const Offset(0, 10),
                      ),
                    ],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            package.tier,
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF1E293B),
                            ),
                          ),
                          Icon(
                            Icons.check_circle,
                            color: const Color(0xFF10B981),
                            size: 24,
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Text(
                        package.description.isEmpty
                            ? 'No description available.'
                            : package.description,
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 14,
                          color: const Color(0xFF64748B),
                          height: 1.5,
                        ),
                      ),
                      const SizedBox(height: 24),
                      _buildFeatureRow(
                        Icons.schedule,
                        '${package.deliveryDays} Days Delivery',
                      ),
                      const SizedBox(height: 12),
                      _buildFeatureRow(
                        Icons.refresh,
                        '${package.revisions} Revisions',
                      ),
                    ],
                  ),
                );
              }).toList(),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFeatureRow(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 18, color: const Color(0xFF94A3B8)),
        const SizedBox(width: 12),
        Text(
          text,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            fontWeight: FontWeight.w600,
            color: const Color(0xFF475569),
          ),
        ),
      ],
    );
  }

  Widget _buildSellerProfilePreviewSection(BuildContext context, GigModel gig) {
    final user = provider.Provider.of<AuthProvider>(
      context,
      listen: false,
    ).user;
    if (user == null) return const SizedBox();

    return InkWell(
      onTap: () => _showSellerProfileModal(context, user, gig),
      borderRadius: BorderRadius.circular(20),
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.grey[200]!),
        ),
        child: Row(
          children: [
            CircleAvatar(
              radius: 24,
              backgroundColor: const Color(0xFF6366F1),
              backgroundImage: user.profilePhotoUrl != null
                  ? NetworkImage(user.profilePhotoUrl!)
                  : null,
              child: user.profilePhotoUrl == null
                  ? Text(
                (user.name ?? 'U')[0].toUpperCase(),
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              )
                  : null,
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    user.name ?? 'User',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                  Text(
                    user.level ?? 'New Seller',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 12,
                      color: const Color(0xFF64748B),
                    ),
                  ),
                ],
              ),
            ),
            const Icon(
              Icons.arrow_forward_ios_rounded,
              size: 16,
              color: Color(0xFF94A3B8),
            ),
          ],
        ),
      ),
    );
  }

  void _showSellerProfileModal(BuildContext context, UserModel user, GigModel gig) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) => Container(
        height: MediaQuery.of(context).size.height * 0.85,
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
        ),
        child: Column(
          children: [
            const SizedBox(height: 12),
            Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                color: Colors.grey[300],
                borderRadius: BorderRadius.circular(2),
              ),
            ),
            const SizedBox(height: 24),
            Expanded(
              child: ListView(
                padding: const EdgeInsets.symmetric(horizontal: 24),
                children: [
                  Center(
                    child: CircleAvatar(
                      radius: 40,
                      backgroundColor: const Color(0xFF6366F1),
                      backgroundImage: user.profilePhotoUrl != null
                          ? NetworkImage(user.profilePhotoUrl!)
                          : null,
                      child: user.profilePhotoUrl == null
                          ? Text(
                        (user.name ?? 'U')[0].toUpperCase(),
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 32,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      )
                          : null,
                    ),
                  ),
                  const SizedBox(height: 16),
                  Center(
                    child: Text(
                      user.name ?? 'User',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1E293B),
                      ),
                    ),
                  ),
                  const SizedBox(height: 8),
                  Center(
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(
                          Icons.star_rounded,
                          color: Colors.amber,
                          size: 20,
                        ),
                        const SizedBox(width: 4),
                        Text(
                          '${user.rating?.toStringAsFixed(1) ?? "0.0"} (${user.totalReviews ?? 0} reviews)',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 16,
                            fontWeight: FontWeight.w600,
                            color: const Color(0xFF4B5563),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),

                  // Rating Breakdown
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF8FAFC),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Column(
                      children: [
                        _buildRatingRow('Seller communication level', user.rating ?? 0.0),
                        const SizedBox(height: 12),
                        _buildRatingRow('Recommend to a friend', user.rating ?? 0.0),
                        const SizedBox(height: 12),
                        _buildRatingRow('Service as described', user.rating ?? 0.0),
                      ],
                    ),
                  ),

                  const SizedBox(height: 32),
                  const Divider(),
                  const SizedBox(height: 24),
                  _buildProfileInfoRow(
                    Icons.location_on_outlined,
                    'From',
                    user.country ?? 'Unknown',
                  ),
                  const SizedBox(height: 16),
                  _buildProfileInfoRow(
                    Icons.translate_rounded,
                    'Languages',
                    user.languages?.join(', ') ?? 'English',
                  ),
                  const SizedBox(height: 16),
                  _buildProfileInfoRow(
                    Icons.access_time_rounded,
                    'Avg. response time',
                    user.avgResponseTime ?? 'N/A',
                  ),
                  const SizedBox(height: 16),
                  _buildProfileInfoRow(
                    Icons.calendar_today_rounded,
                    'Member since',
                    'Jan 2023',
                  ),
                  const SizedBox(height: 32),
                  const Divider(),
                  const SizedBox(height: 24),
                  Text(
                    'Description',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1F293B),
                    ),
                  ),
                  const SizedBox(height: 12),
                  _DescriptionText(
                    text: gig.description,
                  ),
                  const SizedBox(height: 40),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileInfoRow(IconData icon, String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, size: 20, color: const Color(0xFF94A3B8)),
        const SizedBox(width: 16),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 14,
                  color: const Color(0xFF64748B),
                ),
              ),
              const SizedBox(height: 4),
              Text(
                value,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                  color: const Color(0xFF1E293B),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildReviewsSection(AsyncValue<GigAnalyticsModel> analyticsAsync) {
    if (analyticsAsync.isLoading) {
      return Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
        ),
        child: Shimmer.fromColors(
          baseColor: Colors.grey[300]!,
          highlightColor: Colors.grey[100]!,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(width: 100, height: 24, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                      const SizedBox(height: 8),
                      Container(width: 150, height: 16, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                    ],
                  ),
                  Container(width: 60, height: 20, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                ],
              ),
              const SizedBox(height: 24),
              Column(
                children: List.generate(3, (index) => Padding(
                  padding: const EdgeInsets.only(bottom: 24),
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(width: 40, height: 40, decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle)),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Container(width: 120, height: 16, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                            const SizedBox(height: 8),
                            Container(width: 100, height: 12, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                            const SizedBox(height: 12),
                            Container(width: double.infinity, height: 14, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                            const SizedBox(height: 8),
                            Container(width: 200, height: 14, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(4))),
                          ],
                        ),
                      ),
                    ],
                  ),
                )),
              ),
            ],
          ),
        ),
      );
    }
    final analytics = analyticsAsync.asData?.value;
    final reviews = analytics?.recentReviews ?? [];
    final totalReviews = analytics?.totalReviews ?? 0;
    final averageRating = analytics?.averageRating ?? 0.0;

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Reviews',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1F2937),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      const Icon(Icons.star_rounded, color: Color(0xFFEAB308), size: 20),
                      const SizedBox(width: 4),
                      Text(
                        '$averageRating',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: const Color(0xFF1F2937),
                        ),
                      ),
                      Text(
                        ' ($totalReviews reviews)',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 14,
                          color: const Color(0xFF6B7280),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
              if (reviews.isNotEmpty)
                TextButton(
                  onPressed: () {
                    if (widget.gig.id != null) {
                      showModalBottomSheet(
                        context: context,
                        isScrollControlled: true,
                        backgroundColor: Colors.transparent,
                        builder: (context) => AllReviewsBottomSheet(
                          gigId: widget.gig.id!,
                          averageRating: averageRating,
                          totalReviews: totalReviews,
                        ),
                      );
                    }
                  },
                  child: Text(
                    'See All',
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.w600,
                      color: const Color(0xFF3B82F6),
                    ),
                  ),
                ),
            ],
          ),
          const SizedBox(height: 24),
          if (reviews.isEmpty)
             Center(
               child: Padding(
                 padding: const EdgeInsets.symmetric(vertical: 20),
                 child: Text(
                   'No reviews yet',
                   style: GoogleFonts.plusJakartaSans(
                     color: const Color(0xFF9CA3AF),
                     fontSize: 14,
                   ),
                 ),
               ),
             )
          else
            ListView.separated(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: reviews.length,
              separatorBuilder: (context, index) => const Divider(height: 32),
              itemBuilder: (context, index) {
                final review = reviews[index];
                final user = review['user'];
                String userName = 'Unknown User';
                if (user != null) {
                  final firstName = user['first_name'] ?? '';
                  final lastName = user['last_name'] ?? '';
                  final fullName = '$firstName $lastName'.trim();
                  if (fullName.isNotEmpty) {
                    userName = fullName;
                  } else if (user['name'] != null) {
                    userName = user['name'];
                  }
                }
                final userImage = user != null ? user['profile_image'] : null;
                final rating = double.tryParse(review['rating'].toString()) ?? 0.0;
                final comment = review['review'] ?? '';
                final timeAgo = review['created_at'] != null 
                    ? timeago.format(DateTime.parse(review['created_at'])) 
                    : '';

                return Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        CircleAvatar(
                          radius: 20,
                          backgroundColor: Colors.grey[200],
                          backgroundImage: userImage != null ? NetworkImage(resolveImageUrl(userImage)) : null,
                          child: userImage == null 
                              ? Text(userName[0].toUpperCase(), style: const TextStyle(color: Colors.grey)) 
                              : null,
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                userName,
                                style: GoogleFonts.plusJakartaSans(
                                  fontWeight: FontWeight.bold,
                                  color: const Color(0xFF1F2937),
                                  fontSize: 14,
                                ),
                              ),
                              const SizedBox(height: 2),
                              Row(
                                children: [
                                  ...List.generate(5, (starIndex) {
                                    return Icon(
                                      Icons.star_rounded,
                                      size: 14,
                                      color: starIndex < rating 
                                          ? const Color(0xFFEAB308) 
                                          : Colors.grey[300],
                                    );
                                  }),
                                  const SizedBox(width: 8),
                                  Text(
                                    timeAgo,
                                    style: GoogleFonts.plusJakartaSans(
                                      fontSize: 12,
                                      color: const Color(0xFF9CA3AF),
                                    ),
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                    if (comment.isNotEmpty) ...[
                      const SizedBox(height: 12),
                      Text(
                        comment,
                        style: GoogleFonts.plusJakartaSans(
                          color: const Color(0xFF4B5563),
                          fontSize: 14,
                          height: 1.5,
                        ),
                      ),
                    ],
                  ],
                );
              },
            ),
        ],
      ),
    );
  }

  Widget _buildRatingRow(String label, double rating) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            color: const Color(0xFF4B5563),
            fontWeight: FontWeight.w500,
          ),
        ),
        Row(
          children: [
            const Icon(Icons.star_rounded, color: Colors.black, size: 16),
            const SizedBox(width: 4),
            Text(
              rating.toString(),
              style: GoogleFonts.plusJakartaSans(
                fontSize: 14,
                fontWeight: FontWeight.bold,
                color: Colors.black,
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildFAQSection(GigModel gig) {
    if (gig.faqs.isEmpty) return const SizedBox.shrink();

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Frequently Asked Questions',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF1F2937),
            ),
          ),
          const SizedBox(height: 16),
          ListView.separated(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: gig.faqs.length,
            separatorBuilder: (context, index) => const Divider(),
            itemBuilder: (context, index) {
              final faq = gig.faqs[index];
              return _buildFAQItem(faq.question, faq.answer);
            },
          ),
        ],
      ),
    );
  }

  Widget _buildFAQItem(String question, String answer) {
    return ExpansionTile(
      title: Text(
        question,
        style: GoogleFonts.plusJakartaSans(
          fontSize: 14,
          fontWeight: FontWeight.w600,
          color: const Color(0xFF1E293B),
        ),
      ),
      childrenPadding: const EdgeInsets.only(bottom: 16),
      expandedCrossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          answer,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            color: const Color(0xFF64748B),
            height: 1.5,
          ),
        ),
      ],
    );
  }
}

class _DescriptionText extends StatefulWidget {
  final String text;
  const _DescriptionText({required this.text});

  @override
  State<_DescriptionText> createState() => _DescriptionTextState();
}

class _DescriptionTextState extends State<_DescriptionText> {
  bool isExpanded = false;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          widget.text,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            color: const Color(0xFF4B5563),
            height: 1.6,
          ),
          maxLines: isExpanded ? null : 3,
          overflow: isExpanded ? TextOverflow.visible : TextOverflow.ellipsis,
        ),
        const SizedBox(height: 8),
        InkWell(
          onTap: () => setState(() => isExpanded = !isExpanded),
          child: Text(
            isExpanded ? 'Show Less' : 'Show More',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF6366F1),
            ),
          ),
        ),
      ],
    );
  }
}
