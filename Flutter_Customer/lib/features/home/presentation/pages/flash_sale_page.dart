import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'dart:async';

class FlashSalePage extends StatefulWidget {
  final Map<String, dynamic> data;

  const FlashSalePage({Key? key, required this.data}) : super(key: key);

  @override
  State<FlashSalePage> createState() => _FlashSalePageState();
}

class _FlashSalePageState extends State<FlashSalePage> {
  final TextEditingController _searchController = TextEditingController();
  List<dynamic> _allItems = [];
  Map<String, List<dynamic>> _categorizedItems = {};
  String _searchQuery = '';
  late Timer _timer;
  late Duration _timeLeft;
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    _processData();
    _startTimer();
  }

  void _processData() {
    _allItems = widget.data['items'] ?? [];
    _categorizedItems = {};
    for (var item in _allItems) {
      final category = item['category'];
      final categoryName = category != null ? category['name'] : 'Others';
      
      if (!_categorizedItems.containsKey(categoryName)) {
        _categorizedItems[categoryName] = [];
      }
      _categorizedItems[categoryName]!.add(item);
    }
  }

  void _startTimer() {
    DateTime end;
    final endTimeData = widget.data['end_time'];

    if (endTimeData != null && endTimeData is String) {
      try {
        end = DateTime.parse(endTimeData);
      } catch (e) {
        end = DateTime.now().add(const Duration(hours: 12));
      }
    } else if (endTimeData != null && endTimeData is int) {
      end = DateTime.fromMillisecondsSinceEpoch(endTimeData);
    } else {
      end = DateTime.now().add(const Duration(hours: 12));
    }
    
    _timeLeft = end.difference(DateTime.now());

    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (mounted) {
        setState(() {
          _timeLeft = end.difference(DateTime.now());
          if (_timeLeft.isNegative) {
            _timer.cancel();
          }
        });
      }
    });
  }

  @override
  void dispose() {
    _timer.cancel();
    _scrollController.dispose();
    super.dispose();
  }

  // Filter State
  RangeValues _priceRange = const RangeValues(0, 1000);
  String? _selectedCategory;

  void _showFilterModal() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) => StatefulBuilder(
        builder: (context, setModalState) => Container(
          height: MediaQuery.of(context).size.height * 0.75,
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
          ),
          child: Column(
            children: [
              // Handle
              Center(
                child: Container(
                  margin: const EdgeInsets.only(top: 12),
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(
                    color: Colors.grey[300],
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.all(24),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Header
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            'Filter Deals',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 24,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF1A1D1E),
                            ),
                          ),
                          IconButton(
                            onPressed: () => Navigator.pop(context),
                            icon: const Icon(Icons.close_rounded),
                            style: IconButton.styleFrom(
                              backgroundColor: const Color(0xFFF5F7FA),
                              padding: const EdgeInsets.all(8),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 32),

                      // Categories
                      Text(
                        'Category',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: const Color(0xFF1A1D1E),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Wrap(
                        spacing: 12,
                        runSpacing: 12,
                        children: [
                          _buildFilterChip('All', _selectedCategory == null, () {
                            setModalState(() => _selectedCategory = null);
                          }),
                          ..._categorizedItems.keys.map((cat) => _buildFilterChip(
                            cat, 
                            _selectedCategory == cat,
                            () => setModalState(() => _selectedCategory = cat),
                          )),
                        ],
                      ),
                      const SizedBox(height: 32),

                      // Price Range
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            'Price Range',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF1A1D1E),
                            ),
                          ),
                          Text(
                            '\$${_priceRange.start.toInt()} - \$${_priceRange.end.toInt()}',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF6366F1),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      RangeSlider(
                        values: _priceRange,
                        min: 0,
                        max: 1000,
                        divisions: 100,
                        activeColor: const Color(0xFF6366F1),
                        inactiveColor: const Color(0xFFE0E7FF),
                        onChanged: (values) {
                          setModalState(() => _priceRange = values);
                        },
                      ),
                    ],
                  ),
                ),
              ),
              
              // Actions
              Container(
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
                child: Row(
                  children: [
                    Expanded(
                      child: TextButton(
                        onPressed: () {
                          setState(() {
                            _selectedCategory = null;
                            _priceRange = const RangeValues(0, 1000);
                          });
                          Navigator.pop(context);
                        },
                        style: TextButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 18),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                          backgroundColor: const Color(0xFFF5F7FA),
                        ),
                        child: Text(
                          'Reset',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.w700,
                            color: const Color(0xFF64748B),
                            fontSize: 16,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      flex: 2,
                      child: ElevatedButton(
                        onPressed: () {
                          setState(() {}); // Apply filters
                          Navigator.pop(context);
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF1A1D1E),
                          padding: const EdgeInsets.symmetric(vertical: 18),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                          elevation: 0,
                          shadowColor: Colors.transparent,
                        ),
                        child: Text(
                          'Apply Filter',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                            fontSize: 16,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildFilterChip(String label, bool isSelected, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
        decoration: BoxDecoration(
          color: isSelected ? const Color(0xFF1A1D1E) : Colors.white,
          borderRadius: BorderRadius.circular(30),
          border: Border.all(
            color: isSelected ? const Color(0xFF1A1D1E) : const Color(0xFFE2E8F0),
            width: 1.5,
          ),
          boxShadow: isSelected ? [
            BoxShadow(
              color: const Color(0xFF1A1D1E).withOpacity(0.2),
              blurRadius: 10,
              offset: const Offset(0, 4),
            )
          ] : null,
        ),
        child: Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            color: isSelected ? Colors.white : const Color(0xFF64748B),
            fontSize: 14,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }

  void _filterByCategory(String categoryName) {
    setState(() {
      _selectedCategory = categoryName;
      _searchController.clear();
      _searchQuery = '';
    });
    if (_scrollController.hasClients) {
      _scrollController.animateTo(
        0,
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeOut,
      );
    }
  }

  List<dynamic> get _filteredItems {
    var items = _allItems;

    // 1. Filter by Search Query
    if (_searchQuery.isNotEmpty) {
      items = items.where((item) {
        final title = (item['title'] ?? '').toString().toLowerCase();
        final category = (item['category'] != null ? item['category']['name'] : 'Others').toString().toLowerCase();
        final query = _searchQuery.toLowerCase();
        return title.contains(query) || category.contains(query);
      }).toList();
    }

    // 2. Filter by Category
    if (_selectedCategory != null) {
      items = items.where((item) {
        final category = item['category'] != null ? item['category']['name'] : 'Others';
        return category == _selectedCategory;
      }).toList();
    }

    // 3. Filter by Price
    items = items.where((item) {
      final price = (item['discounted_price'] ?? 0).toDouble();
      return price >= _priceRange.start && price <= _priceRange.end;
    }).toList();

    return items;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      body: CustomScrollView(
        controller: _scrollController,
        slivers: [
          // 1. Custom Ultra App Bar
          SliverAppBar(
            expandedHeight: 280,
            floating: false,
            pinned: true,
            backgroundColor: const Color(0xFFF5F7FA),
            elevation: 0,
            leading: Padding(
              padding: const EdgeInsets.only(left: 16, top: 8, bottom: 8),
              child: CircleAvatar(
                backgroundColor: Colors.white.withOpacity(0.8),
                child: IconButton(
                  icon: const Icon(Icons.arrow_back_ios_new, color: Color(0xFF1A1D1E), size: 18),
                  onPressed: () => Navigator.pop(context),
                ),
              ),
            ),
            actions: [
               Padding(
                padding: const EdgeInsets.only(right: 16, top: 8, bottom: 8),
                child: CircleAvatar(
                  backgroundColor: Colors.white.withOpacity(0.8),
                  child: IconButton(
                    icon: const Icon(Icons.tune_rounded, color: Color(0xFF1A1D1E), size: 20),
                    onPressed: _showFilterModal,
                  ),
                ),
              ),
            ],
            flexibleSpace: FlexibleSpaceBar(
              titlePadding: EdgeInsets.zero,
              expandedTitleScale: 1.1,
              title: LayoutBuilder(
                builder: (context, constraints) {
                  final isCollapsed = constraints.maxHeight <= kToolbarHeight + 50;
                  return AnimatedOpacity(
                    duration: const Duration(milliseconds: 200),
                    opacity: isCollapsed ? 1.0 : 0.0,
                    child: Container(
                      padding: const EdgeInsets.only(bottom: 16),
                      alignment: Alignment.bottomCenter,
                      child: Text(
                        'Flash Sales',
                        style: GoogleFonts.plusJakartaSans(
                          color: const Color(0xFF1A1D1E),
                          fontWeight: FontWeight.bold,
                          fontSize: 18,
                        ),
                      ),
                    ),
                  );
                },
              ),
              background: Stack(
                children: [
                  // Gradient Background
                  Container(
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                        colors: [
                          const Color(0xFFEEF2FF),
                          const Color(0xFFE0E7FF),
                          const Color(0xFFF5F7FA),
                        ],
                        stops: const [0.0, 0.5, 1.0],
                      ),
                    ),
                  ),
                  
                  // Decorative Elements
                  Positioned(
                    right: -50,
                    top: -50,
                    child: Container(
                      width: 250,
                      height: 250,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        gradient: RadialGradient(
                          colors: [
                            const Color(0xFF6366F1).withOpacity(0.1),
                            Colors.transparent,
                          ],
                        ),
                      ),
                    ),
                  ),
                  
                  // Main Header Content
                  Positioned(
                    left: 24,
                    bottom: 100,
                    right: 24,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const SizedBox(height: 40), // Push content down for better spacing
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                            color: const Color(0xFFFF4757).withOpacity(0.1),
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: const Color(0xFFFF4757).withOpacity(0.2)),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              const Icon(Icons.local_fire_department_rounded, size: 14, color: Color(0xFFFF4757)),
                              const SizedBox(width: 4),
                              Text(
                                'LIMITED TIME OFFER',
                                style: GoogleFonts.plusJakartaSans(
                                  color: const Color(0xFFFF4757),
                                  fontSize: 11,
                                  fontWeight: FontWeight.bold,
                                  letterSpacing: 0.5,
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 16),
                        SizedBox(
                          width: MediaQuery.of(context).size.width * 0.8,
                          child: Text(
                            widget.data['title'] ?? 'Flash Sale',
                            maxLines: 2,
                            textAlign: TextAlign.center,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.plusJakartaSans(
                              color: const Color(0xFF1A1D1E),
                              fontWeight: FontWeight.w800,
                              fontSize: 32,
                              height: 1.1,
                            ),
                          ),
                        ),
                        const SizedBox(height: 20),
                        // Timer
                        Builder(
                          builder: (context) {
                            final days = _timeLeft.inDays;
                            final hours = _timeLeft.inHours % 24;
                            final minutes = _timeLeft.inMinutes % 60;
                            final seconds = _timeLeft.inSeconds % 60;

                            return Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                if (days > 0) ...[
                                  _buildTimerBox(days, 'Days'),
                                  _buildTimerSeparator(),
                                ],
                                _buildTimerBox(hours, 'Hrs'),
                                _buildTimerSeparator(),
                                _buildTimerBox(minutes, 'Min'),
                                _buildTimerSeparator(),
                                _buildTimerBox(seconds, 'Sec'),
                              ],
                            );
                          },
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            bottom: PreferredSize(
              preferredSize: const Size.fromHeight(80),
              child: Container(
                height: 80,
                decoration: const BoxDecoration(
                  color: Color(0xFFF5F7FA),
                  borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
                ),
                child: Center(
                  child: Container(
                    margin: const EdgeInsets.symmetric(horizontal: 24),
                    height: 56,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFF6366F1).withOpacity(0.08),
                          blurRadius: 24,
                          offset: const Offset(0, 8),
                        ),
                      ],
                    ),
                    child: TextField(
                      controller: _searchController,
                      onChanged: (value) => setState(() => _searchQuery = value),
                      style: GoogleFonts.plusJakartaSans(
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF1A1D1E),
                      ),
                      decoration: InputDecoration(
                        hintText: 'Search for deals...',
                        hintStyle: GoogleFonts.plusJakartaSans(
                          color: const Color(0xFF94A3B8),
                          fontSize: 15,
                        ),
                        prefixIcon: const Icon(Icons.search_rounded, color: Color(0xFF94A3B8)),
                        suffixIcon: _searchQuery.isNotEmpty 
                          ? IconButton(
                              icon: const Icon(Icons.close_rounded, color: Color(0xFF94A3B8), size: 20),
                              onPressed: () {
                                setState(() {
                                  _searchController.clear();
                                  _searchQuery = '';
                                });
                              },
                            ) 
                          : null,
                        border: InputBorder.none,
                        contentPadding: const EdgeInsets.symmetric(vertical: 16),
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),

          // 2. Content
          if (_searchQuery.isNotEmpty || _selectedCategory != null) ...[
            // Filter Header
            if (_selectedCategory != null)
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.fromLTRB(24, 24, 24, 0),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: const Color(0xFF1A1D1E),
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Text(
                              _selectedCategory!,
                              style: GoogleFonts.plusJakartaSans(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                                fontSize: 12,
                              ),
                            ),
                            const SizedBox(width: 4),
                            GestureDetector(
                              onTap: () => setState(() => _selectedCategory = null),
                              child: const Icon(Icons.close_rounded, size: 16, color: Colors.white),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(width: 12),
                      Text(
                        'Showing results',
                        style: GoogleFonts.plusJakartaSans(
                          color: const Color(0xFF64748B),
                          fontSize: 14,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ],
                  ),
                ),
              ),

            // Results Grid
            SliverPadding(
              padding: const EdgeInsets.all(24),
              sliver: SliverGrid(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.7,
                  crossAxisSpacing: 16,
                  mainAxisSpacing: 16,
                ),
                delegate: SliverChildBuilderDelegate(
                  (context, index) => _buildItemCard(_filteredItems[index]),
                  childCount: _filteredItems.length,
                ),
              ),
            ),
          ] else ...[
            // Categorized Horizontal Lists
            ..._categorizedItems.entries.map((entry) {
              return SliverToBoxAdapter(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: const EdgeInsets.fromLTRB(24, 24, 24, 16),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            entry.key,
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                              color: const Color(0xFF1A1D1E),
                            ),
                          ),
                          InkWell(
                            onTap: () => _filterByCategory(entry.key),
                            borderRadius: BorderRadius.circular(20),
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(20),
                                border: Border.all(color: const Color(0xFFE0E7FF)),
                              ),
                              child: Row(
                                children: [
                                  Text(
                                    'See All',
                                    style: GoogleFonts.plusJakartaSans(
                                      fontSize: 12,
                                      fontWeight: FontWeight.bold,
                                      color: const Color(0xFF6366F1),
                                    ),
                                  ),
                                  const SizedBox(width: 4),
                                  const Icon(Icons.arrow_forward_rounded, size: 14, color: Color(0xFF6366F1)),
                                ],
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    SizedBox(
                      height: 250,
                      child: ListView.separated(
                        padding: const EdgeInsets.symmetric(horizontal: 24),
                        scrollDirection: Axis.horizontal,
                        itemCount: entry.value.length,
                        separatorBuilder: (_, __) => const SizedBox(width: 16),
                        itemBuilder: (context, index) => SizedBox(
                          width: 170,
                          child: _buildItemCard(entry.value[index]),
                        ),
                      ),
                    ),
                  ],
                ),
              );
            }),

            // All Items Header
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(24, 40, 24, 20),
                child: Text(
                  'All Flash Deals',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF1A1D1E),
                  ),
                ),
              ),
            ),

            // Vertical List of All Items
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 0),
              sliver: SliverList(
                delegate: SliverChildBuilderDelegate(
                  (context, index) => Padding(
                    padding: const EdgeInsets.only(bottom: 16),
                    child: _buildVerticalItemCard(_allItems[index]),
                  ),
                  childCount: _allItems.length,
                ),
              ),
            ),
            
            const SliverPadding(padding: EdgeInsets.only(bottom: 40)),
          ],
        ],
      ),
    );
  }

  Widget _buildTimerBox(int value, String label) {
    return Container(
      width: 50,
      padding: const EdgeInsets.symmetric(vertical: 8),
      decoration: BoxDecoration(
        color: const Color(0xFF1A1D1E),
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF1A1D1E).withOpacity(0.2),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            value.toString().padLeft(2, '0'),
            style: GoogleFonts.plusJakartaSans(
              color: Colors.white,
              fontWeight: FontWeight.bold,
              fontSize: 16,
              height: 1,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              color: Colors.white.withOpacity(0.6),
              fontSize: 9,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTimerSeparator() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 8),
      child: Text(
        ':',
        style: GoogleFonts.plusJakartaSans(
          color: const Color(0xFF1A1D1E),
          fontSize: 20,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildItemCard(Map<String, dynamic> item) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF909090).withOpacity(0.08),
            blurRadius: 24,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Image
          Expanded(
            child: Stack(
              children: [
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
                  child: CachedNetworkImage(
                    imageUrl: item['image'] ?? '',
                    width: double.infinity,
                    height: double.infinity,
                    fit: BoxFit.cover,
                    errorWidget: (context, url, error) => Container(
                      color: const Color(0xFFF1F5F9),
                      child: const Icon(Icons.image_not_supported_rounded, color: Color(0xFFCBD5E1)),
                    ),
                  ),
                ),
                Positioned(
                  top: 12,
                  left: 12,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFF4757),
                      borderRadius: BorderRadius.circular(10),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFFFF4757).withOpacity(0.3),
                          blurRadius: 8,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: Text(
                      '${item['discount_percentage']}% OFF',
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white,
                        fontSize: 11,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          
          // Details
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item['category'] != null ? item['category']['name'] : 'Service',
                  style: GoogleFonts.plusJakartaSans(
                    color: const Color(0xFF94A3B8),
                    fontSize: 11,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  item['title'] ?? 'Service Title',
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    fontSize: 15,
                    color: const Color(0xFF1A1D1E),
                  ),
                ),
                const SizedBox(height: 10),
                Row(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text(
                      '\$${item['discounted_price']}',
                      style: GoogleFonts.plusJakartaSans(
                        fontWeight: FontWeight.w800,
                        fontSize: 18,
                        color: const Color(0xFF6366F1),
                        height: 1,
                      ),
                    ),
                    const SizedBox(width: 6),
                    Text(
                      '\$${item['price']}',
                      style: GoogleFonts.plusJakartaSans(
                        decoration: TextDecoration.lineThrough,
                        fontSize: 13,
                        color: const Color(0xFF94A3B8),
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    ).animate().fadeIn().scale(duration: 300.ms, curve: Curves.easeOut);
  }

  Widget _buildVerticalItemCard(Map<String, dynamic> item) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF909090).withOpacity(0.08),
            blurRadius: 24,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Row(
        children: [
          // Image
          ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: Stack(
              children: [
                CachedNetworkImage(
                  imageUrl: item['image'] ?? '',
                  height: 110,
                  width: 110,
                  fit: BoxFit.cover,
                  errorWidget: (context, url, error) => Container(
                    color: const Color(0xFFF1F5F9),
                    child: const Icon(Icons.image_not_supported_rounded, color: Color(0xFFCBD5E1)),
                  ),
                ),
                Positioned(
                  top: 8,
                  left: 8,
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFF4757),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      '-${item['discount_percentage']}%',
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white,
                        fontSize: 10,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          
          const SizedBox(width: 20),
          
          // Details
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF1F5F9),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        item['category'] != null ? item['category']['name'] : 'Service',
                        style: GoogleFonts.plusJakartaSans(
                          color: const Color(0xFF64748B),
                          fontSize: 11,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                    // Icon(Icons.favorite_border_rounded, size: 20, color: Color(0xFF94A3B8)),
                  ],
                ),
                const SizedBox(height: 10),
                Text(
                  item['title'] ?? 'Service Title',
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                    color: const Color(0xFF1A1D1E),
                    height: 1.3,
                  ),
                ),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Text(
                          '\$${item['discounted_price']}',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.w800,
                            fontSize: 20,
                            color: const Color(0xFF6366F1),
                            height: 1,
                          ),
                        ),
                        const SizedBox(width: 8),
                        Text(
                          '\$${item['price']}',
                          style: GoogleFonts.plusJakartaSans(
                            decoration: TextDecoration.lineThrough,
                            fontSize: 14,
                            color: const Color(0xFF94A3B8),
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                    Container(
                      decoration: BoxDecoration(
                        color: const Color(0xFF1A1D1E),
                        borderRadius: BorderRadius.circular(12),
                        boxShadow: [
                          BoxShadow(
                            color: const Color(0xFF1A1D1E).withOpacity(0.2),
                            blurRadius: 10,
                            offset: const Offset(0, 4),
                          ),
                        ],
                      ),
                      child: IconButton(
                        icon: const Icon(Icons.arrow_forward_rounded, color: Colors.white, size: 18),
                        onPressed: () {},
                        constraints: const BoxConstraints(minWidth: 40, minHeight: 40),
                        padding: EdgeInsets.zero,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    ).animate().slideX(begin: 0.1, duration: 400.ms, curve: Curves.easeOut).fadeIn();
  }
}
