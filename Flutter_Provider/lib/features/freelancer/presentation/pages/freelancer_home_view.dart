import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:provider/provider.dart';
import '../../../../core/theme/provider_theme.dart';
import '../../../auth/presentation/providers/auth_provider.dart';

class FreelancerHomeView extends StatelessWidget {
  const FreelancerHomeView({super.key});

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user;
    final userName = user?.name ?? 'Provider';

    return Scaffold(
      backgroundColor: const Color(
        0xFFF8F9FE,
      ), // Light gray/blue background for ultra feel
      appBar: AppBar(
        systemOverlayStyle: SystemUiOverlayStyle(
          statusBarColor: Colors.transparent,
          statusBarIconBrightness: Brightness.dark,
          systemNavigationBarColor: const Color(
            0xFFF8F9FE,
          ), // Matches Scaffold background
          systemNavigationBarIconBrightness: Brightness.dark,
        ),
        backgroundColor: Colors.transparent,
        elevation: 0,
        toolbarHeight: 0, // Custom header
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(20, 10, 20, 120),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header
            _buildHeader(userName),
            const SizedBox(height: 24),

            // Main Stats Grid (Level, Score, Rating, Response)
            _buildMainStatsGrid(context),
            const SizedBox(height: 16),

            // Performance Metrics (Orders, Clients, Earnings)
            _buildPerformanceMetrics(context),
            const SizedBox(height: 24),

            // New Briefs
            _buildSectionHeader('New Briefs', action: 'Learn More'),
            const SizedBox(height: 12),
            _buildNewBriefsCard(),
            const SizedBox(height: 24),

            // Earnings Detailed
            _buildSectionHeader('Earnings', action: 'Details'),
            const SizedBox(height: 12),
            _buildEarningsDetailedCard(context),
            const SizedBox(height: 24),

            // To-dos
            _buildSectionHeader('To-dos'),
            const SizedBox(height: 12),
            _buildTodosCard(context),
            const SizedBox(height: 24),

            // My Gigs Stats
            _buildSectionHeader('My Gigs'),
            const SizedBox(height: 12),
            _buildGigsStatsCard(context),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(String userName) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Hi $userName,',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 16,
                fontWeight: FontWeight.w500,
                color: Colors.grey[600],
              ),
            ),
            Text(
              'Welcome back',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: const Color(0xFF1E293B),
              ),
            ),
          ],
        ),
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: Colors.white,
            shape: BoxShape.circle,
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.05),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Stack(
            children: [
              const Icon(Icons.notifications_outlined, color: Colors.black87),
              Positioned(
                top: 0,
                right: 0,
                child: Container(
                  width: 8,
                  height: 8,
                  decoration: const BoxDecoration(
                    color: Colors.red,
                    shape: BoxShape.circle,
                  ),
                ),
              ),
            ],
          ),
        ),
      ],
    ).animate().fadeIn().slideX(begin: -0.2, end: 0);
  }

  Widget _buildSectionHeader(String title, {String? action}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          title,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: const Color(0xFF1E293B),
          ),
        ),
        if (action != null)
          TextButton(
            onPressed: () {},
            style: TextButton.styleFrom(
              padding: EdgeInsets.zero,
              minimumSize: Size.zero,
              tapTargetSize: MaterialTapTargetSize.shrinkWrap,
            ),
            child: Text(
              action,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 14,
                fontWeight: FontWeight.w600,
                decoration: TextDecoration.underline,
                color: Colors.black87,
              ),
            ),
          ),
      ],
    );
  }

  Widget _buildMainStatsGrid(BuildContext context) {
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
        children: [
          Row(
            children: [
              Expanded(
                child: _buildGridItem(
                  'My level',
                  'Level 0',
                  icon: Icons.hexagon_outlined,
                ),
              ),
              Container(width: 1, height: 60, color: Colors.grey[200]),
              Expanded(
                child: _buildGridItem('Success score', '-', isProgress: true),
              ),
            ],
          ),
          const SizedBox(height: 20),
          Divider(color: Colors.grey[100], height: 1),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(child: _buildGridItem('Rating', '5.0', isRating: true)),
              Container(width: 1, height: 60, color: Colors.grey[200]),
              Expanded(
                child: _buildGridItem(
                  'Response rate',
                  '100%',
                  isProgress: true,
                  progressValue: 1.0,
                ),
              ),
            ],
          ),
        ],
      ),
    ).animate().fadeIn().slideY(begin: 0.1, end: 0, delay: 100.ms);
  }

  Widget _buildGridItem(
    String label,
    String value, {
    IconData? icon,
    bool isProgress = false,
    bool isRating = false,
    double progressValue = 0.0,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: Colors.black87,
            ),
          ),
          const SizedBox(height: 12),
          if (icon != null)
            Icon(
              icon,
              size: 32,
              color: Colors.grey[300],
            ), // Placeholder for level badge
          if (!isProgress && icon == null && !isRating)
            Text(
              value,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 20,
                fontWeight: FontWeight.bold,
              ),
            ),
          if (isRating)
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  value,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 6),
                ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: LinearProgressIndicator(
                    value: 1.0,
                    backgroundColor: Colors.grey[100],
                    valueColor: const AlwaysStoppedAnimation<Color>(
                      Colors.green,
                    ),
                    minHeight: 6,
                  ),
                ),
              ],
            ),
          if (isProgress && !isRating)
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  value,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 6),
                ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: LinearProgressIndicator(
                    value: progressValue > 0
                        ? progressValue
                        : null, // Indeterminate if 0/null
                    backgroundColor: Colors.grey[200],
                    valueColor: AlwaysStoppedAnimation<Color>(
                      progressValue > 0 ? Colors.green : Colors.grey[300]!,
                    ),
                    minHeight: 6,
                  ),
                ),
              ],
            ),
        ],
      ),
    );
  }

  Widget _buildPerformanceMetrics(BuildContext context) {
    return Container(
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
        children: [
          _buildPerformanceRow('Orders', '23', '/ 5'),
          Divider(color: Colors.grey[100], height: 1),
          _buildPerformanceRow('Unique clients', '22', '/ 3'),
          Divider(color: Colors.grey[100], height: 1),
          _buildPerformanceRow('Earnings', '\$611.20', '/ \$400'),
        ],
      ),
    ).animate().fadeIn().slideY(begin: 0.1, end: 0, delay: 200.ms);
  }

  Widget _buildPerformanceRow(String label, String value, String subValue) {
    return Padding(
      padding: const EdgeInsets.all(20),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: const Color(0xFF1E293B),
            ),
          ),
          RichText(
            text: TextSpan(
              children: [
                TextSpan(
                  text: value,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF1E293B),
                  ),
                ),
                TextSpan(
                  text: ' $subValue',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: Colors.grey[400],
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNewBriefsCard() {
    return Container(
      width: double.infinity,
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
          Text(
            'Nothing here',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF1E293B),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Explore high-potential projects and clients as briefs that match your skills are sent to you.',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              color: Colors.grey[600],
              height: 1.5,
            ),
          ),
        ],
      ),
    ).animate().fadeIn().slideY(begin: 0.1, end: 0, delay: 300.ms);
  }

  Widget _buildEarningsDetailedCard(BuildContext context) {
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
        children: [
          Row(
            children: [
              Expanded(
                child: _buildEarningDetailItem(
                  'Available for withdrawal',
                  '\$0',
                  isGreen: true,
                ),
              ),
              Expanded(
                child: _buildEarningDetailItem('Earnings in January', '\$24'),
              ),
            ],
          ),
          const SizedBox(height: 24),
          Row(
            children: [
              Expanded(
                child: _buildEarningDetailItem('Avg. selling price', '\$34.03'),
              ),
              Expanded(
                child: _buildEarningDetailItem(
                  'Active orders',
                  '0 (\$0)',
                  isMuted: true,
                ),
              ),
            ],
          ),
          const SizedBox(height: 24),
          Row(
            children: [
              Expanded(
                child: _buildEarningDetailItem('Payments being cleared', '\$0'),
              ),
              Expanded(
                child: _buildEarningDetailItem(
                  'Cancelled orders',
                  '0 (-\$0)',
                  isMuted: true,
                ),
              ),
            ],
          ),
        ],
      ),
    ).animate().fadeIn().slideY(begin: 0.1, end: 0, delay: 400.ms);
  }

  Widget _buildEarningDetailItem(
    String label,
    String value, {
    bool isGreen = false,
    bool isMuted = false,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 12,
            fontWeight: FontWeight.w500,
            color: Colors.grey[600],
          ),
        ),
        const SizedBox(height: 8),
        Text(
          value,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: isGreen
                ? Colors.green
                : (isMuted ? Colors.grey[400] : const Color(0xFF1E293B)),
          ),
        ),
      ],
    );
  }

  Widget _buildTodosCard(BuildContext context) {
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
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  '0 unread messages',
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    fontSize: 14,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  'Your response time is good. Keep up the great work!',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 13,
                    color: Colors.grey[600],
                    height: 1.4,
                  ),
                ),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            decoration: BoxDecoration(
              border: Border.all(color: Colors.grey[300]!),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Text(
              '0',
              style: GoogleFonts.plusJakartaSans(
                fontWeight: FontWeight.bold,
                color: Colors.grey[600],
              ),
            ),
          ),
        ],
      ),
    ).animate().fadeIn().slideY(begin: 0.1, end: 0, delay: 500.ms);
  }

  Widget _buildGigsStatsCard(BuildContext context) {
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
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Last 7 days',
                style: GoogleFonts.plusJakartaSans(
                  fontWeight: FontWeight.bold,
                  fontSize: 14,
                ),
              ),
              Text(
                'Statistics',
                style: GoogleFonts.plusJakartaSans(
                  fontWeight: FontWeight.bold,
                  fontSize: 14,
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          _buildGigStatRow('Impressions', '24', true),
          const SizedBox(height: 16),
          _buildGigStatRow('Clicks', '3', true),
        ],
      ),
    ).animate().fadeIn().slideY(begin: 0.1, end: 0, delay: 600.ms);
  }

  Widget _buildGigStatRow(String label, String value, bool isUp) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            color: Colors.grey[700],
            fontSize: 14,
          ),
        ),
        Row(
          children: [
            Text(
              value,
              style: GoogleFonts.plusJakartaSans(
                fontWeight: FontWeight.bold,
                fontSize: 14,
              ),
            ),
            const SizedBox(width: 8),
            Icon(
              isUp ? Icons.arrow_upward : Icons.arrow_downward,
              size: 14,
              color: isUp ? Colors.green : Colors.red,
            ),
          ],
        ),
      ],
    );
  }
}
