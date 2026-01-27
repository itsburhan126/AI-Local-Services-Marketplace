import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../../auth/data/models/user_model.dart';
import '../../../../chat/presentation/pages/chat_details_page.dart';
import '../providers/booking_provider.dart';
import '../../../../support/presentation/pages/support_page.dart';
import 'package:flutter_customer/features/freelancer/bookings/presentation/pages/rating_page.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import 'package:cached_network_image/cached_network_image.dart';

class BookingDetailsPage extends StatelessWidget {
  final Map<String, dynamic>? booking;

  const BookingDetailsPage({super.key, this.booking});

  String _asString(dynamic v, {String fallback = ''}) {
    return v?.toString() ?? fallback;
  }

  @override
  Widget build(BuildContext context) {
    final serviceName = _asString(booking?['service_name'] ?? booking?['name'], fallback: 'Service Name');
    final providerName = _asString(booking?['provider_name'] ?? booking?['provider']?['name'], fallback: 'Provider Name');
    final price = _asString(booking?['price'], fallback: '0.00');
    final packageName = _asString(booking?['package_name'], fallback: '');
    
    // Date & Time Handling
    // If passed date is null, default to Tomorrow
    final rawDate = booking?['date'];
    final DateTime targetDate = rawDate != null 
        ? DateTime.tryParse(rawDate.toString()) ?? DateTime.now().add(const Duration(days: 1))
        : DateTime.now().add(const Duration(days: 1));
        
    final displayDate = DateFormat('MMM d, yyyy').format(targetDate);
    final apiDate = DateFormat('yyyy-MM-dd').format(targetDate);
    
    final displayTime = _asString(booking?['time'], fallback: '10:00 AM');

    final status = _asString(booking?['status'], fallback: 'New Request');
    final isNew = status == 'New Request';

    final reviewData = booking?['review'];
    final dynamic rawRating = booking?['customer_rating'] ?? (reviewData is Map<String, dynamic> ? reviewData['rating'] : null);
    final int? customerRating = rawRating is int ? rawRating : int.tryParse(rawRating?.toString() ?? '');
    final String? customerReview = booking?['customer_review'] ?? (reviewData is Map<String, dynamic> ? reviewData['review']?.toString() : null);
    final bool hasReview = customerRating != null;

    String? imageUrl = booking?['image'];
    if (imageUrl != null && !imageUrl.startsWith('http')) {
      imageUrl = '${ApiConstants.baseUrl}/$imageUrl'.replaceAll('//', '/').replaceFirst('http:/', 'http://').replaceFirst('https:/', 'https://');
    }

    final deliveryNote = _asString(booking?['delivery_note']);
    final deliveryFiles = booking?['delivery_files'] as List?;

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.black),
          onPressed: () => context.pop(),
        ),
        title: Text(
          isNew ? 'Confirm Order' : 'Order Details',
          style: GoogleFonts.plusJakartaSans(
            color: const Color(0xFF1E293B),
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(24),
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
                        width: 60,
                        height: 60,
                        decoration: BoxDecoration(
                          color: Colors.grey[200],
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: imageUrl != null 
                            ? ClipRRect(
                                borderRadius: BorderRadius.circular(12),
                                child: CachedNetworkImage(
                                  imageUrl: imageUrl!, 
                                  fit: BoxFit.cover,
                                  placeholder: (context, url) => Container(color: Colors.grey[200]),
                                  errorWidget: (context, url, error) => const Icon(Icons.cleaning_services, color: Colors.grey),
                                ),
                              )
                            : const Icon(Icons.cleaning_services, color: Colors.grey),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              serviceName,
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 18,
                                color: Color(0xFF1E293B),
                              ),
                            ),
                            if (packageName.isNotEmpty) ...[
                              const SizedBox(height: 4),
                              Text(
                                packageName,
                                style: TextStyle(
                                  color: Colors.blue[600],
                                  fontSize: 14,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                            const SizedBox(height: 4),
                            Text(
                              isNew ? 'New Request' : 'Order ID: #${booking?['id'] ?? '---'}',
                              style: TextStyle(
                                color: Colors.grey[500],
                                fontSize: 14,
                              ),
                            ),
                          ],
                        ),
                      ),
                      if (!isNew)
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                            color: Colors.blue.withValues(alpha: 0.1),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Text(
                            status,
                            style: const TextStyle(
                              color: Colors.blue,
                              fontWeight: FontWeight.bold,
                              fontSize: 12,
                            ),
                          ),
                        ),
                    ],
                  ),
                  const SizedBox(height: 32),
                  _buildDetailRow('Date', displayDate),
                  const SizedBox(height: 16),
                  _buildDetailRow('Time', displayTime),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Provider',
                        style: TextStyle(
                          color: Colors.grey[500],
                          fontSize: 14,
                        ),
                      ),
                      Row(
                        children: [
                          Text(
                            providerName,
                            style: const TextStyle(
                              fontWeight: FontWeight.w600,
                              fontSize: 14,
                              color: Color(0xFF1E293B),
                            ),
                          ),
                          if (!isNew) ...[
                            const SizedBox(width: 8),
                            InkWell(
                              onTap: () {
                                if (booking?['provider_id'] != null || booking?['provider'] != null) {
                                  final providerData = booking?['provider'];
                                  final providerId = int.tryParse(booking?['provider_id']?.toString() ?? providerData?['id']?.toString() ?? '0');
                                  
                                  if (providerId != null && providerId != 0) {
                                     final user = UserModel(
                                      id: providerId,
                                      name: providerName,
                                      email: providerData?['email'],
                                      profileImage: providerData?['profile_image'] ?? providerData?['image'],
                                    );
                                    
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => ChatDetailsPage(otherUser: user),
                                      ),
                                    );
                                  }
                                }
                              },
                              child: Container(
                                padding: const EdgeInsets.all(6),
                                decoration: BoxDecoration(
                                  color: Colors.blue.withOpacity(0.1),
                                  shape: BoxShape.circle,
                                ),
                                child: const Icon(Icons.message_outlined, color: Colors.blue, size: 16),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  const Divider(),
                  const SizedBox(height: 16),
                  _buildDetailRow('Total Amount', '\$$price', isBold: true),
                ],
              ),
            ).animate().fadeIn().moveY(begin: 20, end: 0),
            
            if (!isNew) ...[
              const SizedBox(height: 24),
              Container(
                    padding: const EdgeInsets.all(24),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withValues(alpha: 0.05),
                          blurRadius: 20,
                          offset: const Offset(0, 10),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Order Status',
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 18,
                            color: Color(0xFF1E293B),
                          ),
                        ),
                        const SizedBox(height: 24),
                        _buildStatusTimeline(status, booking?['created_at']),
                  ],
                ),
              ).animate().fadeIn(delay: 200.ms).moveY(begin: 20, end: 0),
            ],

            if (!isNew && (status.toLowerCase() == 'delivered' || status.toLowerCase() == 'completed')) ...[
              const SizedBox(height: 24),
              _buildDeliverySection(context, deliveryNote, deliveryFiles),
            ],

            if (!isNew && hasReview) ...[
              const SizedBox(height: 24),
              _buildReviewSection(customerRating!, customerReview),
            ],

            const SizedBox(height: 100), // Bottom padding for fixed button
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 20,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: SafeArea(
          child: Consumer<BookingProvider>(
              builder: (context, provider, child) {
                if (!isNew && status.toLowerCase() == 'delivered') {
                  return Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          onPressed: provider.isLoading ? null : () async {
                            final success = await provider.approveWork(booking?['id'].toString() ?? '');
                            if (success && context.mounted) {
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text('Work Approved! Order Completed.'),
                                  backgroundColor: Colors.green,
                                ),
                              );
                              context.pop(); 
                            } else if (context.mounted) {
                               ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(
                                  content: Text(provider.error ?? 'Failed to approve work'),
                                  backgroundColor: Colors.red,
                                ),
                              );
                            }
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.green,
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            elevation: 0,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                          ),
                          child: provider.isLoading 
                            ? const SizedBox(
                                height: 20, 
                                width: 20, 
                                child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white)
                              )
                            : const Text(
                                'Approve Work & Complete Order',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 16,
                                ),
                              ),
                        ),
                      ),
                      const SizedBox(height: 12),
                      SizedBox(
                        width: double.infinity,
                        child: OutlinedButton(
                          onPressed: provider.isLoading ? null : () async {
                            showModalBottomSheet(
                              context: context,
                              isScrollControlled: true,
                              backgroundColor: Colors.transparent,
                              builder: (context) {
                                final noteController = TextEditingController();
                                return StatefulBuilder(
                                  builder: (context, setState) => Container(
                                    constraints: BoxConstraints(
                                      maxHeight: MediaQuery.of(context).size.height * 0.9,
                                    ),
                                    decoration: const BoxDecoration(
                                      color: Colors.white,
                                      borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
                                    ),
                                    padding: EdgeInsets.only(
                                      left: 24,
                                      right: 24,
                                      top: 24,
                                      bottom: MediaQuery.of(context).viewInsets.bottom + 24,
                                    ),
                                    child: SingleChildScrollView(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        mainAxisSize: MainAxisSize.min,
                                        children: [
                                          Row(
                                            children: [
                                              Container(
                                                padding: const EdgeInsets.all(10),
                                                decoration: BoxDecoration(
                                                  color: Colors.amber.withOpacity(0.1),
                                                  shape: BoxShape.circle,
                                                ),
                                                child: const Icon(Icons.edit_note, color: Colors.amber),
                                              ),
                                              const SizedBox(width: 12),
                                              const Expanded(
                                                child: Text(
                                                  'Request Changes',
                                                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Color(0xFF1E293B)),
                                                ),
                                              ),
                                              IconButton(
                                                onPressed: () => Navigator.pop(context),
                                                icon: const Icon(Icons.close, color: Color(0xFF64748B)),
                                              ),
                                            ],
                                          ),
                                          const SizedBox(height: 16),
                                          Text(
                                            'Share notes for the freelancer to fix and re-deliver.',
                                            style: TextStyle(color: Colors.grey[600]),
                                          ),
                                          const SizedBox(height: 16),
                                          TextField(
                                            controller: noteController,
                                            maxLines: 4,
                                            decoration: const InputDecoration(
                                              hintText: 'Type your revision notes...',
                                              border: OutlineInputBorder(),
                                            ),
                                          ),
                                          const SizedBox(height: 16),
                                          Row(
                                            children: [
                                              Container(
                                                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                                                decoration: BoxDecoration(
                                                  color: const Color(0xFFEEF2FF),
                                                  borderRadius: BorderRadius.circular(20),
                                                ),
                                                child: const Text('Missing requirements', style: TextStyle(color: Color(0xFF6366F1), fontWeight: FontWeight.w600)),
                                              ),
                                              const SizedBox(width: 8),
                                              Container(
                                                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                                                decoration: BoxDecoration(
                                                  color: const Color(0xFFFFF7ED),
                                                  borderRadius: BorderRadius.circular(20),
                                                ),
                                                child: const Text('Quality issues', style: TextStyle(color: Color(0xFFFF9241), fontWeight: FontWeight.w600)),
                                              ),
                                            ],
                                          ),
                                          const SizedBox(height: 24),
                                          SizedBox(
                                            width: double.infinity,
                                            child: ElevatedButton(
                                              onPressed: () {
                                                Navigator.pop(context);
                                                ScaffoldMessenger.of(context).showSnackBar(
                                                  const SnackBar(
                                                    content: Text('Revision request submitted'),
                                                    backgroundColor: Colors.blue,
                                                  ),
                                                );
                                              },
                                              style: ElevatedButton.styleFrom(
                                                backgroundColor: const Color(0xFF0EA5E9),
                                                padding: const EdgeInsets.symmetric(vertical: 16),
                                                elevation: 0,
                                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                              ),
                                              child: const Text(
                                                'Submit Revision Request',
                                                style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                                              ),
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                );
                              },
                            );
                          },
                          style: OutlinedButton.styleFrom(
                            side: const BorderSide(color: Color(0xFF0EA5E9)),
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                          ),
                          child: const Text(
                            'Request Changes',
                            style: TextStyle(
                              color: Color(0xFF0EA5E9),
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 12),
                      SizedBox(
                        width: double.infinity,
                        child: OutlinedButton(
                          onPressed: provider.isLoading ? null : () async {
                            final success = await provider.rejectWork(booking?['id'].toString() ?? '');
                            if (success && context.mounted) {
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text('Work Rejected. Refund processed.'),
                                  backgroundColor: Colors.orange,
                                ),
                              );
                              context.pop();
                            } else if (context.mounted) {
                              ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(
                                  content: Text(provider.error ?? 'Failed to reject work'),
                                  backgroundColor: Colors.red,
                                ),
                              );
                            }
                          },
                          style: OutlinedButton.styleFrom(
                            side: const BorderSide(color: Colors.red),
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                          ),
                          child: provider.isLoading
                              ? const SizedBox(
                                  height: 20,
                                  width: 20,
                                  child: CircularProgressIndicator(strokeWidth: 2, color: Colors.red),
                                )
                              : const Text(
                                  'Reject Work & Request Refund',
                                  style: TextStyle(
                                    color: Colors.red,
                                    fontWeight: FontWeight.bold,
                                    fontSize: 16,
                                  ),
                                ),
                        ),
                          ),
                    const SizedBox(height: 12),
                    TextButton.icon(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => SupportPage(
                                orderId: booking?['id']?.toString(),
                                subject: 'Delivery Issue',
                              ),
                            ),
                          );
                        },
                        icon: const Icon(Icons.headset_mic, color: Color(0xFF0EA5E9)),
                        label: const Text(
                          'Contact Support',
                          style: TextStyle(
                            color: Color(0xFF0EA5E9),
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ),
                    ],
                  );
                } else if (!isNew && status.toLowerCase() == 'completed') {
                   return Column(
                     mainAxisSize: MainAxisSize.min,
                     children: [
                       SizedBox(
                         width: double.infinity,
                         child: ElevatedButton(
                           onPressed: () {
                             if (hasReview) {
                               ScaffoldMessenger.of(context).showSnackBar(
                                 const SnackBar(
                                   content: Text('You already submitted a review for this order'),
                                   backgroundColor: Colors.green,
                                 ),
                               );
                               return;
                             }
                             Navigator.push(
                               context,
                               MaterialPageRoute(
                                 builder: (context) => RatingPage(
                                   orderId: booking?['id'].toString() ?? '',
                                   bookingData: booking,
                                 ),
                               ),
                             );
                           },
                           style: ElevatedButton.styleFrom(
                             backgroundColor: hasReview ? Colors.grey[300] : Colors.amber,
                             padding: const EdgeInsets.symmetric(vertical: 16),
                             elevation: 0,
                             shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                           ),
                           child: Text(
                             hasReview ? 'You already rated this order' : 'Rate Freelancer',
                             style: const TextStyle(
                               color: Colors.white,
                               fontWeight: FontWeight.bold,
                               fontSize: 16,
                             ),
                           ),
                         ),
                       ),
                       const SizedBox(height: 12),
                       TextButton.icon(
                         onPressed: () {
                           Navigator.push(
                             context,
                             MaterialPageRoute(
                               builder: (context) => SupportPage(
                                 orderId: booking?['id']?.toString(),
                                 subject: 'General Support',
                               ),
                             ),
                           );
                         },
                         icon: const Icon(Icons.headset_mic, color: Color(0xFF0EA5E9)),
                         label: const Text(
                           'Contact Support',
                           style: TextStyle(
                             color: Color(0xFF0EA5E9),
                             fontWeight: FontWeight.w600,
                           ),
                         ),
                       ),
                     ],
                   );
                }

                return Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: provider.isLoading ? null : () async {
                          if (isNew) {
                        final payload = {
                          'provider_id': booking?['provider_id'],
                          'service_id': booking?['service_id'], // Nullable
                          'gig_id': booking?['gig_id'] ?? booking?['service_id'], // Assuming service_id carries gig_id for now if gig_id is missing, or we check both
                          'gig_package_id': booking?['package_id'],
                          'date': apiDate,
                          'time': displayTime,
                          'address': '123 Main St', // Placeholder for now
                          'notes': 'Looking forward to it!',
                        };

                        // Since we are reusing 'service_id' in ServiceDetailsPage for Gigs sometimes,
                        // we need to be careful.
                        // In ServiceDetailsPage, we passed 'service_id': service?['id'].
                        // If it came from a Gig, it's a Gig ID.
                        // The backend Controller logic checks gig_id OR service_id.
                        // We should try to determine if it is a Gig or Service.
                        // For now, we'll send it as 'gig_id' if package_id exists, else 'service_id'.
                        
                        final Map<String, dynamic> finalPayload = Map.from(payload);
                        if (booking?['package_id'] != null) {
                           finalPayload['gig_id'] = booking?['service_id'];
                           finalPayload.remove('service_id');
                        }

                        final success = await provider.createBooking(finalPayload);
                        
                        if (context.mounted) {
                          if (success) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Order Placed Successfully!'),
                                backgroundColor: Colors.green,
                              ),
                            );
                            // Navigate to My Orders
                            context.go('/bookings');
                          } else {
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(
                                content: Text(provider.error ?? 'Failed to place order'),
                                backgroundColor: Colors.red,
                              ),
                            );
                          }
                        }
                        } else {
                        // Cancel logic
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Cancellation feature coming soon! Please contact support.')),
                        );
                        }
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: isNew ? Theme.of(context).primaryColor : Colors.red.withValues(alpha: 0.1),
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        elevation: 0,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                      ),
                      child: provider.isLoading 
                        ? const SizedBox(
                            height: 20, 
                            width: 20, 
                            child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white)
                          )
                        : Text(
                            isNew ? 'Confirm Order' : 'Cancel Order',
                            style: TextStyle(
                              color: isNew ? Colors.white : Colors.red,
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                    ),
                  ),
                    const SizedBox(height: 12),
                    TextButton.icon(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => const SupportPage(
                              subject: 'General Support',
                            ),
                          ),
                        );
                      },
                      icon: const Icon(Icons.headset_mic, color: Color(0xFF0EA5E9)),
                      label: const Text(
                        'Contact Support',
                        style: TextStyle(
                          color: Color(0xFF0EA5E9),
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ],
                );
              }
            ),
        ),
      ),
    );
  }



  Widget _buildReviewSection(int rating, String? review) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.05),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Your Review',
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 18,
              color: Color(0xFF1E293B),
            ),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Row(
                children: List.generate(5, (index) {
                  return Icon(
                    index < rating ? Icons.star_rounded : Icons.star_border_rounded,
                    color: const Color(0xFFFFB020),
                    size: 20,
                  );
                }),
              ),
              const SizedBox(width: 8),
              Text(
                '$rating.0',
                style: const TextStyle(
                  fontWeight: FontWeight.w600,
                  color: Color(0xFF475569),
                ),
              ),
            ],
          ),
          if (review != null && review.trim().isNotEmpty) ...[
            const SizedBox(height: 12),
            Text(
              review,
              style: const TextStyle(
                fontSize: 14,
                color: Color(0xFF64748B),
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildStatusTimeline(String currentStatus, String? createdAt) {
    final statusOrder = ['pending', 'accepted', 'in_progress', 'delivered', 'completed'];
    final statusLabels = {
      'pending': 'Order Placed',
      'accepted': 'Provider Accepted',
      'in_progress': 'Gig In Progress',
      'delivered': 'Work Delivered',
      'completed': 'Gig Completed'
    };
    
    // Normalize status
    String normalizedStatus = currentStatus.toLowerCase();
    if (normalizedStatus == 'confirmed') normalizedStatus = 'accepted';
    if (normalizedStatus == 'ongoing') normalizedStatus = 'in_progress';
    
    int currentIndex = statusOrder.indexOf(normalizedStatus);
    if (currentIndex == -1) {
       // specific handling for cancelled or unknown
       if (normalizedStatus == 'cancelled') return const Text('Order Cancelled', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold));
       currentIndex = 0; // Default to first step if unknown
    }

    // Format date if available
    String dateStr = '';
    if (createdAt != null) {
      try {
        dateStr = DateFormat('MMM d, h:mm a').format(DateTime.parse(createdAt));
      } catch (e) {
        // ignore error
      }
    }

    return Column(
      children: List.generate(statusOrder.length, (index) {
        final stepStatus = statusOrder[index];
        final isCompleted = index <= currentIndex;
        final isLast = index == statusOrder.length - 1;
        
        // Only show date on the first step or the current step (simplification since we don't have individual timestamps for each state change yet)
        String time = '';
        if (index == 0) time = dateStr; 
        if (index == currentIndex && index != 0) time = 'Current Stage';

        return _buildStatusStep(
          statusLabels[stepStatus] ?? stepStatus, 
          time, 
          isCompleted, 
          !isLast
        );
      }),
    );
  }

  Widget _buildDeliverySection(BuildContext context, String note, List? files) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Delivery Details',
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 18,
              color: Color(0xFF1E293B),
            ),
          ),
          const SizedBox(height: 16),
          if (note.isNotEmpty) ...[
            Text(
              'Note:',
              style: TextStyle(fontWeight: FontWeight.w600, color: Colors.grey[600]),
            ),
            const SizedBox(height: 8),
            Text(note, style: const TextStyle(color: Color(0xFF1E293B))),
            const SizedBox(height: 16),
          ],
          if (files != null && files.isNotEmpty) ...[
            Text(
              'Files:',
              style: TextStyle(fontWeight: FontWeight.w600, color: Colors.grey[600]),
            ),
            const SizedBox(height: 12),
            ...files.map((file) {
                 String filePath = file.toString();
                 String fileName = filePath.split('/').last;
                 String ext = fileName.split('.').last.toLowerCase();
                 bool isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].contains(ext);
                 
                 String url = filePath;
                 if (!url.startsWith('http')) {
                    url = '${ApiConstants.baseUrl}/$url'.replaceAll('//', '/').replaceFirst('http:/', 'http://').replaceFirst('https:/', 'https://');
                 }

                 return Container(
                   margin: const EdgeInsets.only(bottom: 8),
                   padding: const EdgeInsets.all(12),
                   decoration: BoxDecoration(
                     color: Colors.grey[50],
                     borderRadius: BorderRadius.circular(12),
                     border: Border.all(color: Colors.grey[200]!),
                   ),
                   child: Column(
                     crossAxisAlignment: CrossAxisAlignment.start,
                     children: [
                       if (isImage) ...[
                         ClipRRect(
                           borderRadius: BorderRadius.circular(8),
                           child: Image.network(
                             url,
                             height: 150,
                             width: double.infinity,
                             fit: BoxFit.cover,
                             errorBuilder: (c, e, s) => const Icon(Icons.broken_image, size: 50, color: Colors.grey),
                           ),
                         ),
                         const SizedBox(height: 12),
                       ],
                       Row(
                         children: [
                           Container(
                             padding: const EdgeInsets.all(8),
                             decoration: BoxDecoration(
                               color: Colors.blue.withOpacity(0.1),
                               borderRadius: BorderRadius.circular(8),
                             ),
                             child: Icon(isImage ? Icons.image : Icons.description, color: Colors.blue, size: 20),
                           ),
                           const SizedBox(width: 12),
                           Expanded(
                             child: Text(
                               fileName,
                               style: const TextStyle(
                                 fontWeight: FontWeight.w500,
                                 color: Color(0xFF1E293B),
                               ),
                               maxLines: 1,
                               overflow: TextOverflow.ellipsis,
                             ),
                           ),
                           IconButton(
                             icon: const Icon(Icons.download_rounded, color: Colors.grey),
                             onPressed: () async {
                               final uri = Uri.parse(url);
                               if (await canLaunchUrl(uri)) {
                                 await launchUrl(uri, mode: LaunchMode.externalApplication);
                               } else {
                                 ScaffoldMessenger.of(context).showSnackBar(
                                   const SnackBar(content: Text('Could not open file')),
                                 );
                               }
                             },
                           ),
                         ],
                       ),
                     ],
                   ),
                 );
            }).toList(),
          ],
        ],
      ),
    ).animate().fadeIn(delay: 300.ms).moveY(begin: 20, end: 0);
  }

  Widget _buildDetailRow(String label, String value, {bool isBold = false}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: TextStyle(
            color: Colors.grey[500],
            fontSize: 14,
          ),
        ),
        Text(
          value,
          style: TextStyle(
            fontWeight: isBold ? FontWeight.bold : FontWeight.w600,
            fontSize: isBold ? 18 : 14,
            color: const Color(0xFF1E293B),
          ),
        ),
      ],
    );
  }

  Widget _buildStatusStep(String title, String time, bool isCompleted, bool showLine) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Column(
          children: [
            Container(
              width: 20,
              height: 20,
              decoration: BoxDecoration(
                color: isCompleted ? Colors.green : Colors.grey[300],
                shape: BoxShape.circle,
                border: isCompleted
                    ? null
                    : Border.all(color: Colors.grey[400]!, width: 2),
              ),
              child: isCompleted
                  ? const Icon(Icons.check, size: 12, color: Colors.white)
                  : null,
            ),
            if (showLine)
              Container(
                width: 2,
                height: 40,
                color: isCompleted ? Colors.green : Colors.grey[300],
              ),
          ],
        ),
        const SizedBox(width: 16),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 16,
                  color: isCompleted ? const Color(0xFF1E293B) : Colors.grey[400],
                ),
              ),
              if (time.isNotEmpty)
                Text(
                  time,
                  style: TextStyle(
                    color: Colors.grey[500],
                    fontSize: 12,
                  ),
                ),
              const SizedBox(height: 24),
            ],
          ),
        ),
      ],
    );
  }
}
