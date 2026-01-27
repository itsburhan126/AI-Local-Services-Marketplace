import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../providers/booking_provider.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';

class RatingPage extends StatefulWidget {
  final String orderId;
  final Map<String, dynamic>? bookingData;

  const RatingPage({
    super.key,
    required this.orderId,
    this.bookingData,
  });

  @override
  State<RatingPage> createState() => _RatingPageState();
}

class _RatingPageState extends State<RatingPage> {
  int _rating = 0;
  final TextEditingController _reviewController = TextEditingController();
  bool _isSubmitting = false;

  @override
  void dispose() {
    _reviewController.dispose();
    super.dispose();
  }

  void _submitRating() async {
    if (_rating == 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Please select a star rating'),
          backgroundColor: Colors.orange,
        ),
      );
      return;
    }

    setState(() {
      _isSubmitting = true;
    });

    final provider = context.read<BookingProvider>();
    final success = await provider.submitReview(
      widget.orderId,
      _rating,
      _reviewController.text,
    );

    if (mounted) {
      setState(() {
        _isSubmitting = false;
      });

      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Thank you for your feedback!'),
            backgroundColor: Colors.green,
          ),
        );
        // Navigate back to bookings or home
        if (context.canPop()) {
           context.pop(); // Pop RatingPage
           // Ideally we should go back to main screen to avoid back-stack issues
           context.go('/bookings'); 
        } else {
           context.go('/bookings');
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(provider.error ?? 'Failed to submit review'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  void _skipRating() {
    // Navigate back or to bookings
    context.go('/bookings');
  }

  @override
  Widget build(BuildContext context) {
    final serviceName = widget.bookingData?['service_name'] ?? widget.bookingData?['name'] ?? 'Service';
    final providerName = widget.bookingData?['provider_name'] ?? widget.bookingData?['provider']?['name'] ?? 'Provider';
    String? imageUrl = widget.bookingData?['image'];
    
    if (imageUrl != null && !imageUrl.startsWith('http')) {
      imageUrl = '${ApiConstants.baseUrl}/$imageUrl'.replaceAll('//', '/').replaceFirst('http:/', 'http://').replaceFirst('https:/', 'https://');
    }

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.close, color: Colors.grey),
          onPressed: _skipRating,
        ),
        actions: [
          TextButton(
            onPressed: _skipRating,
            child: Text(
              'Skip',
              style: GoogleFonts.plusJakartaSans(
                color: const Color(0xFF64748B),
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            const SizedBox(height: 20),
            
            // Service Image & Info
            Container(
              width: 100,
              height: 100,
              decoration: BoxDecoration(
                color: Colors.grey[100],
                shape: BoxShape.circle,
                border: Border.all(color: Colors.grey[200]!, width: 4),
                image: imageUrl != null
                    ? DecorationImage(
                        image: NetworkImage(imageUrl),
                        fit: BoxFit.cover,
                      )
                    : null,
              ),
              child: imageUrl == null
                  ? const Icon(Icons.cleaning_services, size: 40, color: Colors.grey)
                  : null,
            ).animate().scale(duration: 500.ms, curve: Curves.easeOutBack),
            
            const SizedBox(height: 24),
            
            Text(
              'How was your experience?',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: const Color(0xFF1E293B),
              ),
              textAlign: TextAlign.center,
            ).animate().fadeIn().slideY(begin: 0.3, end: 0),
            
            const SizedBox(height: 8),
            
            Text(
              'Rate $providerName for $serviceName',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 16,
                color: const Color(0xFF64748B),
              ),
              textAlign: TextAlign.center,
            ).animate().fadeIn(delay: 200.ms).slideY(begin: 0.3, end: 0),
            
            const SizedBox(height: 40),
            
            // Star Rating
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(5, (index) {
                return GestureDetector(
                  onTap: () {
                    setState(() {
                      _rating = index + 1;
                    });
                  },
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 4),
                    child: AnimatedScale(
                      scale: _rating > index ? 1.2 : 1.0,
                      duration: const Duration(milliseconds: 200),
                      child: Icon(
                        index < _rating ? Icons.star_rounded : Icons.star_outline_rounded,
                        color: index < _rating ? const Color(0xFFFFB020) : const Color(0xFFCBD5E1),
                        size: 48,
                      ),
                    ),
                  ),
                );
              }),
            ).animate().fadeIn(delay: 400.ms),
            
            const SizedBox(height: 16),
            
            Text(
              _rating == 5 ? 'Excellent!' : 
              _rating == 4 ? 'Good' : 
              _rating == 3 ? 'Average' : 
              _rating == 2 ? 'Below Average' : 
              _rating == 1 ? 'Poor' : 'Select a rating',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: _rating > 0 ? const Color(0xFFFFB020) : const Color(0xFF94A3B8),
              ),
            ),
            
            const SizedBox(height: 40),
            
            // Review Text Field
            Container(
              decoration: BoxDecoration(
                color: const Color(0xFFF8FAFC),
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: TextField(
                controller: _reviewController,
                maxLines: 4,
                decoration: InputDecoration(
                  hintText: 'Share your feedback (optional)...',
                  hintStyle: GoogleFonts.plusJakartaSans(color: const Color(0xFF94A3B8)),
                  border: InputBorder.none,
                  contentPadding: const EdgeInsets.all(16),
                ),
              ),
            ).animate().fadeIn(delay: 600.ms),
            
            const SizedBox(height: 32),
            
            // Submit Button
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _isSubmitting ? null : _submitRating,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF0F172A),
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  elevation: 0,
                ),
                child: _isSubmitting
                    ? const SizedBox(
                        height: 24,
                        width: 24,
                        child: CircularProgressIndicator(
                          color: Colors.white,
                          strokeWidth: 2,
                        ),
                      )
                    : Text(
                        'Submit Review',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
              ),
            ).animate().fadeIn(delay: 800.ms).slideY(begin: 0.5, end: 0),
          ],
        ),
      ),
    );
  }
}
