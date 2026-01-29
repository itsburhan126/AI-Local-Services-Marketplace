import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter_customer/core/widgets/custom_avatar.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';

class AllReviewsBottomSheet extends StatelessWidget {
  final List<dynamic> reviews;
  final double avgRating;

  const AllReviewsBottomSheet({
    super.key,
    required this.reviews,
    required this.avgRating,
  });

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
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.85,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Column(
        children: [
          // Header
          Padding(
            padding: const EdgeInsets.all(24),
            child: Row(
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'All Reviews',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF0F172A),
                      ),
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        Icon(Icons.star_rounded, color: Colors.amber[400], size: 20),
                        const SizedBox(width: 4),
                        Text(
                          avgRating.toStringAsFixed(1),
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                            color: const Color(0xFF0F172A),
                          ),
                        ),
                        Text(
                          ' (${reviews.length} reviews)',
                          style: GoogleFonts.plusJakartaSans(
                            color: Colors.grey[500],
                            fontSize: 14,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                const Spacer(),
                IconButton(
                  onPressed: () => Navigator.pop(context),
                  icon: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: Colors.grey[100],
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.close, size: 20),
                  ),
                ),
              ],
            ),
          ),
          const Divider(height: 1),
          
          // List
          Expanded(
            child: reviews.isEmpty
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.rate_review_outlined, size: 48, color: Colors.grey[300]),
                        const SizedBox(height: 16),
                        Text(
                          'No reviews yet',
                          style: GoogleFonts.plusJakartaSans(
                            color: Colors.grey[500],
                            fontSize: 16,
                          ),
                        ),
                      ],
                    ),
                  )
                : ListView.separated(
                    padding: const EdgeInsets.all(24),
                    itemCount: reviews.length,
                    separatorBuilder: (context, index) => const Divider(height: 32),
                    itemBuilder: (context, index) {
                      final review = reviews[index];
                      return _buildReviewItem(review);
                    },
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildReviewItem(Map<String, dynamic> review) {
    // Name Resolution Logic
    String reviewerName = 'Anonymous';
    final user = review['user']; // Assuming user object might exist
    
    if (user != null) {
      final firstName = user['first_name'] ?? '';
      final lastName = user['last_name'] ?? '';
      final fullName = '$firstName $lastName'.trim();
      
      if (fullName.isNotEmpty) {
        reviewerName = fullName;
      } else if (user['name'] != null) {
        reviewerName = user['name'];
      }
    } else if (review['reviewer_name'] != null) {
      reviewerName = review['reviewer_name'];
    }

    final reviewText = review['review'] ?? '';
    // final reviewRating = (review['rating'] as num?)?.toDouble() ?? 0.0;
    final reviewDate = review['created_at'] != null 
        ? DateFormat('MMM d, yyyy').format(DateTime.parse(review['created_at'])) 
        : '';
    
    // Image Resolution Logic
    String? profileImage;
    if (user != null && user['profile_image'] != null) {
       profileImage = _getValidUrl(user['profile_image']);
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            CustomAvatar(
              imageUrl: profileImage,
              name: reviewerName,
              size: 40,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    reviewerName,
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: const Color(0xFF0F172A),
                    ),
                  ),
                  Row(
                    children: [
                      Icon(Icons.verified, size: 12, color: Colors.blue[400]), // Example badge
                      const SizedBox(width: 4),
                      Text(
                        'Verified User',
                        style: GoogleFonts.plusJakartaSans(
                          color: Colors.grey[500],
                          fontSize: 12,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            Text(
              reviewDate,
              style: GoogleFonts.plusJakartaSans(
                color: Colors.grey[400],
                fontSize: 12,
              ),
            ),
          ],
        ),
        if (reviewText.isNotEmpty) ...[
          const SizedBox(height: 12),
          Text(
            reviewText,
            style: GoogleFonts.plusJakartaSans(
              color: const Color(0xFF334155),
              fontSize: 14,
              height: 1.5,
            ),
          ),
        ],
      ],
    );
  }
}
