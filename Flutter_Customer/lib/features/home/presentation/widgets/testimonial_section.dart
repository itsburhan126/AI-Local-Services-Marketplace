import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter_animate/flutter_animate.dart';

class TestimonialsSection extends StatelessWidget {
  final List<dynamic> items;

  const TestimonialsSection({super.key, required this.items});

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) return const SizedBox();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: Text(
            'What our customers say',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: const Color(0xFF0F172A),
              letterSpacing: -0.5,
            ),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          height: 180,
          child: ListView.separated(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            scrollDirection: Axis.horizontal,
            itemCount: items.length,
            separatorBuilder: (_, __) => const SizedBox(width: 16),
            itemBuilder: (context, index) => _buildTestimonialCard(items[index], index),
          ),
        ),
      ],
    );
  }

  Widget _buildTestimonialCard(dynamic item, int index) {
    return Container(
      width: 300,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.04),
            blurRadius: 15,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: const Color(0xFFE2E8F0), width: 2),
                ),
                child: CircleAvatar(
                  radius: 20,
                  backgroundColor: Colors.grey[100],
                  backgroundImage: CachedNetworkImageProvider(
                    item['image'] ?? 'https://i.pravatar.cc/150',
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      item['user'] ?? 'User',
                      style: GoogleFonts.plusJakartaSans(
                        fontWeight: FontWeight.w700,
                        fontSize: 14,
                        color: const Color(0xFF0F172A),
                      ),
                    ),
                    Row(
                      children: List.generate(5, (starIndex) {
                        return Icon(
                          Icons.star_rounded,
                          size: 14,
                          color: starIndex < (item['rating'] ?? 5) 
                              ? const Color(0xFFF59E0B) 
                              : const Color(0xFFE2E8F0),
                        );
                      }),
                    ),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: const Icon(Icons.format_quote_rounded, color: Color(0xFF6366F1), size: 16),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Text(
            '"${item['comment'] ?? ''}"',
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 13,
              height: 1.5,
              color: const Color(0xFF475569),
              fontStyle: FontStyle.italic,
            ),
          ),
          const Spacer(),
          Text(
            'Service: ${item['service'] ?? 'General'}',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 11,
              fontWeight: FontWeight.w600,
              color: const Color(0xFF94A3B8),
            ),
          ),
        ],
      ),
    ).animate().fadeIn(delay: (index * 100).ms).slideY(begin: 0.1);
  }
}
