import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../providers/auth_provider.dart';

class ProviderModeSelectionPage extends StatefulWidget {
  const ProviderModeSelectionPage({super.key});

  @override
  State<ProviderModeSelectionPage> createState() =>
      _ProviderModeSelectionPageState();
}

class _ProviderModeSelectionPageState extends State<ProviderModeSelectionPage> {
  String? _selectedMode;
  bool _isLoading = false;

  Future<void> _continue() async {
    if (_selectedMode == null) return;

    setState(() => _isLoading = true);

    try {
      final authProvider = context.read<AuthProvider>();
      final success = await authProvider.updateProviderMode(_selectedMode!);

      if (success && mounted) {
        context.go('/');
      } else if (mounted) {
        final error = authProvider.error ?? 'Failed to update mode';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(error, style: GoogleFonts.plusJakartaSans()),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(e.toString(), style: GoogleFonts.plusJakartaSans()),
            backgroundColor: Colors.red,
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 20),
              Text(
                'Choose Your Path',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF1E293B),
                ),
              ).animate().fadeIn().slideY(begin: 0.5, end: 0),
              const SizedBox(height: 8),
              Text(
                'How do you want to offer your services?',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 16,
                  color: Colors.grey[600],
                ),
              ).animate().fadeIn().slideY(begin: 0.5, end: 0, delay: 100.ms),
              const SizedBox(height: 40),

              Expanded(
                child: Column(
                  children: [
                    _buildModeCard(
                      id: 'freelancer',
                      title: 'Freelancer',
                      description:
                          'I offer digital services remotely (e.g. Design, Development, Writing)',
                      icon: Icons.computer_rounded,
                      color: Colors.blue,
                      delay: 200,
                    ),
                    const SizedBox(height: 20),
                    _buildModeCard(
                      id: 'local_service',
                      title: 'Local Service Provider',
                      description:
                          'I offer physical services in person (e.g. Cleaning, Repair, Moving)',
                      icon: Icons.location_on_rounded,
                      color: Colors.orange,
                      delay: 300,
                    ),
                  ],
                ),
              ),

              SizedBox(
                width: double.infinity,
                height: 56,
                child: ElevatedButton(
                  onPressed: _selectedMode == null || _isLoading
                      ? null
                      : _continue,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Theme.of(context).primaryColor,
                    foregroundColor: Colors.white,
                    elevation: 10,
                    shadowColor: Theme.of(
                      context,
                    ).primaryColor.withOpacity(0.4),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                    ),
                  ),
                  child: _isLoading
                      ? const SizedBox(
                          height: 24,
                          width: 24,
                          child: CircularProgressIndicator(
                            color: Colors.white,
                            strokeWidth: 2,
                          ),
                        )
                      : Text(
                          'Continue',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                ),
              ).animate().fadeIn().slideY(begin: 0.5, end: 0, delay: 400.ms),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildModeCard({
    required String id,
    required String title,
    required String description,
    required IconData icon,
    required MaterialColor color,
    required int delay,
  }) {
    final isSelected = _selectedMode == id;

    return GestureDetector(
      onTap: () => setState(() => _selectedMode = id),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: isSelected ? color.shade50 : Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(
            color: isSelected ? color : Colors.grey.shade200,
            width: isSelected ? 2 : 1,
          ),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: color.withOpacity(0.2),
                    blurRadius: 20,
                    offset: const Offset(0, 4),
                  ),
                ]
              : [
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
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: isSelected ? color : color.shade50,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(
                icon,
                color: isSelected ? Colors.white : color,
                size: 28,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    description,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 13,
                      color: Colors.grey[600],
                      height: 1.4,
                    ),
                  ),
                ],
              ),
            ),
            if (isSelected)
              Container(
                padding: const EdgeInsets.all(4),
                decoration: BoxDecoration(color: color, shape: BoxShape.circle),
                child: const Icon(Icons.check, color: Colors.white, size: 16),
              ),
          ],
        ),
      ),
    ).animate().fadeIn().slideY(begin: 0.5, end: 0, delay: delay.ms);
  }
}
