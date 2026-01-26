import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../auth/presentation/providers/auth_provider.dart';

class SplashPage extends StatefulWidget {
  const SplashPage({super.key});

  @override
  State<SplashPage> createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage> {
  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  Future<void> _checkAuth() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    
    // Start fetching profile immediately if we have a token (even before delay ends)
    if (authProvider.isAuthenticated) {
      await authProvider.fetchUserProfile();
    }

    await Future.delayed(const Duration(seconds: 3)); // Smooth splash delay
    if (!mounted) return;

    if (authProvider.isAuthenticated) {
      final rule = authProvider.user?.serviceRule;
      if (rule == 'freelancer') {
        context.go('/freelancer-home');
      } else if (rule == 'local_service') {
        context.go('/home');
      } else {
        context.go('/service-rule-selection');
      }
    } else {
      context.go('/intro');
    }
  }

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    final theme = Theme.of(context);
    SystemChrome.setSystemUIOverlayStyle(SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: theme.brightness == Brightness.dark ? Brightness.light : Brightness.dark,
      systemNavigationBarColor: theme.scaffoldBackgroundColor,
      systemNavigationBarIconBrightness: theme.brightness == Brightness.dark ? Brightness.light : Brightness.dark,
      systemNavigationBarDividerColor: Colors.transparent,
    ));
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          color: scheme.primary, // Use solid primary color to verify visibility
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              scheme.primary,
              scheme.tertiary,
            ],
          ),
        ),
        child: Stack(
          children: [
            Center(
               child: Column(
                 mainAxisAlignment: MainAxisAlignment.center,
                 children: [
                   const Icon(Icons.rocket_launch_rounded, size: 80, color: Colors.white),
                   const SizedBox(height: 20),
                   const CircularProgressIndicator(color: Colors.white),
                 ],
               ),
            ),
            // Background Elements
            Positioned(
              top: -100,
              right: -100,
              child: Container(
                width: 400,
                height: 400,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  gradient: RadialGradient(
                    colors: [
                      scheme.primary.withOpacity(0.18),
                      Colors.transparent,
                    ],
                  ),
                ),
              ),
            ),
            Positioned(
              bottom: -100,
              left: -100,
              child: Container(
                width: 400,
                height: 400,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  gradient: RadialGradient(
                    colors: [
                      scheme.tertiary.withOpacity(0.12),
                      Colors.transparent,
                    ],
                  ),
                ),
              ),
            ),
            
            // Main Content
            Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  // Logo Container
                  Container(
                    padding: const EdgeInsets.all(32),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.06),
                      shape: BoxShape.circle,
                      border: Border.all(
                        color: Colors.white.withOpacity(0.12),
                        width: 1,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: scheme.primary.withOpacity(0.22),
                          blurRadius: 40,
                          offset: const Offset(0, 10),
                        ),
                      ],
                    ),
                    child: Container(
                      padding: const EdgeInsets.all(24),
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        gradient: LinearGradient(
                          begin: Alignment.topLeft,
                          end: Alignment.bottomRight,
                          colors: [
                            scheme.primary,
                            scheme.secondary,
                          ],
                        ),
                        boxShadow: [
                          BoxShadow(
                            color: scheme.primary.withOpacity(0.35),
                            blurRadius: 20,
                            offset: const Offset(0, 8),
                          ),
                        ],
                      ),
                      child: const Icon(
                        Icons.auto_awesome,
                        size: 56,
                        color: Colors.white,
                      ),
                    ),
                  )
                  .animate()
                  .scale(
                      duration: 800.ms,
                      curve: Curves.elasticOut,
                      begin: const Offset(0.5, 0.5))
                  .then()
                  .shimmer(duration: 1500.ms, color: Colors.white.withOpacity(0.3))
                  .animate(onPlay: (controller) => controller.repeat(reverse: true))
                  .boxShadow(
                    begin: const BoxShadow(color: Colors.transparent),
                    end: BoxShadow(
                      color: const Color(0xFF6366F1).withOpacity(0.3),
                      blurRadius: 30,
                      spreadRadius: 2,
                    ),
                    duration: 2000.ms,
                  ),
                  
                  const SizedBox(height: 40),
                  
                  // App Name
                  Text(
                    'AI Local Services',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 32,
                      fontWeight: FontWeight.bold,
                      color: scheme.onBackground,
                      letterSpacing: -0.5,
                    ),
                  )
                  .animate()
                  .fadeIn(duration: 600.ms, delay: 400.ms)
                  .moveY(begin: 20, end: 0, duration: 600.ms, curve: Curves.easeOutBack),
                  
                  const SizedBox(height: 12),
                  
                  // Tagline
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    decoration: BoxDecoration(
                      color: scheme.onBackground.withOpacity(0.06),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(
                        color: scheme.onBackground.withOpacity(0.08),
                      ),
                    ),
                    child: Text(
                      'Premium Service Marketplace',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 14,
                        color: scheme.onBackground.withOpacity(0.7),
                        fontWeight: FontWeight.w500,
                        letterSpacing: 0.5,
                      ),
                    ),
                  )
                  .animate()
                  .fadeIn(duration: 600.ms, delay: 600.ms)
                  .moveY(begin: 20, end: 0, duration: 600.ms, curve: Curves.easeOutBack),
                ],
              ),
            ),
            
            // Bottom Loading Indicator
            Positioned(
              bottom: 60,
              left: 0,
              right: 0,
              child: Center(
                child: SizedBox(
                  width: 24,
                  height: 24,
                  child: CircularProgressIndicator(
                    strokeWidth: 2,
                    valueColor: AlwaysStoppedAnimation<Color>(
                      scheme.onBackground.withOpacity(0.5),
                    ),
                  ),
                ),
              ).animate().fadeIn(delay: 1000.ms),
            ),
          ],
      ),
    ));
  }
}
