import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:provider/provider.dart';

class IntroPage extends StatefulWidget {
  const IntroPage({super.key});

  @override
  State<IntroPage> createState() => _IntroPageState();
}

class _IntroPageState extends State<IntroPage> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<Map<String, String>> _onboardingData = [
    {
      'title': 'Expand Your Business',
      'description':
          'Connect with thousands of local customers looking for your expertise. Grow your reach instantly.',
      'image':
          'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
    },
    {
      'title': 'Manage with Ease',
      'description':
          'Track jobs, manage earnings, and communicate with clients all in one professional dashboard.',
      'image':
          'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
    },
    {
      'title': 'Get Paid Securely',
      'description':
          'Secure payment processing and instant withdrawals. Focus on your work, we handle the rest.',
      'image':
          'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
    },
  ];

  void _onNext() {
    if (_currentPage < _onboardingData.length - 1) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 500),
        curve: Curves.easeInOutQuint,
      );
    } else {
      _completeOnboarding();
    }
  }

  Future<void> _completeOnboarding() async {
    final prefs = context.read<SharedPreferences>();
    await prefs.setBool('has_seen_intro', true);
    if (mounted) {
      context.go('/welcome');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Stack(
        children: [
          PageView.builder(
            controller: _pageController,
            onPageChanged: (index) {
              setState(() {
                _currentPage = index;
              });
            },
            itemCount: _onboardingData.length,
            itemBuilder: (context, index) => _buildSlide(
              data: _onboardingData[index],
              isActive: index == _currentPage,
            ),
          ),
          Positioned(
            bottom: 40,
            left: 24,
            right: 24,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: List.generate(
                    _onboardingData.length,
                    (index) => AnimatedContainer(
                      duration: const Duration(milliseconds: 300),
                      margin: const EdgeInsets.only(right: 8),
                      height: 8,
                      width: _currentPage == index ? 24 : 8,
                      decoration: BoxDecoration(
                        color: _currentPage == index
                            ? Theme.of(context).primaryColor
                            : Colors.grey.withOpacity(0.3),
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                  ),
                ),
                AnimatedScale(
                  scale: _currentPage == _onboardingData.length - 1
                      ? 1.05
                      : 1.0,
                  duration: 300.ms,
                  curve: Curves.easeOutBack,
                  child: ElevatedButton(
                    onPressed: _onNext,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Theme.of(context).primaryColor,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(
                        horizontal: 32,
                        vertical: 16,
                      ),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(30),
                      ),
                      elevation: 10,
                      shadowColor: Theme.of(
                        context,
                      ).primaryColor.withOpacity(0.4),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(
                          _currentPage == _onboardingData.length - 1
                              ? 'Get Started'
                              : 'Next',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                          ),
                        ),
                        const SizedBox(width: 8),
                        const Icon(Icons.arrow_forward_rounded, size: 20),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
          Positioned(
            top: 50,
            right: 24,
            child: TextButton(
              onPressed: _completeOnboarding,
              child: Text(
                'Skip',
                style: GoogleFonts.plusJakartaSans(
                  color: Colors.grey[600],
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSlide({
    required Map<String, String> data,
    required bool isActive,
  }) {
    return Column(
      children: [
        Expanded(
          flex: 3,
          child:
              Container(
                    decoration: BoxDecoration(
                      borderRadius: const BorderRadius.only(
                        bottomLeft: Radius.circular(40),
                        bottomRight: Radius.circular(40),
                      ),
                      image: DecorationImage(
                        image: NetworkImage(data['image']!),
                        fit: BoxFit.cover,
                        colorFilter: ColorFilter.mode(
                          Colors.black.withOpacity(0.2),
                          BlendMode.darken,
                        ),
                      ),
                    ),
                  )
                  .animate(target: isActive ? 1 : 0)
                  .fadeIn(duration: 600.ms)
                  .scale(
                    begin: const Offset(1.1, 1.1),
                    end: const Offset(1, 1),
                  ),
        ),
        Expanded(
          flex: 2,
          child: Padding(
            padding: const EdgeInsets.all(32.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 20),
                Text(
                      data['title']!,
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 32,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1E293B),
                        height: 1.1,
                      ),
                    )
                    .animate(target: isActive ? 1 : 0)
                    .fadeIn(delay: 200.ms)
                    .slideX(begin: -0.2, end: 0),
                const SizedBox(height: 16),
                Text(
                      data['description']!,
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 16,
                        color: Colors.grey[600],
                        height: 1.5,
                      ),
                    )
                    .animate(target: isActive ? 1 : 0)
                    .fadeIn(delay: 400.ms)
                    .slideX(begin: -0.2, end: 0),
              ],
            ),
          ),
        ),
      ],
    );
  }
}
