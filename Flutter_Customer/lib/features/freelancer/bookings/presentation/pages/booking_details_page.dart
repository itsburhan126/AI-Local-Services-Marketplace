import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../providers/booking_provider.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';

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

    String? imageUrl = booking?['image'];
    if (imageUrl != null && !imageUrl.startsWith('http')) {
      imageUrl = '${ApiConstants.baseUrl}/$imageUrl'.replaceAll('//', '/').replaceFirst('http:/', 'http://').replaceFirst('https:/', 'https://');
    }

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
                          image: imageUrl != null 
                              ? DecorationImage(image: NetworkImage(imageUrl), fit: BoxFit.cover)
                              : null,
                        ),
                        child: imageUrl == null 
                            ? const Icon(Icons.cleaning_services, color: Colors.grey) 
                            : null,
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
                  _buildDetailRow('Provider', providerName),
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

            const SizedBox(height: 32),
            Consumer<BookingProvider>(
              builder: (context, provider, child) {
                return SizedBox(
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
                );
              }
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStatusTimeline(String currentStatus, String? createdAt) {
    final statusOrder = ['pending', 'accepted', 'in_progress', 'completed'];
    final statusLabels = {
      'pending': 'Order Placed',
      'accepted': 'Provider Accepted',
      'in_progress': 'Gig In Progress',
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
