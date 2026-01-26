import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:provider/provider.dart';
import '../../../../core/theme/provider_theme.dart';
import '../../../freelancer/orders/presentation/pages/requests_page.dart';
import '../../../services/presentation/pages/services_page.dart';
import '../../../profile/presentation/pages/profile_page.dart';
import '../../../auth/presentation/providers/auth_provider.dart';
import '../../../freelancer/gigs/presentation/pages/gigs_page.dart';
import '../../../freelancer/presentation/pages/freelancer_home_view.dart';
import '../../../chat/presentation/pages/chat_page.dart';
import '../../../chat/presentation/providers/chat_provider.dart';
import '../../../freelancer/orders/presentation/providers/requests_provider.dart';

class DashboardPage extends StatefulWidget {
  final int initialIndex;
  const DashboardPage({super.key, this.initialIndex = 0});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  late int _currentIndex;

  @override
  void initState() {
    super.initState();
    _currentIndex = widget.initialIndex;
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final authProvider = context.read<AuthProvider>();
      if (authProvider.user != null && authProvider.user!.id != null) {
        // Initialize Pusher for real-time updates
        context.read<ChatProvider>().initPusher(authProvider.user!.id!);
        
        // Initial fetch for orders to populate badge count
        context.read<RequestsProvider>().fetchOrders();
        
        // Initialize real-time listeners for requests
        context.read<RequestsProvider>().setupRealtime(authProvider.user!.id!);
      }
    });
  }

  @override
  void didUpdateWidget(DashboardPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (widget.initialIndex != oldWidget.initialIndex) {
      setState(() {
        _currentIndex = widget.initialIndex;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final requestsProvider = context.watch<RequestsProvider>();
    final user = authProvider.user;

    // Explicitly check service_rule as primary source of truth
    debugPrint(
      'Dashboard: Checking mode. ServiceRule: ${user?.serviceRule}, Mode: ${user?.mode}',
    );

    // If serviceRule is 'freelancer', it IS a freelancer.
    // Fallback to 'mode' only if serviceRule is null (though DB should have it).
    final isFreelancer =
        (user?.serviceRule == 'freelancer') || (user?.mode == 'freelancer');

    final servicesLabel = isFreelancer ? 'Gigs' : 'Services';

    final List<Widget> pages = [
      isFreelancer ? const FreelancerHomeView() : const LocalServiceHomeView(),
      const ChatPage(),
      const RequestsPage(),
      isFreelancer ? const GigsPage() : const ServicesPage(),
      const ProfilePage(),
    ];

    return PopScope(
      canPop: _currentIndex == 0,
      onPopInvokedWithResult: (didPop, result) {
        if (didPop) return;
        setState(() {
          _currentIndex = 0;
        });
      },
      child: Scaffold(
        extendBody: true,
        body: pages[_currentIndex],
        bottomNavigationBar: Container(
          margin: EdgeInsets.fromLTRB(
            20,
            0,
            20,
            20 + MediaQuery.of(context).padding.bottom,
          ),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(30),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.1),
              blurRadius: 30,
              offset: const Offset(0, 10),
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(30),
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
            child: Container(
              height: 70, // Slightly more compact
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.9),
                borderRadius: BorderRadius.circular(30),
                border: Border.all(color: Colors.white, width: 1.0),
              ),
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  _buildNavItem(
                    0,
                    Icons.home_outlined,
                    Icons.home_rounded,
                    'Home',
                  ),
                  _buildNavItem(
                    1,
                    Icons.chat_bubble_outline_rounded,
                    Icons.chat_bubble_rounded,
                    'Chat',
                  ),
                  _buildNavItem(
                    2,
                    Icons.assignment_outlined,
                    Icons.assignment_rounded,
                    isFreelancer ? 'Orders' : 'Requests',
                    badgeCount: requestsProvider.pendingOrders.length,
                  ),
                  _buildNavItem(
                    3,
                    isFreelancer
                        ? Icons.work_outline
                        : Icons.grid_view_outlined,
                    isFreelancer ? Icons.work : Icons.grid_view_rounded,
                    servicesLabel,
                  ),
                  _buildNavItem(
                    4,
                    Icons.person_outline,
                    Icons.person_rounded,
                    'Profile',
                  ),
                ],
              ),
            ),
          ),
        ),
        ),
      ),
    );
  }

  Widget _buildNavItem(
    int index,
    IconData icon,
    IconData activeIcon,
    String label, {
    int badgeCount = 0,
  }) {
    final isSelected = _currentIndex == index;
    return GestureDetector(
      onTap: () => setState(() => _currentIndex = index),
      behavior: HitTestBehavior.opaque,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeOutQuint,
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected
              ? Colors.black.withOpacity(0.05)
              : Colors.transparent,
          borderRadius: BorderRadius.circular(16),
        ),
        child: Row(
          // Changed to Row for pill shape if text is visible, or keep Column. Let's try Icon + Dot or just Icon + Text
          mainAxisSize: MainAxisSize.min,
          children: [
            Stack(
              clipBehavior: Clip.none,
              children: [
                Icon(
                  isSelected ? activeIcon : icon,
                  color: isSelected
                      ? const Color(0xFF1E293B)
                      : Colors.grey[400],
                  size: 24,
                )
                .animate(target: isSelected ? 1 : 0)
                .scale(
                  begin: const Offset(1, 1),
                  end: const Offset(1.1, 1.1),
                  duration: 200.ms,
                ),
                if (badgeCount > 0)
                  Positioned(
                    top: -5,
                    right: -5,
                    child: Container(
                      padding: const EdgeInsets.all(4),
                      decoration: const BoxDecoration(
                        color: Colors.red,
                        shape: BoxShape.circle,
                      ),
                      constraints: const BoxConstraints(
                        minWidth: 16,
                        minHeight: 16,
                      ),
                      child: Text(
                        '$badgeCount',
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ).animate().scale(duration: 200.ms, curve: Curves.easeOutBack),
                  ),
              ],
            ),
            if (isSelected) ...[
              const SizedBox(width: 8),
              Text(
                label,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF1E293B),
                ),
              ).animate().fadeIn().slideX(begin: -0.2, end: 0),
            ],
          ],
        ),
      ),
    );
  }
}

class LocalServiceHomeView extends StatelessWidget {
  const LocalServiceHomeView({super.key});

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user;
    final userName = user?.name?.split(' ').first ?? 'Provider';

    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      appBar: AppBar(
        systemOverlayStyle: SystemUiOverlayStyle(
          statusBarColor: Colors.transparent,
          statusBarIconBrightness: Brightness.dark,
          statusBarBrightness: Brightness.light,
          systemNavigationBarColor: ProviderTheme.backgroundColor,
          systemNavigationBarIconBrightness: Brightness.dark,
          systemNavigationBarDividerColor: Colors.transparent,
        ),
        backgroundColor: Colors.transparent,
        elevation: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Hi, $userName 👋',
              style: GoogleFonts.plusJakartaSans(
                fontWeight: FontWeight.bold,
                fontSize: 24,
                color: const Color(0xFF1E293B),
              ),
            ),
            Text(
              'Welcome back',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 13,
                fontWeight: FontWeight.w500,
                color: Colors.grey[500],
              ),
            ),
          ],
        ).animate().fadeIn().slideX(begin: -0.2, end: 0),
        actions: [
          Container(
            margin: const EdgeInsets.only(right: 16),
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(20),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 10,
                  offset: const Offset(0, 2),
                ),
              ],
            ),
            child: Row(
              children: [
                Container(
                  width: 8,
                  height: 8,
                  decoration: const BoxDecoration(
                    color: Colors.green,
                    shape: BoxShape.circle,
                  ),
                ),
                const SizedBox(width: 8),
                Text(
                  'Online',
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.w700,
                    fontSize: 13,
                    color: Colors.black87,
                  ),
                ),
                const SizedBox(width: 8),
                SizedBox(
                  height: 20,
                  width: 30,
                  child: Switch(
                    value: true,
                    onChanged: (v) {},
                    activeColor: Colors.green,
                    activeTrackColor: Colors.green.withOpacity(0.2),
                    thumbColor: MaterialStateProperty.all(Colors.white),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(
          24,
          10,
          24,
          120,
        ), // Added bottom padding for floating nav
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildEarningsCard(
              context,
            ).animate().fadeIn().slideY(begin: 0.2, end: 0),
            const SizedBox(height: 32),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Recent Job Requests',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF1E293B),
                  ),
                ),
                TextButton(
                  onPressed: () {},
                  child: Text(
                    'View All',
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            _buildEmptyState().animate().fadeIn(delay: 200.ms),
          ],
        ),
      ),
    );
  }

  Widget _buildEarningsCard(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24.0),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            Theme.of(context).primaryColor,
            Theme.of(context).colorScheme.secondary,
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Theme.of(context).primaryColor.withOpacity(0.3),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        children: [
          Text(
            'Total Earnings',
            style: GoogleFonts.plusJakartaSans(
              color: Colors.white.withOpacity(0.8),
              fontSize: 14,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            '\$1,240.00',
            style: GoogleFonts.plusJakartaSans(
              color: Colors.white,
              fontSize: 32,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 24),
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.1),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: Colors.white.withOpacity(0.1)),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildStatItem('Jobs', '45'),
                Container(
                  width: 1,
                  height: 40,
                  color: Colors.white.withOpacity(0.2),
                ),
                _buildStatItem('Rating', '4.8'),
                Container(
                  width: 1,
                  height: 40,
                  color: Colors.white.withOpacity(0.2),
                ),
                _buildStatItem('Wallet', '\$320'),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatItem(String label, String value) {
    return Column(
      children: [
        Text(
          value,
          style: GoogleFonts.plusJakartaSans(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 16,
          ),
        ),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            color: Colors.white.withOpacity(0.7),
            fontSize: 12,
          ),
        ),
      ],
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const SizedBox(height: 40),
          Icon(
            Icons.inbox_rounded,
            size: 60,
            color: Colors.grey.withOpacity(0.3),
          ),
          const SizedBox(height: 16),
          Text(
            'No active job requests',
            style: GoogleFonts.plusJakartaSans(color: Colors.grey),
          ),
        ],
      ),
    );
  }
}
