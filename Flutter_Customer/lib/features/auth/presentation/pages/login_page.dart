import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/auth_provider.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (_formKey.currentState!.validate()) {
      final success = await context.read<AuthProvider>().login(
            _emailController.text.trim(),
            _passwordController.text,
          );
      if (success && mounted) {
        final authProvider = context.read<AuthProvider>();
        if (authProvider.user?.serviceRule == null) {
          context.go('/service-rule-selection');
        } else if (authProvider.user?.serviceRule == 'freelancer') {
          context.go('/freelancer-home');
        } else {
          context.go('/home');
        }
      } else if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(context.read<AuthProvider>().error ?? 'Login failed'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  Future<void> _googleLogin() async {
    // TODO: Implement Google Login Logic
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Google Login not implemented yet')),
    );
  }

  @override
  Widget build(BuildContext context) {
    final isLoading = context.watch<AuthProvider>().isLoading;

    final theme = Theme.of(context);
    SystemChrome.setSystemUIOverlayStyle(SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: theme.brightness == Brightness.dark ? Brightness.light : Brightness.dark,
      systemNavigationBarColor: theme.scaffoldBackgroundColor,
      systemNavigationBarIconBrightness: theme.brightness == Brightness.dark ? Brightness.light : Brightness.dark,
      systemNavigationBarDividerColor: Colors.transparent,
    ));
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: Stack(
        children: [
          // Background Gradient Blobs
          Positioned(
            top: -100,
            right: -100,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                gradient: RadialGradient(
                  colors: [
                    const Color(0xFF6366F1).withOpacity(0.2),
                    Colors.transparent,
                  ],
                ),
              ),
            ).animate(onPlay: (c) => c.repeat(reverse: true))
             .moveX(begin: 0, end: 10, duration: 3000.ms, curve: Curves.easeInOut)
             .moveY(begin: 0, end: -8, duration: 2800.ms, curve: Curves.easeInOut)
             .scale(begin: const Offset(1.0, 1.0), end: const Offset(1.03, 1.03), duration: 3200.ms),
          ),
          Positioned(
            bottom: -50,
            left: -50,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                gradient: RadialGradient(
                  colors: [
                    const Color(0xFFFF4757).withOpacity(0.15),
                    Colors.transparent,
                  ],
                ),
              ),
            ).animate(onPlay: (c) => c.repeat(reverse: true))
             .moveX(begin: 0, end: -10, duration: 3000.ms, curve: Curves.easeInOut)
             .moveY(begin: 0, end: 8, duration: 2800.ms, curve: Curves.easeInOut)
             .scale(begin: const Offset(1.0, 1.0), end: const Offset(1.03, 1.03), duration: 3200.ms),
          ),

          SafeArea(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  IconButton(
                    icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Color(0xFF1E293B)),
                    onPressed: () => context.pop(),
                  ),
                  const SizedBox(height: 40),
                  
                  // Header
                  Text(
                    'Welcome Back',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 32,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1E293B),
                      letterSpacing: -1,
                    ),
                  ).animate().fadeIn().moveX(begin: -20, end: 0),
                  const SizedBox(height: 8),
                  Text(
                    'Sign in to access your account',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16,
                      color: const Color(0xFF64748B),
                      fontWeight: FontWeight.w500,
                    ),
                  ).animate().fadeIn(delay: 200.ms).moveX(begin: -20, end: 0),
                  
                  const SizedBox(height: 48),

                  // Form
                  Container(
                    padding: const EdgeInsets.all(32),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFF6366F1).withOpacity(0.08),
                          blurRadius: 24,
                          offset: const Offset(0, 8),
                        ),
                      ],
                    ),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        children: [
                          // Email Field
                          TextFormField(
                            controller: _emailController,
                            keyboardType: TextInputType.emailAddress,
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.w600,
                              color: const Color(0xFF1E293B),
                            ),
                            decoration: InputDecoration(
                              labelText: 'Email Address',
                              labelStyle: GoogleFonts.plusJakartaSans(
                                color: const Color(0xFF94A3B8),
                                fontWeight: FontWeight.w500,
                              ),
                              prefixIcon: const Icon(Icons.email_outlined, color: Color(0xFF94A3B8)),
                              enabledBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
                              ),
                              focusedBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFF6366F1), width: 2),
                              ),
                              errorBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFFFF4757)),
                              ),
                              focusedErrorBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFFFF4757), width: 2),
                              ),
                              filled: true,
                              fillColor: const Color(0xFFF8FAFC),
                              contentPadding: const EdgeInsets.symmetric(vertical: 16, horizontal: 20),
                            ),
                            validator: (value) {
                              if (value == null || value.isEmpty) {
                                return 'Please enter your email';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 24),
                          
                          // Password Field
                          TextFormField(
                            controller: _passwordController,
                            obscureText: _obscurePassword,
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.w600,
                              color: const Color(0xFF1E293B),
                            ),
                            decoration: InputDecoration(
                              labelText: 'Password',
                              labelStyle: GoogleFonts.plusJakartaSans(
                                color: const Color(0xFF94A3B8),
                                fontWeight: FontWeight.w500,
                              ),
                              prefixIcon: const Icon(Icons.lock_outline_rounded, color: Color(0xFF94A3B8)),
                              suffixIcon: IconButton(
                                icon: Icon(
                                  _obscurePassword
                                      ? Icons.visibility_outlined
                                      : Icons.visibility_off_outlined,
                                  color: const Color(0xFF94A3B8),
                                ),
                                onPressed: () {
                                  setState(() {
                                    _obscurePassword = !_obscurePassword;
                                  });
                                },
                              ),
                              enabledBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
                              ),
                              focusedBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFF6366F1), width: 2),
                              ),
                              errorBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFFFF4757)),
                              ),
                              focusedErrorBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(16),
                                borderSide: const BorderSide(color: Color(0xFFFF4757), width: 2),
                              ),
                              filled: true,
                              fillColor: const Color(0xFFF8FAFC),
                              contentPadding: const EdgeInsets.symmetric(vertical: 16, horizontal: 20),
                            ),
                            validator: (value) {
                              if (value == null || value.isEmpty) {
                                return 'Please enter your password';
                              }
                              return null;
                            },
                          ),
                          const SizedBox(height: 32),
                          
                          // Login Button
                          SizedBox(
                            width: double.infinity,
                            height: 56,
                            child: ElevatedButton(
                              onPressed: isLoading ? null : _login,
                              style: ElevatedButton.styleFrom(
                                backgroundColor: const Color(0xFF1E293B),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(16),
                                ),
                                elevation: 0,
                                shadowColor: Colors.transparent,
                              ),
                              child: isLoading
                                  ? const SizedBox(
                                      height: 24,
                                      width: 24,
                                      child: CircularProgressIndicator(
                                        color: Colors.white,
                                        strokeWidth: 2,
                                      ),
                                    )
                                  : Text(
                                      'Sign In',
                                      style: GoogleFonts.plusJakartaSans(
                                        fontSize: 16,
                                        fontWeight: FontWeight.bold,
                                        color: Colors.white,
                                      ),
                                    ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ).animate().fadeIn(delay: 400.ms).moveY(begin: 40, end: 0)
                   .then().animate(onPlay: (c) => c.repeat(reverse: true))
                   .moveY(begin: 0, end: -6, duration: 2800.ms, curve: Curves.easeInOut),
                  
                  const SizedBox(height: 32),
                  
                  // Divider
                  Row(
                    children: [
                      Expanded(child: Divider(color: Colors.grey[300])),
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        child: Text(
                          'Or continue with',
                          style: GoogleFonts.plusJakartaSans(
                            color: const Color(0xFF94A3B8),
                            fontSize: 14,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                      ),
                      Expanded(child: Divider(color: Colors.grey[300])),
                    ],
                  ).animate().fadeIn(delay: 500.ms),
                  
                  const SizedBox(height: 32),
                  
                  // Google Login Button
                  SizedBox(
                    width: double.infinity,
                    height: 56,
                    child: OutlinedButton(
                      onPressed: _googleLogin,
                      style: OutlinedButton.styleFrom(
                        side: const BorderSide(color: Color(0xFFE2E8F0), width: 1.5),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(16),
                        ),
                        backgroundColor: Colors.white,
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          // Placeholder for Google Icon (using standard icon if asset missing)
                          // Ideally use an asset image here
                           const Icon(Icons.g_mobiledata_rounded, size: 32, color: Color(0xFFDB4437)),
                           // Image.asset('assets/icons/google.png', height: 24), // Uncomment if asset exists
                          const SizedBox(width: 12),
                          Text(
                            'Sign in with Google',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF1E293B),
                            ),
                          ),
                        ],
                      ),
                    ).animate(onPlay: (c) => c.repeat(reverse: true))
                     .shimmer(duration: 2200.ms, color: const Color(0xFFFFFFFF).withOpacity(0.25))
                     .moveY(begin: 0, end: -2, duration: 2600.ms, curve: Curves.easeInOut),
                  ).animate().fadeIn(delay: 600.ms).moveY(begin: 20, end: 0),

                  const SizedBox(height: 40),
                  
                  // Sign Up Link
                  Center(
                    child: TextButton(
                      onPressed: () {
                        context.push('/register');
                      },
                      child: RichText(
                        text: TextSpan(
                          text: "Don't have an account? ",
                          style: GoogleFonts.plusJakartaSans(
                            color: const Color(0xFF64748B),
                            fontSize: 15,
                          ),
                          children: [
                            TextSpan(
                              text: 'Sign Up',
                              style: GoogleFonts.plusJakartaSans(
                                color: const Color(0xFF6366F1),
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ).animate().fadeIn(delay: 800.ms),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
