import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:flutter/services.dart';

class IntroPage extends StatelessWidget {
  const IntroPage({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    SystemChrome.setSystemUIOverlayStyle(SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: theme.brightness == Brightness.dark ? Brightness.light : Brightness.dark,
      systemNavigationBarColor: theme.scaffoldBackgroundColor,
      systemNavigationBarIconBrightness: theme.brightness == Brightness.dark ? Brightness.light : Brightness.dark,
      systemNavigationBarDividerColor: Colors.transparent,
    ));
    return Scaffold(
      body: Stack(
        children: [
          // Background Gradient
          Container(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [
                  Theme.of(context).colorScheme.primary.withValues(alpha: 0.05),
                  Theme.of(context).colorScheme.tertiary.withValues(alpha: 0.1),
                  Colors.white,
                ],
              ),
            ),
          ),
          // Animated Bubble 1 (Top Right)
          Positioned(
            top: -50,
            right: -50,
            child: Container(
              width: 200,
              height: 200,
              decoration: BoxDecoration(
                color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
                shape: BoxShape.circle,
                boxShadow: [
                  BoxShadow(
                    color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.2),
                    blurRadius: 50,
                    spreadRadius: 20,
                  ),
                ],
              ),
            ).animate(onPlay: (controller) => controller.repeat(reverse: true)).scale(
                  begin: const Offset(1, 1),
                  end: const Offset(1.2, 1.2),
                  duration: 3.seconds,
                ),
          ),
          // Animated Bubble 2 (Bottom Left)
          Positioned(
            bottom: -50,
            left: -50,
            child: Container(
              width: 250,
              height: 250,
              decoration: BoxDecoration(
                color: Theme.of(context).colorScheme.tertiary.withValues(alpha: 0.1),
                shape: BoxShape.circle,
                boxShadow: [
                  BoxShadow(
                    color: Theme.of(context).colorScheme.tertiary.withValues(alpha: 0.2),
                    blurRadius: 60,
                    spreadRadius: 10,
                  ),
                ],
              ),
            ).animate(onPlay: (controller) => controller.repeat(reverse: true)).scale(
                  begin: const Offset(1, 1),
                  end: const Offset(1.15, 1.15),
                  duration: 4.seconds,
                  curve: Curves.easeInOut,
                ),
          ),
          SafeArea(
            child: LayoutBuilder(
              builder: (context, constraints) {
                return SingleChildScrollView(
                  child: ConstrainedBox(
                    constraints: BoxConstraints(minHeight: constraints.maxHeight),
                    child: IntrinsicHeight(
                      child: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 16.0),
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Spacer(),
                            // Hero Image / Icon Container
                            Container(
                    padding: const EdgeInsets.all(40),
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.8),
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(
                          color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.2),
                          blurRadius: 40,
                          offset: const Offset(0, 20),
                        ),
                      ],
                      border: Border.all(
                        color: Colors.white,
                        width: 2,
                      ),
                    ),
                    child: Icon(
                      Icons.rocket_launch_rounded,
                      size: 80,
                      color: Theme.of(context).colorScheme.primary,
                    ),
                  )
                      .animate(onPlay: (controller) => controller.repeat(reverse: true))
                      .scale(begin: const Offset(1, 1), end: const Offset(1.05, 1.05), duration: 2.seconds)
                      .shimmer(delay: 1.seconds, duration: 2.seconds),
                  const SizedBox(height: 48),
                  // Title
                  Text(
                    'Find Best Local Services\nwith AI Power',
                    textAlign: TextAlign.center,
                    style: Theme.of(context).textTheme.displaySmall?.copyWith(
                          fontWeight: FontWeight.bold,
                          color: const Color(0xFF1E293B),
                          height: 1.2,
                          letterSpacing: -0.5,
                        ),
                  ).animate().fadeIn().moveY(begin: 20, end: 0),
                  const SizedBox(height: 16),
                  // Subtitle
                  Text(
                    'Get connected with top-rated professionals in your area instantly. Fast, reliable, and secure.',
                    textAlign: TextAlign.center,
                    style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                          color: const Color(0xFF64748B),
                          height: 1.5,
                        ),
                  ).animate().fadeIn(delay: 200.ms).moveY(begin: 20, end: 0),
                  const Spacer(),
                  // Action Button
                  SizedBox(
                    width: double.infinity,
                    height: 56,
                    child: ElevatedButton(
                      onPressed: () {
                        context.push('/login');
                      },
                      style: ElevatedButton.styleFrom(
                        elevation: 10,
                        shadowColor: Theme.of(context).colorScheme.primary.withValues(alpha: 0.5),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                        ),
                      ),
                      child: const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text('Get Started'),
                          SizedBox(width: 8),
                          Icon(Icons.arrow_forward_rounded),
                        ],
                      ),
                    ),
                  ).animate().fadeIn(delay: 400.ms).moveY(begin: 20, end: 0),
                          const SizedBox(height: 32),
                          ],
                        ),
                      ),
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
