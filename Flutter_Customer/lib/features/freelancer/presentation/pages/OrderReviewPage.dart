import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import '../../data/services/gig_service.dart';

class OrderReviewPage extends StatefulWidget {
  final Map<String, dynamic> bookingData;

  const OrderReviewPage({super.key, required this.bookingData});

  @override
  State<OrderReviewPage> createState() => _OrderReviewPageState();
}

class _OrderReviewPageState extends State<OrderReviewPage> {
  final GigService _gigService = GigService();
  String _selectedPaymentMethod = 'credit_card';
  bool _isProcessing = false;

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

  double get _totalPrice => double.tryParse(widget.bookingData['total_price'].toString()) ?? 0.0;
  double get _serviceFee => (_totalPrice * 0.05).clamp(2.0, 50.0); // 5% fee
  double get _grandTotal => _totalPrice + _serviceFee;

  @override
  Widget build(BuildContext context) {
    final extras = widget.bookingData['extras'] as List<dynamic>? ?? [];

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Color(0xFF0F172A)),
          onPressed: () => context.pop(),
        ),
        title: Text(
          'Review & Pay',
          style: GoogleFonts.plusJakartaSans(
            color: const Color(0xFF0F172A),
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Order Summary Card
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.05),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(8),
                        child: Image.network(
                          _getValidUrl(widget.bookingData['image']),
                          width: 80,
                          height: 80,
                          fit: BoxFit.cover,
                          errorBuilder: (_, __, ___) => Container(
                            width: 80,
                            height: 80,
                            color: Colors.grey[200],
                            child: const Icon(Icons.image, color: Colors.grey),
                          ),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              widget.bookingData['service_name'] ?? 'Service',
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: GoogleFonts.plusJakartaSans(
                                fontWeight: FontWeight.bold,
                                fontSize: 15,
                                color: const Color(0xFF0F172A),
                              ),
                            ),
                            const SizedBox(height: 8),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                              decoration: BoxDecoration(
                                color: Colors.grey[100],
                                borderRadius: BorderRadius.circular(6),
                              ),
                              child: Text(
                                widget.bookingData['package_name'] ?? 'Package',
                                style: GoogleFonts.plusJakartaSans(
                                  color: const Color(0xFF64748B),
                                  fontSize: 12,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),
                  const Divider(),
                  const SizedBox(height: 16),
                  
                  // Breakdown
                  _buildSummaryRow('Subtotal', '\$${widget.bookingData['price']}'),
                  if (extras.isNotEmpty) ...[
                    const SizedBox(height: 12),
                    ...extras.map((e) => Padding(
                      padding: const EdgeInsets.only(bottom: 12),
                      child: _buildSummaryRow(e['title'], '+\$${e['price']}', isMuted: true),
                    )),
                  ],
                  const SizedBox(height: 12),
                  _buildSummaryRow('Service Fee', '\$${_serviceFee.toStringAsFixed(2)}'),
                  const SizedBox(height: 16),
                  const Divider(),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Total',
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.bold,
                          fontSize: 18,
                          color: const Color(0xFF0F172A),
                        ),
                      ),
                      Text(
                        '\$${_grandTotal.toStringAsFixed(2)}',
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.bold,
                          fontSize: 24,
                          color: const Color(0xFF0F172A),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: [
                      const Icon(Icons.schedule, size: 14, color: Colors.green),
                      const SizedBox(width: 4),
                      Text(
                        'Delivery: ${widget.bookingData['delivery_days']} Days',
                        style: GoogleFonts.plusJakartaSans(
                          color: Colors.green,
                          fontWeight: FontWeight.w600,
                          fontSize: 13,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ).animate().slideY(begin: 0.1, duration: 400.ms, curve: Curves.easeOut),

            const SizedBox(height: 24),

            Text(
              'Payment Method',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: const Color(0xFF0F172A),
              ),
            ),
            const SizedBox(height: 16),

            // Payment Methods
            _buildPaymentMethod(
              id: 'credit_card',
              title: 'Credit or Debit Card',
              icon: Icons.credit_card,
            ),
            const SizedBox(height: 12),
            _buildPaymentMethod(
              id: 'paypal',
              title: 'PayPal',
              icon: Icons.account_balance_wallet,
            ),
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.05),
              blurRadius: 10,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: SafeArea(
          child: SizedBox(
            height: 54,
            child: ElevatedButton(
              onPressed: _isProcessing ? null : () async {
                setState(() => _isProcessing = true);
                
                try {
                  // Prepare API payload
                  final now = DateTime.now();
                  final date = "${now.year}-${now.month.toString().padLeft(2, '0')}-${now.day.toString().padLeft(2, '0')}";
                  final time = "${now.hour.toString().padLeft(2, '0')}:${now.minute.toString().padLeft(2, '0')}";

                  final payload = {
                    'gig_id': widget.bookingData['service_id'],
                    'gig_package_id': widget.bookingData['package_id'],
                    'date': date,
                    'time': time,
                    'service_id': widget.bookingData['service_id'],
                    'provider_id': widget.bookingData['provider_id'],
                    'package_name': widget.bookingData['package_name'],
                    'price': _grandTotal,
                    'delivery_days': widget.bookingData['delivery_days'],
                    'payment_method': _selectedPaymentMethod,
                    'extras': widget.bookingData['extras'],
                    'status': 'pending',
                    'payment_status': 'pending', // No active payment yet
                    'notes': 'Order placed via mobile app',
                    'address': 'Online',
                  };

                  final response = await _gigService.createGigOrder(payload);
                  
                  if (mounted) {
                    setState(() => _isProcessing = false);
                    
                    if (response != null) {
                      context.go('/order-success');
                    } else {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Failed to create order. Please try again.')),
                      );
                    }
                  }
                } catch (e) {
                  if (mounted) {
                    setState(() => _isProcessing = false);
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(content: Text('Error: ${e.toString()}')),
                    );
                  }
                }
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF0F172A),
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
                elevation: 0,
              ),
              child: _isProcessing
                  ? const SizedBox(
                      width: 24,
                      height: 24,
                      child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                    )
                  : Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.lock, size: 18),
                        const SizedBox(width: 8),
                        Text(
                          'Confirm & Pay \$${_grandTotal.toStringAsFixed(2)}',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                          ),
                        ),
                      ],
                    ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildSummaryRow(String label, String value, {bool isMuted = false}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            color: isMuted ? Colors.grey[500] : Colors.grey[600],
            fontSize: 14,
            fontWeight: isMuted ? FontWeight.normal : FontWeight.w500,
          ),
        ),
        Text(
          value,
          style: GoogleFonts.plusJakartaSans(
            color: const Color(0xFF0F172A),
            fontWeight: FontWeight.bold,
            fontSize: 14,
          ),
        ),
      ],
    );
  }

  Widget _buildPaymentMethod({required String id, required String title, required IconData icon}) {
    final isSelected = _selectedPaymentMethod == id;
    return GestureDetector(
      onTap: () => setState(() => _selectedPaymentMethod = id),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isSelected ? const Color(0xFF0F172A) : Colors.grey[200]!,
            width: isSelected ? 2 : 1,
          ),
        ),
        child: Row(
          children: [
            Icon(icon, color: isSelected ? const Color(0xFF0F172A) : Colors.grey[400]),
            const SizedBox(width: 16),
            Expanded(
              child: Text(
                title,
                style: GoogleFonts.plusJakartaSans(
                  fontWeight: FontWeight.w600,
                  fontSize: 15,
                  color: const Color(0xFF0F172A),
                ),
              ),
            ),
            if (isSelected)
              const Icon(Icons.check_circle, color: Color(0xFF0F172A), size: 20)
            else
              Container(
                width: 20,
                height: 20,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.grey[300]!),
                ),
              ),
          ],
        ),
      ),
    );
  }
}
