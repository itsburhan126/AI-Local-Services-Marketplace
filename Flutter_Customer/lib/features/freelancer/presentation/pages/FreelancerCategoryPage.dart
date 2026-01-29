import 'dart:ui';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_customer/core/widgets/custom_toast.dart';
import 'package:flutter_customer/core/widgets/custom_avatar.dart';
import 'package:provider/provider.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import '../../../home/data/home_service.dart';
import '../../../home/presentation/providers/home_provider.dart';

class FreelancerCategoryPage extends StatefulWidget {
  final Map<String, dynamic> category;

  const FreelancerCategoryPage({super.key, required this.category});

  @override
  State<FreelancerCategoryPage> createState() => _FreelancerCategoryPageState();
}

class _FreelancerCategoryPageState extends State<FreelancerCategoryPage> {
  final HomeService _homeService = HomeService();
  List<dynamic> _gigs = [];
  List<dynamic> _subCategories = [];
  List<dynamic> _serviceTypes = [];
  
  // Filter States
  int? _selectedServiceTypeId;
  String? _selectedSellerLevel;
  String? _selectedDeliveryTime;
  double? _minPrice;
  double? _maxPrice;

  bool _isLoading = true;
  bool _hasSubCategories = false;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadData();
    _loadServiceTypes();
  }

  Future<void> _loadServiceTypes() async {
    try {
      final types = await _homeService.getServiceTypes();
      if (mounted) {
        setState(() {
          _serviceTypes = types;
        });
      }
    } catch (e) {
      debugPrint('[FreelancerCategoryPage] Error loading service types: $e');
    }
  }

  Future<void> _loadData() async {
    try {
      if (mounted) {
        setState(() {
          _isLoading = true;
          _error = null;
        });
      }

      final categoryId = widget.category['id'] is int 
          ? widget.category['id'] 
          : int.tryParse(widget.category['id'].toString());
      
      if (categoryId == null) throw Exception('Invalid Category ID');
      
      // Load both subcategories and gigs concurrently
      debugPrint('[FreelancerCategoryPage] Loading data for category: $categoryId');
      final results = await Future.wait([
        _homeService.getSubCategories(categoryId),
        _homeService.getGigsByCategory(
          categoryId, 
          serviceTypeId: _selectedServiceTypeId,
          minPrice: _minPrice,
          maxPrice: _maxPrice,
        ),
      ]);

      if (mounted) {
        setState(() {
          _subCategories = results[0] as List<dynamic>;
          _gigs = results[1] as List<dynamic>;
          _hasSubCategories = _subCategories.isNotEmpty;
          _isLoading = false;
        });
        debugPrint('[FreelancerCategoryPage] Loaded ${_subCategories.length} subcategories and ${_gigs.length} gigs');
      }
    } catch (e) {
      debugPrint('[FreelancerCategoryPage] Error loading category data: $e');
      if (mounted) {
        setState(() {
          _error = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final categoryName = widget.category['name'] ?? 'Category';
    final categoryImage = widget.category['image'] ?? widget.category['icon'];
    final isUrl = categoryImage is String && (categoryImage.startsWith('http') || categoryImage.startsWith('assets'));

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: RefreshIndicator(
        onRefresh: _loadData,
        child: CustomScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            _buildAppBar(context, categoryName, isUrl ? categoryImage : null),
            
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.only(top: 16, bottom: 8),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 20),
                      child: Text(
                        'Shop by',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 22,
                          fontWeight: FontWeight.bold,
                          color: const Color(0xFF0F172A),
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),
                    _buildFilterChips(),
                  ],
                ),
              ),
            ),

            if (_isLoading)
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    children: [
                       SizedBox(
                         height: 130,
                         child: ListView.separated(
                           scrollDirection: Axis.horizontal,
                           itemCount: 4,
                           separatorBuilder: (_, __) => const SizedBox(width: 16),
                           itemBuilder: (_, __) => _buildShimmerSubCategory(),
                         ),
                       ),
                       const SizedBox(height: 24),
                       ...List.generate(3, (index) => Padding(
                         padding: const EdgeInsets.only(bottom: 16),
                         child: _buildShimmerGigCard(),
                         // child: _buildShimmerGigCard(),
                       )),
                    ],
                  ),
                ),
              )
            else if (_error != null)
               SliverFillRemaining(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.error_outline_rounded, size: 48, color: Colors.red),
                      const SizedBox(height: 16),
                      Text('Failed to load data', style: GoogleFonts.plusJakartaSans(color: Colors.red)),
                      const SizedBox(height: 8),
                      TextButton(onPressed: _loadData, child: const Text('Retry')),
                    ],
                  ),
                ),
              )
            else ...[
              // Subcategories Section
              if (_hasSubCategories)
                SliverToBoxAdapter(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: 8),
                      SizedBox(
                        height: 140,
                        child: ListView.separated(
                          padding: const EdgeInsets.symmetric(horizontal: 20),
                          scrollDirection: Axis.horizontal,
                          itemCount: _subCategories.length,
                          separatorBuilder: (context, index) => const SizedBox(width: 12),
                          itemBuilder: (context, index) => _buildSubCategoryItem(context, _subCategories[index], index),
                        ),
                      ),
                      const SizedBox(height: 24),
                    ],
                  ),
                ),

              // Gigs Section
              if (_gigs.isEmpty)
                SliverFillRemaining(
                  hasScrollBody: false,
                  child: Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Container(
                          padding: const EdgeInsets.all(24),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            shape: BoxShape.circle,
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black.withValues(alpha: 0.03),
                                blurRadius: 6,
                                offset: const Offset(0, 0),
                              ),
                            ],
                          ),
                          child: Icon(Icons.search_off_rounded, size: 48, color: Colors.grey[400]),
                        ),
                        const SizedBox(height: 24),
                        Text(
                          'No services found',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: const Color(0xFF1E293B),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Try checking back later',
                          style: GoogleFonts.plusJakartaSans(
                            color: const Color(0xFF64748B),
                          ),
                        ),
                      ],
                    ),
                  ),
                )
              else
                SliverPadding(
                  padding: const EdgeInsets.symmetric(horizontal: 20),
                  sliver: SliverList(
                    delegate: SliverChildBuilderDelegate(
                      (context, index) {
                        return Padding(
                          padding: const EdgeInsets.only(bottom: 20),
                          child: _buildVerticalGigCard(context, _gigs[index], index),
                        );
                      },
                      childCount: _gigs.length,
                    ),
                  ),
                ),
            ],
              
            const SliverPadding(padding: EdgeInsets.only(bottom: 40)),
          ],
        ),
      ),
    );
  }

  Widget _buildAppBar(BuildContext context, String title, String? imageUrl) {
    return SliverAppBar(
      pinned: true,
      floating: true,
      backgroundColor: Colors.white,
      surfaceTintColor: Colors.transparent,
      elevation: 0,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back, color: Color(0xFF0F172A)),
        onPressed: () => context.pop(),
      ),
      actions: [
        IconButton(
          icon: const Icon(Icons.search, color: Color(0xFF0F172A)),
          onPressed: () {
            // Search functionality can be added here
          },
        ),
      ],
      title: Text(
        title,
        style: GoogleFonts.plusJakartaSans(
          color: const Color(0xFF0F172A),
          fontWeight: FontWeight.w600,
          fontSize: 16,
        ),
      ),
      centerTitle: true,
    );
  }

  Widget _buildFilterChips() {
    final filters = [
      {'label': 'All', 'id': 'all'},
      {'label': 'Service type', 'id': 'service_type'},
      {'label': 'Seller Level', 'id': 'seller_level'},
      {'label': 'Delivery Time', 'id': 'delivery_time'},
      {'label': 'Budget', 'id': 'budget'},
    ];

    return SizedBox(
      height: 40,
      child: ListView.separated(
        padding: const EdgeInsets.symmetric(horizontal: 20),
        scrollDirection: Axis.horizontal,
        itemCount: filters.length,
        separatorBuilder: (_, __) => const SizedBox(width: 8),
        itemBuilder: (context, index) {
          final filter = filters[index];
          final id = filter['id'] as String;
          final label = filter['label'] as String;
          
          bool isSelected = false;
          String displayLabel = label;

          if (id == 'all') {
            isSelected = _selectedServiceTypeId == null && 
                        _selectedSellerLevel == null && 
                        _selectedDeliveryTime == null &&
                        _minPrice == null && 
                        _maxPrice == null;
          } else if (id == 'service_type') {
            isSelected = _selectedServiceTypeId != null;
            if (isSelected) {
              final selectedType = _serviceTypes.firstWhere(
                (t) => t['id'] == _selectedServiceTypeId, 
                orElse: () => {'name': 'Service type'}
              );
              displayLabel = selectedType['name'];
            }
          } else if (id == 'seller_level') {
            isSelected = _selectedSellerLevel != null;
            if (isSelected) displayLabel = _selectedSellerLevel!;
          } else if (id == 'delivery_time') {
            isSelected = _selectedDeliveryTime != null;
            if (isSelected) displayLabel = _selectedDeliveryTime!;
          } else if (id == 'budget') {
            isSelected = _minPrice != null || _maxPrice != null;
            if (isSelected) {
              if (_minPrice != null && _maxPrice != null) {
                displayLabel = '\$${_minPrice!.toInt()} - \$${_maxPrice!.toInt()}';
              } else if (_minPrice != null) {
                displayLabel = 'Min \$${_minPrice!.toInt()}';
              } else if (_maxPrice != null) {
                displayLabel = 'Max \$${_maxPrice!.toInt()}';
              }
            }
          }

          return GestureDetector(
            onTap: () {
              if (id == 'all') {
                setState(() {
                  _selectedServiceTypeId = null;
                  _selectedSellerLevel = null;
                  _selectedDeliveryTime = null;
                  _minPrice = null;
                  _maxPrice = null;
                });
                _loadData();
              } else if (id == 'service_type') {
                _showServiceTypeSheet();
              } else if (id == 'seller_level') {
                _showSellerLevelSheet();
              } else if (id == 'delivery_time') {
                _showDeliveryTimeSheet();
              } else if (id == 'budget') {
                _showBudgetSheet();
              }
            },
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
              decoration: BoxDecoration(
                color: isSelected ? const Color(0xFF0F172A) : Colors.white,
                borderRadius: BorderRadius.circular(24),
                border: Border.all(
                  color: isSelected ? const Color(0xFF0F172A) : Colors.grey.withValues(alpha: 0.2),
                ),
                boxShadow: isSelected ? [
                  BoxShadow(
                    color: const Color(0xFF0F172A).withValues(alpha: 0.2),
                    blurRadius: 8,
                    offset: const Offset(0, 4),
                  ),
                ] : null,
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    displayLabel,
                    style: GoogleFonts.plusJakartaSans(
                      color: isSelected ? Colors.white : const Color(0xFF64748B),
                      fontWeight: FontWeight.w600,
                      fontSize: 13,
                    ),
                  ),
                  if (isSelected && id != 'all') ...[
                      const SizedBox(width: 4),
                      GestureDetector(
                        onTap: () {
                          setState(() {
                            if (id == 'service_type') _selectedServiceTypeId = null;
                            if (id == 'seller_level') _selectedSellerLevel = null;
                            if (id == 'delivery_time') _selectedDeliveryTime = null;
                            if (id == 'budget') {
                              _minPrice = null;
                              _maxPrice = null;
                            }
                          });
                          if (id == 'service_type' || id == 'budget' || id == 'seller_level') _loadData();
                        },
                        child: const Icon(Icons.close, size: 16, color: Colors.white),
                      ),
                    ],
                  if (!isSelected && id != 'all') ...[
                     const SizedBox(width: 4),
                     const Icon(Icons.keyboard_arrow_down, size: 16, color: Color(0xFF64748B)),
                  ]
                ],
              ),
            ),
          );
        },
      ),
    );
  }

  void _showServiceTypeSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => Container(
        padding: const EdgeInsets.symmetric(vertical: 24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: Text(
                'Select Service Type',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF0F172A),
                ),
              ),
            ),
            const SizedBox(height: 16),
            if (_serviceTypes.isEmpty)
              const Center(child: Padding(
                padding: EdgeInsets.all(20.0),
                child: Text('No service types available'),
              ))
            else
              Flexible(
                child: ListView.separated(
                  shrinkWrap: true,
                  padding: const EdgeInsets.symmetric(horizontal: 24),
                  itemCount: _serviceTypes.length,
                  separatorBuilder: (_, __) => const Divider(height: 1),
                  itemBuilder: (context, index) {
                    final type = _serviceTypes[index];
                    final isSelected = _selectedServiceTypeId == type['id'];
                    return ListTile(
                      contentPadding: EdgeInsets.zero,
                      title: Text(
                        type['name'],
                        style: GoogleFonts.plusJakartaSans(
                          color: isSelected ? const Color(0xFF0F172A) : const Color(0xFF64748B),
                          fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
                        ),
                      ),
                      trailing: isSelected ? const Icon(Icons.check, color: Color(0xFF0F172A)) : null,
                      onTap: () {
                        setState(() {
                          _selectedServiceTypeId = type['id'];
                        });
                        Navigator.pop(context);
                        _loadData();
                      },
                    );
                  },
                ),
              ),
          ],
        ),
      ),
    );
  }

  void _showSellerLevelSheet() {
    final levels = ['Level 1', 'Level 2', 'Level 3', 'Level 4'];
    
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => Container(
        padding: const EdgeInsets.symmetric(vertical: 24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: Text(
                'Select Seller Level',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF0F172A),
                ),
              ),
            ),
            const SizedBox(height: 16),
            Flexible(
              child: ListView.separated(
                shrinkWrap: true,
                padding: const EdgeInsets.symmetric(horizontal: 24),
                itemCount: levels.length,
                separatorBuilder: (_, __) => const Divider(height: 1),
                itemBuilder: (context, index) {
                  final level = levels[index];
                  final isSelected = _selectedSellerLevel == level;
                  return ListTile(
                    contentPadding: EdgeInsets.zero,
                    title: Text(
                      level,
                      style: GoogleFonts.plusJakartaSans(
                        color: isSelected ? const Color(0xFF0F172A) : const Color(0xFF64748B),
                        fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
                      ),
                    ),
                    trailing: isSelected ? const Icon(Icons.check, color: Color(0xFF0F172A)) : null,
                    onTap: () {
                      setState(() {
                        _selectedSellerLevel = level;
                      });
                      Navigator.pop(context);
                      _loadData();
                    },
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showDeliveryTimeSheet() {
    final times = ['24 Hour', '3 Days', '5 Days', '7 Days'];
    
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => Container(
        padding: const EdgeInsets.symmetric(vertical: 24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: Text(
                'Select Delivery Time',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF0F172A),
                ),
              ),
            ),
            const SizedBox(height: 16),
            Flexible(
              child: ListView.separated(
                shrinkWrap: true,
                padding: const EdgeInsets.symmetric(horizontal: 24),
                itemCount: times.length,
                separatorBuilder: (_, __) => const Divider(height: 1),
                itemBuilder: (context, index) {
                  final time = times[index];
                  final isSelected = _selectedDeliveryTime == time;
                  return ListTile(
                    contentPadding: EdgeInsets.zero,
                    title: Text(
                      time,
                      style: GoogleFonts.plusJakartaSans(
                        color: isSelected ? const Color(0xFF0F172A) : const Color(0xFF64748B),
                        fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
                      ),
                    ),
                    trailing: isSelected ? const Icon(Icons.check, color: Color(0xFF0F172A)) : null,
                    onTap: () {
                      Navigator.pop(context);
                      CustomToast.show(context, '$time filtering coming soon');
                    },
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showBudgetSheet() {
    final minController = TextEditingController(text: _minPrice?.toInt().toString() ?? '');
    final maxController = TextEditingController(text: _maxPrice?.toInt().toString() ?? '');

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => Padding(
        padding: EdgeInsets.only(
          bottom: MediaQuery.of(context).viewInsets.bottom,
        ),
        child: Container(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Price Range',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFF0F172A),
                ),
              ),
              const SizedBox(height: 24),
              Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Min',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.w600,
                            color: const Color(0xFF64748B),
                          ),
                        ),
                        const SizedBox(height: 8),
                        TextField(
                          controller: minController,
                          keyboardType: TextInputType.number,
                          decoration: InputDecoration(
                            prefixText: '\$ ',
                            hintText: '0',
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Max',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.w600,
                            color: const Color(0xFF64748B),
                          ),
                        ),
                        const SizedBox(height: 8),
                        TextField(
                          controller: maxController,
                          keyboardType: TextInputType.number,
                          decoration: InputDecoration(
                            prefixText: '\$ ',
                            hintText: 'Any',
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 32),
              Row(
                children: [
                  Expanded(
                    child: OutlinedButton(
                      onPressed: () {
                        setState(() {
                          _minPrice = null;
                          _maxPrice = null;
                        });
                        Navigator.pop(context);
                        _loadData();
                      },
                      style: OutlinedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        side: const BorderSide(color: Color(0xFFE2E8F0)),
                      ),
                      child: Text(
                        'Clear',
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.w600,
                          color: const Color(0xFF0F172A),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: ElevatedButton(
                      onPressed: () {
                        if (minController.text.isEmpty && maxController.text.isEmpty) {
                          CustomToast.show(context, 'Please enter min and max price', isError: true);
                          return;
                        }

                        final min = double.tryParse(minController.text);
                        final max = double.tryParse(maxController.text);

                        setState(() {
                          _minPrice = min;
                          _maxPrice = max;
                        });
                        Navigator.pop(context);
                        _loadData();
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF0F172A),
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                      child: Text(
                        'Apply',
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.w600,
                          color: Colors.white,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  String _getValidUrl(String? url) {
    if (url == null || url.isEmpty || url == 'default') {
      return 'https://placehold.co/400x300?text=No+Image';
    }
    if (url.startsWith('http') || url.startsWith('assets')) return url;
    
    String cleanPath = url.startsWith('/') ? url.substring(1) : url;
    
    // Check if path already has storage/ prefix
    if (cleanPath.startsWith('storage/')) {
      return '${ApiConstants.baseUrl}/$cleanPath';
    }
    
    // Default to storage folder for relative paths
    return '${ApiConstants.baseUrl}/storage/$cleanPath';
  }

  Widget _buildSubCategoryItem(BuildContext context, Map<String, dynamic> category, int index) {
    final image = _getValidUrl(category['image'] ?? category['icon']);
    
    return GestureDetector(
      onTap: () {
        context.push('/freelancer-category', extra: category);
      },
      child: Container(
        width: 140,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
          border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Expanded(
              flex: 3,
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: CachedNetworkImage(
                  imageUrl: image,
                  fit: BoxFit.contain,
                  errorWidget: (context, url, error) => Container(
                    decoration: BoxDecoration(
                      color: const Color(0xFFF1F5F9),
                      shape: BoxShape.circle,
                    ),
                    padding: const EdgeInsets.all(12),
                    child: const Icon(Icons.category_outlined, color: Colors.grey),
                  ),
                ),
              ),
            ),
            Expanded(
              flex: 2,
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                child: Text(
                  category['name'],
                  maxLines: 2,
                  textAlign: TextAlign.center,
                  overflow: TextOverflow.ellipsis,
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.w600,
                    fontSize: 13,
                    color: const Color(0xFF1E293B),
                    height: 1.2,
                  ),
                ),
              ),
            ),
          ],
        ),
      ).animate().fadeIn(duration: 400.ms, delay: (index * 50).ms).slideX(begin: 0.1, end: 0),
    );
  }

  Widget _buildVerticalGigCard(BuildContext context, Map<String, dynamic> gig, int index) {
    final image = _getValidUrl(gig['thumbnail_image'] ?? gig['image'] ?? gig['thumbnail']);
    final title = gig['title'] ?? gig['name'] ?? 'Untitled Gig';
    
    // Price Logic
    String price = '0';
    if (gig['packages'] != null && (gig['packages'] as List).isNotEmpty) {
       final packages = gig['packages'] as List;
       final basic = packages.firstWhere((p) => p['tier'] == 'Basic', orElse: () => packages.first);
       price = basic['price']?.toString() ?? '0';
    } else {
       price = gig['price']?.toString() ?? '0';
    }

    // Provider Logic
    final provider = gig['provider'] ?? {};
    final providerName = provider['name'] ?? 'Freelancer';
    final providerImage = _getValidUrl(provider['provider_profile']?['profile_image'] ?? provider['image']);
    
    // Rating Logic
    final reviews = (gig['reviews'] as List?) ?? [];
    double rating = double.tryParse(gig['rating']?.toString() ?? '0') ?? 0.0;
    
    if (rating == 0 && reviews.isNotEmpty) {
      double total = 0;
      for (var r in reviews) {
        total += (r['rating'] as num?)?.toDouble() ?? 0.0;
      }
      rating = total / reviews.length;
    }
    
    final ratingStr = rating.toStringAsFixed(1);
    final reviewCount = reviews.length;

    return GestureDetector(
      onTap: () {
        context.push('/freelancer-gig-details', extra: gig);
      },
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.04),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
          border: Border.all(color: Colors.grey.withValues(alpha: 0.05)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image Section
            Stack(
              children: [
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                  child: AspectRatio(
                    aspectRatio: 16 / 9,
                    child: CachedNetworkImage(
                      imageUrl: image,
                      fit: BoxFit.cover,
                      errorWidget: (context, url, error) => Container(
                        color: Colors.grey[100],
                        child: const Icon(Icons.image_not_supported, color: Colors.grey),
                      ),
                    ),
                  ),
                ),
                Positioned(
                  top: 10,
                  right: 10,
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withValues(alpha: 0.1),
                          blurRadius: 4,
                          offset: const Offset(0, 2),
                        ),
                      ],
                    ),
                    child: const Icon(Icons.favorite_border_rounded, size: 18, color: Color(0xFF64748B)),
                  ),
                ),
              ],
            ),
            
            // Details Section
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Provider & Rating
                  Row(
                    children: [
                      CustomAvatar(imageUrl: providerImage, name: providerName, size: 24),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          providerName,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 13,
                            fontWeight: FontWeight.w600,
                            color: const Color(0xFF1E293B),
                          ),
                        ),
                      ),
                      const Icon(Icons.star_rounded, size: 18, color: Color(0xFFFFB800)),
                      const SizedBox(width: 4),
                      Text(
                        ratingStr,
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 13,
                          fontWeight: FontWeight.w700,
                          color: const Color(0xFF1E293B),
                        ),
                      ),
                      Text(
                        ' ($reviewCount)',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 13,
                          color: const Color(0xFF94A3B8),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  
                  // Title
                  Text(
                    title,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 15,
                      fontWeight: FontWeight.w600,
                      color: const Color(0xFF1E293B),
                      height: 1.4,
                    ),
                  ),
                  
                  const SizedBox(height: 16),
                  const Divider(height: 1, color: Color(0xFFF1F5F9)),
                  const SizedBox(height: 12),
                  
                  // Price
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: [
                      Text(
                        'Starting at ',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 12,
                          color: const Color(0xFF64748B),
                        ),
                      ),
                      Text(
                        '\$$price',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 18,
                          fontWeight: FontWeight.w700,
                          color: const Color(0xFF1E293B),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ).animate().fadeIn(duration: 400.ms, delay: (index * 50).ms).slideY(begin: 0.1, end: 0),
    );
  }

  Widget _buildShimmerSubCategory() {
    return Container(
      width: 140,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            height: 60,
            width: 60,
            decoration: const BoxDecoration(
              color: Color(0xFFF1F5F9),
              shape: BoxShape.circle,
            ),
          ).animate(onPlay: (controller) => controller.repeat())
           .shimmer(duration: 1200.ms, color: Colors.grey[300]),
          const SizedBox(height: 12),
          Container(
            height: 12,
            width: 80,
            decoration: BoxDecoration(
              color: const Color(0xFFF1F5F9),
              borderRadius: BorderRadius.circular(4),
            ),
          ).animate(onPlay: (controller) => controller.repeat())
           .shimmer(duration: 1200.ms, color: Colors.grey[300]),
        ],
      ),
    );
  }

  Widget _buildShimmerGigCard() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.withValues(alpha: 0.1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            height: 200,
            decoration: const BoxDecoration(
              color: Color(0xFFF1F5F9),
              borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
            ),
          ).animate(onPlay: (controller) => controller.repeat())
           .shimmer(duration: 1200.ms, color: Colors.grey[300]),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                Row(
                  children: [
                    Container(
                      height: 24,
                      width: 24,
                      decoration: const BoxDecoration(
                        color: Color(0xFFF1F5F9),
                        shape: BoxShape.circle,
                      ),
                    ).animate(onPlay: (controller) => controller.repeat())
                     .shimmer(duration: 1200.ms, color: Colors.grey[300]),
                    const SizedBox(width: 8),
                    Container(
                      height: 12,
                      width: 100,
                      decoration: BoxDecoration(
                        color: const Color(0xFFF1F5F9),
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ).animate(onPlay: (controller) => controller.repeat())
                     .shimmer(duration: 1200.ms, color: Colors.grey[300]),
                  ],
                ),
                const SizedBox(height: 16),
                Container(
                  height: 16,
                  width: double.infinity,
                  decoration: BoxDecoration(
                    color: const Color(0xFFF1F5F9),
                    borderRadius: BorderRadius.circular(4),
                  ),
                ).animate(onPlay: (controller) => controller.repeat())
                 .shimmer(duration: 1200.ms, color: Colors.grey[300]),
                const SizedBox(height: 8),
                Container(
                  height: 16,
                  width: 200,
                  decoration: BoxDecoration(
                    color: const Color(0xFFF1F5F9),
                    borderRadius: BorderRadius.circular(4),
                  ),
                ).animate(onPlay: (controller) => controller.repeat())
                 .shimmer(duration: 1200.ms, color: Colors.grey[300]),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
