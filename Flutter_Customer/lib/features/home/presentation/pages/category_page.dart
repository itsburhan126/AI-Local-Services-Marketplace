import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'dart:ui';
import '../providers/home_provider.dart';

class CategoryPage extends StatefulWidget {
  final Map<String, dynamic>? category;
  final String? type; // 'local_service' or 'freelancer'
  
  const CategoryPage({super.key, this.category, this.type});

  @override
  State<CategoryPage> createState() => _CategoryPageState();
}

class _CategoryPageState extends State<CategoryPage> {
  @override
  void initState() {
    super.initState();
    // If we have a specific type and no categories loaded (or just to be safe), we could trigger load.
    // But usually HomeProvider already has data if we came from Home.
    // For now, we rely on existing data to avoid double fetch, 
    // or if empty, we might trigger (optional).
    if (widget.type != null && context.read<HomeProvider>().categories.isEmpty) {
       WidgetsBinding.instance.addPostFrameCallback((_) {
         context.read<HomeProvider>().loadHomeData(type: widget.type!);
       });
    }
  }

  @override
  Widget build(BuildContext context) {
    final categoryName = widget.category?['name'] ?? 'Categories';
    final provider = context.watch<HomeProvider>();
    final categories = widget.category == null ? provider.categories : []; 
    // If widget.category is NOT null, we are likely in a sub-category view (not implemented fully here yet),
    // or we should show services. For now, if category is null, we show ALL categories.
    
    // Fallback if provider empty (e.g. direct nav or error)
    final displayCategories = categories.isNotEmpty ? categories : _getFallbackCategories();

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      extendBodyBehindAppBar: true,
      appBar: AppBar(
        backgroundColor: Colors.white.withValues(alpha: 0.8),
        flexibleSpace: ClipRRect(
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
            child: Container(color: Colors.transparent),
          ),
        ),
        surfaceTintColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
        leading: IconButton(
          icon: Icon(Icons.arrow_back_ios_new_rounded, color: Theme.of(context).iconTheme.color, size: 20),
          onPressed: () => context.pop(),
        ),
        title: Text(
          categoryName,
          style: GoogleFonts.plusJakartaSans(
            color: const Color(0xFF1E293B),
            fontWeight: FontWeight.bold,
            fontSize: 20,
          ),
        ),
      ),
      body: SafeArea(
        child: Column(
          children: [
            _buildSearchAndFilter(context),
            Expanded(
              child: GridView.builder(
                padding: const EdgeInsets.all(20),
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.85,
                  crossAxisSpacing: 16,
                  mainAxisSpacing: 16,
                ),
                itemCount: displayCategories.length,
                itemBuilder: (context, index) => _buildCategoryCard(context, displayCategories[index], index),
              ),
            ),
          ],
        ),
      ),
    );
  }

  List<dynamic> _getFallbackCategories() {
    // Return different fallbacks based on type if needed, or generic ones
    if (widget.type == 'freelancer') {
      return [
        {'name': 'Development', 'icon': Icons.code, 'color': 0xFF6366F1},
        {'name': 'Design', 'icon': Icons.design_services, 'color': 0xFFEC4899},
        {'name': 'Marketing', 'icon': Icons.campaign, 'color': 0xFF10B981},
        {'name': 'Writing', 'icon': Icons.translate, 'color': 0xFF8B5CF6},
        {'name': 'Video', 'icon': Icons.video_camera_back, 'color': 0xFFF59E0B},
        {'name': 'Business', 'icon': Icons.business_center, 'color': 0xFF3B82F6},
      ];
    }
    return [
      {'name': 'Cleaning', 'icon': Icons.cleaning_services_rounded, 'color': 0xFFEEF2FF},
      {'name': 'Plumbing', 'icon': Icons.plumbing_rounded, 'color': 0xFFFDF2F8},
      {'name': 'Electrical', 'icon': Icons.electrical_services_rounded, 'color': 0xFFF0FDF4},
      {'name': 'Painting', 'icon': Icons.format_paint_rounded, 'color': 0xFFFFF7ED},
      {'name': 'Moving', 'icon': Icons.local_shipping_rounded, 'color': 0xFFF0F9FF},
      {'name': 'Gardening', 'icon': Icons.grass_rounded, 'color': 0xFFFAF5FF},
    ];
  }

  Widget _buildSearchAndFilter(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(20, 10, 20, 10),
      child: Row(
        children: [
          Expanded(
            child: Container(
              height: 50,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.03),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: TextField(
                decoration: InputDecoration(
                  hintText: 'Search categories...',
                  hintStyle: GoogleFonts.plusJakartaSans(
                    color: const Color(0xFF94A3B8),
                    fontSize: 14,
                  ),
                  prefixIcon: const Icon(Icons.search, color: Color(0xFF94A3B8)),
                  border: InputBorder.none,
                  enabledBorder: InputBorder.none,
                  focusedBorder: InputBorder.none,
                  contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                ),
              ),
            ),
          ),
          const SizedBox(width: 16),
          GestureDetector(
            onTap: () => _showFilterModal(context),
            child: Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                color: Theme.of(context).primaryColor,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: Theme.of(context).primaryColor.withValues(alpha: 0.3),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: const Icon(Icons.tune_rounded, color: Colors.white),
            ),
          ).animate().scale(delay: 200.ms),
        ],
      ),
    );
  }

  Widget _buildCategoryCard(BuildContext context, dynamic category, int index) {
    final name = category['name'] ?? 'Service';
    final iconValue = category['image'] ?? category['icon'];
    // Handle dynamic color if available, or fallback
    final colorValue = category['color'];
    final color = colorValue is int ? Color(colorValue) : const Color(0xFFEEF2FF); // Default light blue
    
    final isUrl = iconValue is String && (iconValue.startsWith('http') || iconValue.startsWith('assets'));

    return GestureDetector(
      onTap: () {
        if (widget.type == 'freelancer') {
           context.push('/freelancer-category', extra: category);
        } else {
           // Navigate to default/local service sub-categories or services
           // context.push('/service-list', extra: category); // Placeholder
        }
      },
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.03),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
          border: Border.all(
            color: Colors.white,
            width: 2,
          ),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 60,
              height: 60,
              decoration: BoxDecoration(
                color: color.withOpacity(0.1), // Use light version of color
                shape: BoxShape.circle,
              ),
              child: isUrl 
                ? ClipRRect(
                    borderRadius: BorderRadius.circular(30),
                    child: CachedNetworkImage(
                      imageUrl: iconValue, 
                      fit: BoxFit.cover,
                      errorWidget: (context, url, error) => Icon(Icons.category, color: Theme.of(context).primaryColor),
                    )
                  )
                : Icon(
                    iconValue is IconData ? iconValue : Icons.category_rounded,
                    color: Theme.of(context).colorScheme.primary,
                    size: 28,
                  ),
            ),
            const SizedBox(height: 16),
            Text(
              name,
              textAlign: TextAlign.center,
              style: GoogleFonts.plusJakartaSans(
                fontWeight: FontWeight.bold,
                fontSize: 16,
                color: const Color(0xFF1E293B),
              ),
            ),
            const SizedBox(height: 4),
            Text(
              'Explore', // Placeholder for count
              style: GoogleFonts.plusJakartaSans(
                fontSize: 12,
                color: const Color(0xFF94A3B8),
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ),
      ).animate().fadeIn(delay: (50 * index).ms).scale(begin: const Offset(0.9, 0.9)),
    );
  }

  void _showFilterModal(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) => const FilterModal(),
    );
  }
}

class FilterModal extends StatefulWidget {
  const FilterModal({super.key});

  @override
  State<FilterModal> createState() => _FilterModalState();
}

class _FilterModalState extends State<FilterModal> {
  double _priceRange = 100;
  String _selectedSort = 'Recommended';

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.75,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
      ),
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(
            child: Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                color: Colors.grey[300],
                borderRadius: BorderRadius.circular(2),
              ),
            ),
          ),
          const SizedBox(height: 24),
          Text(
            'Filter & Sort',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF1E293B),
            ),
          ),
          const SizedBox(height: 24),
          // Sort Options
          Text('Sort by', style: _sectionTitleStyle),
          const SizedBox(height: 12),
          Wrap(
            spacing: 12,
            runSpacing: 12,
            children: ['Recommended', 'Price: Low to High', 'Price: High to Low', 'Rating']
                .map((e) => _buildChoiceChip(e)).toList(),
          ),
          const SizedBox(height: 24),
          // Price Range
          Text('Price Range (Hourly)', style: _sectionTitleStyle),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('\$10', style: GoogleFonts.plusJakartaSans(color: const Color(0xFF64748B))),
              Text('\$${_priceRange.toInt()}',
                  style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.bold, color: Theme.of(context).colorScheme.primary)),
            ],
          ),
          SliderTheme(
            data: SliderTheme.of(context).copyWith(
              activeTrackColor: Theme.of(context).colorScheme.primary,
              inactiveTrackColor: Colors.grey[200],
              thumbColor: Colors.white,
              thumbShape: const RoundSliderThumbShape(enabledThumbRadius: 12, elevation: 4),
              overlayColor: Theme.of(context).colorScheme.primary.withValues(alpha: 0.1),
            ),
            child: Slider(
              value: _priceRange,
              min: 10,
              max: 500,
              onChanged: (v) => setState(() => _priceRange = v),
            ),
          ),

          const Spacer(),
          Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed: () => context.pop(),
                  style: OutlinedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    side: BorderSide(color: Colors.grey[300]!),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  ),
                  child: Text('Reset',
                      style: GoogleFonts.plusJakartaSans(
                          color: const Color(0xFF64748B), fontWeight: FontWeight.w600)),
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: ElevatedButton(
                  onPressed: () => context.pop(),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Theme.of(context).colorScheme.primary,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    elevation: 0,
                  ),
                  child: Text('Apply Filters',
                      style: GoogleFonts.plusJakartaSans(
                          color: Colors.white, fontWeight: FontWeight.bold)),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  TextStyle get _sectionTitleStyle => GoogleFonts.plusJakartaSans(
        fontSize: 16,
        fontWeight: FontWeight.w600,
        color: const Color(0xFF1E293B),
      );

  Widget _buildChoiceChip(String label) {
    final isSelected = _selectedSort == label;
    return GestureDetector(
      onTap: () => setState(() => _selectedSort = label),
      child: AnimatedContainer(
        duration: 200.ms,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
        decoration: BoxDecoration(
          color: isSelected ? Theme.of(context).colorScheme.primary : Colors.white,
          borderRadius: BorderRadius.circular(30),
          border: Border.all(
            color: isSelected ? Theme.of(context).colorScheme.primary : Colors.grey[200]!,
          ),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: Theme.of(context).colorScheme.primary.withValues(alpha: 0.3),
                    blurRadius: 8,
                    offset: const Offset(0, 4),
                  )
                ]
              : [],
        ),
        child: Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            color: isSelected ? Colors.white : const Color(0xFF64748B),
            fontWeight: FontWeight.w600,
            fontSize: 13,
          ),
        ),
      ),
    );
  }
}
