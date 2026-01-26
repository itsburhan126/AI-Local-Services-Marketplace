import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'dart:ui';
import '../widgets/FreelancerHomeView.dart';
import '../../../home/presentation/pages/category_page.dart';
import '../../../chat/presentation/pages/chat_page.dart';
import 'package:flutter_customer/features/freelancer/bookings/presentation/pages/bookings_page.dart';
import '../../../profile/presentation/pages/profile_page.dart';

class FreelancerHomePage extends StatefulWidget {
  const FreelancerHomePage({super.key});

  @override
  State<FreelancerHomePage> createState() => _FreelancerHomePageState();
}

class _FreelancerHomePageState extends State<FreelancerHomePage> {
  int _selectedIndex = 0;

  final List<Widget> _pages = const [
    FreelancerHomeView(),
    CategoryPage(type: 'freelancer'),
    ChatPage(),
    BookingsPage(),
    ProfilePage(),
  ];

  @override
  Widget build(BuildContext context) {
    // Make system bottom navigation bar transparent for edge-to-edge "Ultra" look
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.dark,
        systemNavigationBarColor: Colors.transparent, // Matches the app background
        systemNavigationBarIconBrightness: Brightness.dark,
        systemNavigationBarDividerColor: Colors.transparent,
      ),
      child: PopScope(
        canPop: _selectedIndex == 0,
        onPopInvokedWithResult: (didPop, result) {
          if (didPop) return;
          setState(() {
            _selectedIndex = 0;
          });
        },
        child: Scaffold(
          backgroundColor: const Color(0xFFF8FAFC),
          extendBody: true, // Critical for floating glassmorphism effect
          body: IndexedStack(
            index: _selectedIndex,
            children: _pages,
          ),
          bottomNavigationBar: _buildUltraBottomBar(context),
        ),
      ),
    );
  }

  Widget _buildUltraBottomBar(BuildContext context) {
    return Container(
      margin: EdgeInsets.fromLTRB(24, 0, 24, 20 + MediaQuery.of(context).padding.bottom),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.8), // Semi-transparent white
        borderRadius: BorderRadius.circular(32),
        border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 30,
            offset: const Offset(0, 15),
            spreadRadius: -5,
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(32),
        child: BackdropFilter(
          filter: ImageFilter.blur(sigmaX: 15, sigmaY: 15), // Heavy blur for glass effect
          child: Padding(
            padding: const EdgeInsets.all(8.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                _buildNavItem(0, Icons.home_rounded, Icons.home_outlined, 'Home'),
                _buildNavItem(1, Icons.grid_view_rounded, Icons.grid_view_outlined, 'Category'),
                _buildNavItem(2, Icons.chat_bubble_rounded, Icons.chat_bubble_outline_rounded, 'Chat'),
                _buildNavItem(3, Icons.calendar_month_rounded, Icons.calendar_today_outlined, 'Orders'),
                _buildNavItem(4, Icons.person_rounded, Icons.person_outline_rounded, 'Profile'),
              ],
            ),
          ),
        ),
      ),
    ).animate().fadeIn(delay: 300.ms).moveY(begin: 50, end: 0);
  }

  Widget _buildNavItem(int index, IconData selectedIcon, IconData unselectedIcon, String label) {
    final isSelected = _selectedIndex == index;
    return GestureDetector(
      onTap: () {
        if (_selectedIndex != index) {
          setState(() => _selectedIndex = index);
          HapticFeedback.lightImpact(); // Tactile feedback
        }
      },
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 400),
        curve: Curves.easeOutQuart, // Smoother, springier curve
        padding: EdgeInsets.symmetric(horizontal: isSelected ? 20 : 12, vertical: 12),
        decoration: BoxDecoration(
          color: isSelected ? const Color(0xFF0F172A) : Colors.transparent, // Slate 900
          borderRadius: BorderRadius.circular(24),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: const Color(0xFF0F172A).withValues(alpha: 0.3),
                    blurRadius: 12,
                    offset: const Offset(0, 4),
                  )
                ]
              : [],
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(
              isSelected ? selectedIcon : unselectedIcon,
              color: isSelected ? Colors.white : Colors.grey[500],
              size: 24,
            ).animate(target: isSelected ? 1 : 0).scale(begin: const Offset(1, 1), end: const Offset(1.1, 1.1)),
            if (isSelected) ...[
              const SizedBox(width: 8),
              Text(
                label,
                style: const TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.w600,
                  fontSize: 13,
                  fontFamily: 'Plus Jakarta Sans',
                ),
              ).animate().fadeIn(duration: 200.ms).moveX(begin: -5, end: 0),
            ],
          ],
        ),
      ),
    );
  }
}
