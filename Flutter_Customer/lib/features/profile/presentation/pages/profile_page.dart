import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../../auth/presentation/providers/auth_provider.dart';
import 'package:flutter_customer/features/freelancer/bookings/presentation/providers/booking_provider.dart';
import 'package:flutter_customer/core/widgets/custom_avatar.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';

class ProfilePage extends StatefulWidget {
  const ProfilePage({super.key});

  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  String _getValidUrl(String? url) {
    if (url == null || url.isEmpty || url == 'default') {
      return 'https://placehold.co/400x300?text=No+Image';
    }
    if (url.startsWith('http') || url.startsWith('assets')) return url;
    
    String cleanPath = url.startsWith('/') ? url.substring(1) : url;
    
    if (cleanPath.startsWith('storage/')) {
      return '${ApiConstants.baseUrl}/$cleanPath';
    }
    
    return '${ApiConstants.baseUrl}/storage/$cleanPath';
  }

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<BookingProvider>().loadBookings();
    });
  }

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user;
    final bookingProvider = context.watch<BookingProvider>();
    final totalBookings = bookingProvider.activeBookings.length + 
                          bookingProvider.completedBookings.length + 
                          bookingProvider.cancelledBookings.length;

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: SingleChildScrollView(
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.fromLTRB(24, 60, 24, 40),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: const BorderRadius.vertical(bottom: Radius.circular(40)),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.05),
                    blurRadius: 20,
                    offset: const Offset(0, 10),
                  ),
                ],
              ),
              child: Column(
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(4),
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.2), width: 2),
                        ),
                        child: CustomAvatar(
                          imageUrl: _getValidUrl(user?.profileImage),
                          name: user?.name,
                          size: 80,
                        ),
                      ),
                      const SizedBox(width: 20),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              user?.name ?? 'User Name',
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 24,
                                fontWeight: FontWeight.bold,
                                color: const Color(0xFF1E293B),
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              user?.email ?? 'user@example.com',
                              style: GoogleFonts.plusJakartaSans(
                                color: const Color(0xFF64748B),
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                            const SizedBox(height: 12),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                              decoration: BoxDecoration(
                                color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
                                borderRadius: BorderRadius.circular(20),
                              ),
                              child: Text(
                                'Gold Member',
                                style: GoogleFonts.plusJakartaSans(
                                  color: Theme.of(context).colorScheme.primary,
                                  fontSize: 12,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      IconButton(
                        onPressed: () => context.push('/edit-profile'),
                        style: IconButton.styleFrom(
                          backgroundColor: const Color(0xFFF1F5F9),
                          padding: const EdgeInsets.all(12),
                        ),
                        icon: const Icon(Icons.edit_rounded, color: Color(0xFF64748B)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 32),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      _buildStatItem('Orders', totalBookings.toString()),
                      _buildStatItem('Reviews', '0'),
                      _buildStatItem('Favorites', '12'),
                    ],
                  ),
                ],
              ),
            ).animate().slideY(begin: -0.2).fadeIn(),
            Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Account Settings',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                  const SizedBox(height: 16),
                  _buildMenuSection([
                    _buildMenuItem(context, Icons.person_outline_rounded, 'Personal Information', () => context.push('/edit-profile')),
                    _buildMenuItem(context, Icons.notifications_outlined, 'Notifications', () => context.push('/notifications')),
                    _buildMenuItem(context, Icons.payment_outlined, 'Payment Methods', () {}),
                    _buildMenuItem(context, Icons.security_outlined, 'Security', () {}),
                  ]),
                  const SizedBox(height: 32),
                  Text(
                    'Support',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                  const SizedBox(height: 16),
                  _buildMenuSection([
                    _buildMenuItem(context, Icons.help_outline_rounded, 'Help Center', () {}),
                    _buildMenuItem(context, Icons.info_outline_rounded, 'Terms & Privacy', () {}),
                    _buildMenuItem(context, Icons.logout_rounded, 'Log Out', () {
                      context.read<AuthProvider>().logout();
                    }, isDestructive: true),
                  ]),
                  const SizedBox(height: 100), // Spacing for bottom nav
                ],
              ).animate().slideY(begin: 0.2).fadeIn(delay: 200.ms),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStatItem(String label, String value) {
    return Column(
      children: [
        Text(
          value,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 24,
            fontWeight: FontWeight.bold,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 4),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            fontWeight: FontWeight.w500,
            color: const Color(0xFF94A3B8),
          ),
        ),
      ],
    );
  }

  Widget _buildMenuSection(List<Widget> items) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.02),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        children: items,
      ),
    );
  }

  Widget _buildMenuItem(BuildContext context, IconData icon, String title, VoidCallback onTap, {bool isDestructive = false}) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(24),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: isDestructive 
                      ? Colors.red.withValues(alpha: 0.1) 
                      : const Color(0xFFF1F5F9),
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  icon,
                  size: 20,
                  color: isDestructive ? Colors.red : const Color(0xFF64748B),
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Text(
                  title,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                    color: isDestructive ? Colors.red : const Color(0xFF1E293B),
                  ),
                ),
              ),
              Icon(
                Icons.chevron_right_rounded,
                color: Colors.grey[400],
                size: 20,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
