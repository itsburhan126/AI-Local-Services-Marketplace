import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';

import 'package:go_router/go_router.dart';
import '../widgets/filter_modal.dart';

class SearchPage extends StatefulWidget {
  const SearchPage({super.key});

  @override
  State<SearchPage> createState() => _SearchPageState();
}

class _SearchPageState extends State<SearchPage> {
  final _searchController = TextEditingController();

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        SliverAppBar(
          floating: true,
          pinned: true,
          backgroundColor: const Color(0xFFF8FAFC).withValues(alpha: 0.8),
          elevation: 0,
          toolbarHeight: 80,
          title: Container(
            margin: const EdgeInsets.only(top: 8),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(20),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.05),
                  blurRadius: 10,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: 'Search services, providers...',
                hintStyle: TextStyle(color: Colors.grey[400]),
                prefixIcon: Icon(Icons.search, color: Theme.of(context).colorScheme.primary),
                suffixIcon: IconButton(
                  icon: const Icon(Icons.filter_list_rounded),
                  color: Theme.of(context).colorScheme.primary,
                  onPressed: () {
                    showModalBottomSheet(
                      context: context,
                      isScrollControlled: true,
                      backgroundColor: Colors.transparent,
                      builder: (context) => const FilterModal(),
                    );
                  },
                ),
                border: InputBorder.none,
                contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
              ),
            ),
          ),
        ),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Recent Searches',
                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1E293B),
                      ),
                ),
                const SizedBox(height: 12),
                Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: [
                    'House Cleaning',
                    'Plumber',
                    'AC Repair',
                    'Electrician'
                  ].map((search) => _buildSearchChip(search)).toList(),
                ),
                const SizedBox(height: 32),
                Text(
                  'Popular Categories',
                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1E293B),
                      ),
                ),
                const SizedBox(height: 16),
                _buildPopularCategories(),
                const SizedBox(height: 120),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSearchChip(String label) {
    return GestureDetector(
      onTap: () => context.push('/service-details'),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.grey[200]!),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.history, size: 16, color: Colors.grey[400]),
            const SizedBox(width: 8),
            Flexible(
              child: Text(
                label,
                style: const TextStyle(
                  color: Color(0xFF64748B),
                  fontWeight: FontWeight.w500,
                ),
                overflow: TextOverflow.ellipsis,
              ),
            ),
          ],
        ),
      ).animate().fadeIn().scale(),
    );
  }

  Widget _buildPopularCategories() {
    final categories = [
      {'icon': Icons.cleaning_services, 'label': 'Cleaning', 'color': 0xFF6366F1},
      {'icon': Icons.plumbing, 'label': 'Plumbing', 'color': 0xFFEC4899},
      {'icon': Icons.electric_bolt, 'label': 'Electric', 'color': 0xFFF59E0B},
      {'icon': Icons.format_paint, 'label': 'Painting', 'color': 0xFF10B981},
      {'icon': Icons.spa, 'label': 'Beauty', 'color': 0xFF8B5CF6},
      {'icon': Icons.local_shipping, 'label': 'Moving', 'color': 0xFFEF4444},
    ];

    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 3,
        crossAxisSpacing: 16,
        mainAxisSpacing: 16,
        childAspectRatio: 0.9,
      ),
      itemCount: categories.length,
      itemBuilder: (context, index) {
        final cat = categories[index];
        return Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withValues(alpha: 0.02),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Color(cat['color'] as int).withValues(alpha: 0.1),
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  cat['icon'] as IconData,
                  color: Color(cat['color'] as int),
                  size: 24,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                cat['label'] as String,
                style: const TextStyle(
                  fontWeight: FontWeight.w600,
                  fontSize: 12,
                  color: Color(0xFF475569),
                ),
              ),
            ],
          ),
        ).animate().fadeIn(delay: (index * 50).ms).scale(delay: (index * 50).ms);
      },
    );
  }
}
