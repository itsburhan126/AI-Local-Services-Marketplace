import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../../../auth/data/datasources/auth_remote_data_source.dart';
import '../../../auth/presentation/providers/auth_provider.dart' as app_auth;

class SplashPage extends StatefulWidget {
  const SplashPage({super.key});

  @override
  State<SplashPage> createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage> {
  final _secureStorage = const FlutterSecureStorage();

  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  Future<void> _handleLogout(SharedPreferences prefs) async {
    await _secureStorage.delete(key: 'auth_token');
    await prefs.remove('auth_token');

    if (!mounted) return;

    final hasSeenIntro = prefs.getBool('has_seen_intro') ?? false;
    if (hasSeenIntro) {
      context.go('/welcome');
    } else {
      context.go('/intro');
    }
  }

  Future<void> _checkAuth() async {
    // Minimum splash duration for branding
    await Future.delayed(const Duration(seconds: 2));
    if (!mounted) return;

    final prefs = context.read<SharedPreferences>();
    final token = await _secureStorage.read(key: 'auth_token');

    if (token != null) {
      try {
        // Verify token and role with API
        // Use the globally configured AuthRemoteDataSource instead of creating a new one
        final authDataSource = context.read<AuthRemoteDataSource>();
        final user = await authDataSource.getUserProfile(token);

        if (mounted) {
          if (user.role == 'provider') {
            debugPrint(
              'Splash: User loaded. Mode: ${user.mode}, ServiceRule: ${user.serviceRule}',
            );
            context.read<app_auth.AuthProvider>().setUser(user);

            // Persist critical state to SharedPreferences for resilience
            if (user.mode != null) {
              await prefs.setString('user_mode', user.mode!);
            }
            if (user.serviceRule != null) {
              await prefs.setString('user_service_rule', user.serviceRule!);
            }

            // If provider hasn't selected a mode (service_rule) yet, force them to select it
            // Check both mode and serviceRule to be robust
            if ((user.mode == null || user.mode!.isEmpty) &&
                (user.serviceRule == null || user.serviceRule!.isEmpty)) {
              debugPrint('Splash: Redirecting to mode-selection');
              context.go('/mode-selection');
            } else {
              debugPrint('Splash: Redirecting to dashboard');
              context.go('/');
            }
          } else {
            // Invalid role, clear token and go to intro/welcome
            await _handleLogout(prefs);
          }
        }
      } on DioException catch (e) {
        if (mounted) {
          // Check for 401 (Unauthorized) or 404 (User Not Found)
          if (e.response?.statusCode == 401 || e.response?.statusCode == 404) {
            // Token expired, invalid, or user deleted -> Logout
            await _handleLogout(prefs);
          } else {
            // Network error (timeout, 500, etc.) -> Allow offline access or Retry
            // For now, we allow access so they can see cached data if any
            context.go('/');
          }
        }
      } catch (e) {
        // Unknown error -> Safer to logout and force re-login
        if (mounted) {
          await _handleLogout(prefs);
        }
      }
    } else {
      final hasSeenIntro = prefs.getBool('has_seen_intro') ?? false;
      if (mounted) {
        if (hasSeenIntro) {
          context.go('/welcome');
        } else {
          context.go('/intro');
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              Theme.of(context).colorScheme.primary,
              Theme.of(context).colorScheme.secondary,
            ],
          ),
        ),
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  shape: BoxShape.circle,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.1),
                      blurRadius: 20,
                      spreadRadius: 5,
                    ),
                  ],
                ),
                child: Icon(
                  Icons.work_outline,
                  size: 60,
                  color: Theme.of(context).colorScheme.primary,
                ).animate().scale(duration: 600.ms).then().shake(),
              ),
              const SizedBox(height: 24),
              Text(
                'Provider Portal',
                style: Theme.of(context).textTheme.displayMedium?.copyWith(
                  color: Colors.white,
                  fontWeight: FontWeight.bold,
                ),
              ).animate().fadeIn(delay: 300.ms).moveY(begin: 20, end: 0),
              const SizedBox(height: 8),
              Text(
                'Grow Your Service Business',
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                  color: Colors.white.withValues(alpha: 0.9),
                ),
              ).animate().fadeIn(delay: 500.ms),
            ],
          ),
        ),
      ),
    );
  }
}
