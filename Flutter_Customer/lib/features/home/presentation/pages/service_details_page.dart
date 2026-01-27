import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../../chat/presentation/pages/chat_page.dart';
import '../../../chat/presentation/pages/chat_details_page.dart';
import '../../../auth/data/models/user_model.dart';
import 'package:flutter_customer/features/freelancer/data/services/gig_service.dart';
import '../providers/home_provider.dart';

class ServiceDetailsPage extends StatefulWidget {
  final Map<String, dynamic>? service;

  const ServiceDetailsPage({super.key, this.service});

  @override
  State<ServiceDetailsPage> createState() => _ServiceDetailsPageState();
}

class _ServiceDetailsPageState extends State<ServiceDetailsPage> {
  int _selectedPackageIndex = 0;
  final GigService _gigService = GigService();
  bool _isFavorite = false;

  @override
  void initState() {
    super.initState();
    _checkAndIncrementView();
    if (widget.service != null) {
      final isFav = widget.service!['is_favorite'];
      _isFavorite = isFav == true || isFav == 1;
    }
  }

  void _toggleFavorite() {
    if (widget.service == null || widget.service!['id'] == null) return;
    
    final serviceId = int.tryParse(widget.service!['id'].toString()) ?? 0;
    if (serviceId == 0) return;

    setState(() {
      _isFavorite = !_isFavorite;
    });

    Provider.of<HomeProvider>(context, listen: false).toggleGigFavorite(serviceId);
  }

  Future<void> _checkAndIncrementView() async {
    // Check if this is a Gig (has packages)
    final service = widget.service;
    final packages = service?['packages'] as List<dynamic>?;
    final hasPackages = packages != null && packages.isNotEmpty;

    if (hasPackages && service?['id'] != null) {
      await _gigService.incrementGigView(service!['id']);
    }
  }

  String _asString(dynamic v, {String fallback = ''}) {
    if (v == null) return fallback;
    if (v is String) return v;
    if (v is num || v is bool) return v.toString();
    if (v is Map) {
      for (final key in ['name', 'title', 'label', 'value']) {
        final val = v[key];
        if (val is String) return val;
        if (val is num || val is bool) return val.toString();
      }
    }
    if (v is List && v.isNotEmpty) {
      final first = v.first;
      if (first is String) return first;
      if (first is Map) {
        for (final key in ['url', 'src', 'image', 'full']) {
          final val = first[key];
          if (val is String) return val;
        }
      }
    }
    return fallback;
  }

  String? _safeImageUrl(dynamic v) {
    final s = _asString(v, fallback: '');
    if (s.isEmpty) {
      if (v is Map) {
        for (final key in ['url', 'src', 'image', 'full']) {
          final val = v[key];
          if (val is String && val.isNotEmpty) return val;
        }
      }
      if (v is List && v.isNotEmpty) {
        final first = v.first;
        if (first is String) return first;
        if (first is Map) {
          for (final key in ['url', 'src', 'image', 'full']) {
            final val = first[key];
            if (val is String && val.isNotEmpty) return val;
          }
        }
      }
      return null;
    }
    return s;
  }

  @override
  Widget build(BuildContext context) {
    final service = widget.service;
    final serviceName = _asString(service?['name'], fallback: 'Home Deep Cleaning Service');
    final categoryName = _asString(service?['category'], fallback: 'Cleaning');
    final description = _asString(
      service?['description'],
      fallback: 'Professional home cleaning service including floor, kitchen, bathroom, and furniture cleaning.',
    );
    final serviceImage = _safeImageUrl(service?['image'] ?? service?['thumbnail']);
    final providerMap = service?['provider'];
    final providerName = (providerMap is Map && providerMap['name'] != null) ? providerMap['name'] as String : 'Clean Pro Services';
    final providerId = (providerMap is Map && providerMap['id'] != null) ? providerMap['id'] as int? : null;
    final rating = service?['rating']?.toString() ?? '4.8';
    final reviews = service?['reviews']?.toString() ?? '120';
    
    // Check for packages (Freelancer Gig)
    final packages = service?['packages'] as List<dynamic>?;
    final hasPackages = packages != null && packages.isNotEmpty;
    
    // Sort packages by price (Basic -> Standard -> Premium) usually, but we rely on order or tier name
    // Assuming packages are ordered or we can find them.
    // Let's just use them as is for now.
    
    String displayPrice = _asString(service?['price'], fallback: '80.00');
    if (hasPackages) {
      final pkg = packages[_selectedPackageIndex];
      displayPrice = pkg['price'].toString();
    }

    return Scaffold(
      body: Stack(
        children: [
          CustomScrollView(
            slivers: [
              SliverAppBar(
                expandedHeight: 300,
                pinned: true,
                backgroundColor: Theme.of(context).scaffoldBackgroundColor,
                leading: Container(
                  margin: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: Theme.of(context).cardColor,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.1),
                        blurRadius: 10,
                      ),
                    ],
                  ),
                  child: IconButton(
                    icon: Icon(Icons.arrow_back, color: Theme.of(context).iconTheme.color),
                    onPressed: () => context.pop(),
                  ),
                ),
                actions: [
                  Container(
                    margin: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: Theme.of(context).cardColor,
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withValues(alpha: 0.1),
                          blurRadius: 10,
                        ),
                      ],
                    ),
                    child: IconButton(
                      icon: Icon(
                        _isFavorite ? Icons.favorite_rounded : Icons.favorite_border_rounded,
                        color: _isFavorite ? const Color(0xFFEF4444) : Theme.of(context).iconTheme.color,
                      ),
                      onPressed: _toggleFavorite,
                    ),
                  ),
                ],
                flexibleSpace: FlexibleSpaceBar(
                  background: Stack(
                    fit: StackFit.expand,
                    children: [
                      serviceImage != null 
                      ? Image.network(
                          serviceImage,
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) => Container(
                            color: Colors.grey[200],
                            child: Icon(Icons.broken_image, size: 100, color: Colors.grey[400]),
                          ),
                        )
                      : Container(
                        color: Colors.grey[200],
                        child: const Icon(Icons.image, size: 100, color: Colors.grey),
                      ),
                      Container(
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            begin: Alignment.topCenter,
                            end: Alignment.bottomCenter,
                            colors: [
                              Colors.transparent,
                              Colors.black.withValues(alpha: 0.5),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.all(24.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              categoryName,
                              style: TextStyle(
                                color: Theme.of(context).colorScheme.primary,
                                fontWeight: FontWeight.bold,
                                fontSize: 12,
                              ),
                            ),
                          ),
                          Expanded(
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.end,
                              children: [
                                const Icon(Icons.star_rounded, color: Colors.amber, size: 20),
                                const SizedBox(width: 4),
                                Text(
                                  rating,
                                  style: const TextStyle(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 16,
                                  ),
                                ),
                                const SizedBox(width: 4),
                                Flexible(
                                  child: Text(
                                    ' ($reviews reviews)',
                                    overflow: TextOverflow.ellipsis,
                                    maxLines: 1,
                                    style: TextStyle(
                                      color: Colors.grey[500],
                                      fontSize: 14,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Text(
                        serviceName,
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 24,
                          fontWeight: FontWeight.bold,
                          color: const Color(0xFF1E293B),
                        ),
                      ),
                      
                      if (hasPackages) ...[
                        const SizedBox(height: 24),
                        _buildPackageSelector(packages!),
                        const SizedBox(height: 16),
                        _buildPackageDetails(packages[_selectedPackageIndex]),
                      ] else ...[
                        const SizedBox(height: 8),
                        Text(
                          description,
                          style: TextStyle(
                            color: Colors.grey[600],
                            fontSize: 16,
                            height: 1.5,
                          ),
                        ),
                      ],

                      const SizedBox(height: 16),
                      Wrap(
                        spacing: 8,
                        runSpacing: 8,
                        children: [
                          _buildFeatureChip(context, Icons.verified_rounded, 'Verified'),
                          _buildFeatureChip(context, Icons.health_and_safety_rounded, 'Insured'),
                          _buildFeatureChip(context, Icons.schedule_rounded, '24/7 Support'),
                          _buildFeatureChip(context, Icons.star_rate_rounded, 'Top Rated'),
                        ],
                      ),
                      const SizedBox(height: 32),
                      Text(
                        'Provider',
                        style: Theme.of(context).textTheme.titleMedium?.copyWith(
                              fontWeight: FontWeight.bold,
                            ),
                      ),
                      const SizedBox(height: 16),
                      Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: Colors.grey[200]!),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withValues(alpha: 0.02),
                              blurRadius: 10,
                            ),
                          ],
                        ),
                        child: Row(
                          crossAxisAlignment: CrossAxisAlignment.center,
                          children: [
                            const CircleAvatar(
                              radius: 28,
                              backgroundColor: Color(0xFFE2E8F0),
                              child: Icon(Icons.person, color: Color(0xFF64748B)),
                            ),
                            const SizedBox(width: 16),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Row(
                                    children: [
                                      Expanded(
                                        child: Text(
                                          providerName,
                                          maxLines: 1,
                                          overflow: TextOverflow.ellipsis,
                                          style: const TextStyle(
                                            fontWeight: FontWeight.bold,
                                            fontSize: 16,
                                          ),
                                        ),
                                      ),
                                      const SizedBox(width: 8),
                                      const Icon(Icons.verified, size: 16, color: Colors.blue),
                                    ],
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    'Member since 2023',
                                    style: TextStyle(
                                      color: Colors.grey[500],
                                      fontSize: 12,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            IconButton(
                              onPressed: () {
                                // Start Chat
                                context.push(
                                  '/chat-details',
                                  extra: {
                                    'id': providerId,
                                    'name': providerName,
                                    'image': null,
                                  }
                                );
                              },
                              icon: Container(
                                padding: const EdgeInsets.all(8),
                                decoration: BoxDecoration(
                                  color: Theme.of(context).primaryColor.withValues(alpha: 0.1),
                                  shape: BoxShape.circle,
                                ),
                                child: Icon(
                                  Icons.chat_bubble_outline_rounded,
                                  color: Theme.of(context).primaryColor,
                                  size: 20,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 100), // Bottom padding for FAB
                    ],
                  ),
                ),
              ),
            ],
          ),
          Positioned(
            bottom: 0,
            left: 0,
            right: 0,
            child: Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: Colors.white,
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.05),
                    blurRadius: 20,
                    offset: const Offset(0, -5),
                  ),
                ],
              ),
              child: SafeArea(
                child: Row(
                  children: [
                    Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Total Price',
                          style: TextStyle(
                            color: Colors.grey[500],
                            fontSize: 12,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          '\$$displayPrice',
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 24,
                            color: Color(0xFF1E293B),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(width: 24),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: () {
                          // Prepare booking data
                          final pkg = hasPackages ? packages![_selectedPackageIndex] : null;
                          final bookingData = {
                            'service_id': service?['id'],
                            'service_name': serviceName,
                            'provider_id': providerId,
                            'provider_name': providerName,
                            'price': displayPrice,
                            'image': serviceImage,
                            'package_name': pkg?['name'] ?? pkg?['tier'], // 'Basic', 'Standard' etc.
                            'package_id': pkg?['id'],
                            // 'date': ... // We might want a date picker here later
                            // 'time': ...
                          };
                          
                          context.push(
                            '/booking-details',
                            extra: bookingData,
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Theme.of(context).primaryColor,
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                          elevation: 0,
                        ),
                        child: const Text(
                          'Book Now',
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPackageSelector(List<dynamic> packages) {
    return Container(
      height: 50,
      decoration: BoxDecoration(
        color: Colors.grey[100],
        borderRadius: BorderRadius.circular(25),
      ),
      child: Row(
        children: List.generate(packages.length, (index) {
          final isSelected = _selectedPackageIndex == index;
          final package = packages[index];
          final tierName = package['tier'] ?? 'Tier ${index + 1}'; // Basic, Standard, Premium
          
          return Expanded(
            child: GestureDetector(
              onTap: () => setState(() => _selectedPackageIndex = index),
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 200),
                margin: const EdgeInsets.all(4),
                decoration: BoxDecoration(
                  color: isSelected ? Colors.white : Colors.transparent,
                  borderRadius: BorderRadius.circular(21),
                  boxShadow: isSelected
                      ? [
                          BoxShadow(
                            color: Colors.black.withValues(alpha: 0.05),
                            blurRadius: 4,
                            offset: const Offset(0, 2),
                          )
                        ]
                      : null,
                ),
                alignment: Alignment.center,
                child: Text(
                  tierName,
                  style: TextStyle(
                    color: isSelected ? const Color(0xFF1E293B) : Colors.grey[500],
                    fontWeight: FontWeight.bold,
                    fontSize: 14,
                  ),
                ),
              ),
            ),
          );
        }),
      ),
    );
  }

  Widget _buildPackageDetails(dynamic package) {
    final description = package['description'] ?? 'No description available';
    final deliveryTime = package['delivery_time'] ?? 1;
    final revisions = package['revisions'] ?? 0;
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          package['name'] ?? 'Package Details',
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 8),
        Text(
          description,
          style: TextStyle(
            color: Colors.grey[600],
            fontSize: 15,
            height: 1.5,
          ),
        ),
        const SizedBox(height: 16),
        Row(
          children: [
            _buildInfoItem(Icons.access_time_rounded, '$deliveryTime Days Delivery'),
            const SizedBox(width: 24),
            _buildInfoItem(Icons.cached_rounded, revisions == -1 ? 'Unlimited Revisions' : '$revisions Revisions'),
          ],
        ),
      ],
    ).animate().fadeIn();
  }
  
  Widget _buildInfoItem(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 18, color: const Color(0xFF64748B)),
        const SizedBox(width: 8),
        Text(
          text,
          style: const TextStyle(
            color: Color(0xFF64748B),
            fontWeight: FontWeight.w600,
            fontSize: 14,
          ),
        ),
      ],
    );
  }

  Widget _buildFeatureChip(BuildContext context, IconData icon, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: Theme.of(context).scaffoldBackgroundColor,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey[200]!),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 16, color: Theme.of(context).primaryColor),
          const SizedBox(width: 6),
          Text(
            label,
            style: TextStyle(
              color: Colors.grey[700],
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }
}
