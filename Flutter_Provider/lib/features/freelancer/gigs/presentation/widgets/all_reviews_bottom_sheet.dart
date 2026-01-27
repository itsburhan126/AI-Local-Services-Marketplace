import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:timeago/timeago.dart' as timeago;
import '../providers/gig_provider.dart';

class AllReviewsBottomSheet extends ConsumerStatefulWidget {
  final int gigId;
  final double averageRating;
  final int totalReviews;

  const AllReviewsBottomSheet({
    super.key,
    required this.gigId,
    required this.averageRating,
    required this.totalReviews,
  });

  @override
  ConsumerState<AllReviewsBottomSheet> createState() => _AllReviewsBottomSheetState();
}

class _AllReviewsBottomSheetState extends ConsumerState<AllReviewsBottomSheet> {
  final ScrollController _scrollController = ScrollController();
  List<dynamic> _reviews = [];
  bool _isLoading = true;
  bool _isLoadingMore = false;
  int _currentPage = 1;
  int _lastPage = 1;
  String? _error;

  @override
  void initState() {
    super.initState();
    _fetchReviews();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200 &&
        !_isLoadingMore &&
        _currentPage < _lastPage) {
      _fetchMoreReviews();
    }
  }

  Future<void> _fetchReviews() async {
    try {
      setState(() {
        _isLoading = true;
        _error = null;
      });

      final repository = ref.read(gigRepositoryProvider);
      final result = await repository.getGigReviews(widget.gigId, 1);

      if (mounted) {
        setState(() {
          _reviews = result.reviews;
          _currentPage = result.currentPage;
          _lastPage = result.lastPage;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  Future<void> _fetchMoreReviews() async {
    try {
      setState(() {
        _isLoadingMore = true;
      });

      final repository = ref.read(gigRepositoryProvider);
      final result = await repository.getGigReviews(widget.gigId, _currentPage + 1);

      if (mounted) {
        setState(() {
          _reviews.addAll(result.reviews);
          _currentPage = result.currentPage;
          _lastPage = result.lastPage;
          _isLoadingMore = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _isLoadingMore = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      constraints: BoxConstraints(
        maxHeight: MediaQuery.of(context).size.height * 0.85,
      ),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        children: [
          // Header
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'All Reviews',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: const Color(0xFF1F2937),
                      ),
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        const Icon(Icons.star_rounded, color: Color(0xFFEAB308), size: 20),
                        const SizedBox(width: 4),
                        Text(
                          '${widget.averageRating}',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: const Color(0xFF1F2937),
                          ),
                        ),
                        Text(
                          ' (${widget.totalReviews} reviews)',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 14,
                            color: const Color(0xFF6B7280),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                IconButton(
                  onPressed: () => Navigator.pop(context),
                  icon: const Icon(Icons.close),
                ),
              ],
            ),
          ),
          const Divider(height: 1),
          
          // Content
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _error != null
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(
                              'Failed to load reviews',
                              style: GoogleFonts.plusJakartaSans(color: Colors.red),
                            ),
                            TextButton(
                              onPressed: _fetchReviews,
                              child: const Text('Retry'),
                            ),
                          ],
                        ),
                      )
                    : _reviews.isEmpty
                        ? Center(
                            child: Text(
                              'No reviews yet',
                              style: GoogleFonts.plusJakartaSans(
                                color: const Color(0xFF9CA3AF),
                              ),
                            ),
                          )
                        : ListView.separated(
                            controller: _scrollController,
                            padding: const EdgeInsets.all(16),
                            itemCount: _reviews.length + (_isLoadingMore ? 1 : 0),
                            separatorBuilder: (context, index) => const Divider(height: 32),
                            itemBuilder: (context, index) {
                              if (index == _reviews.length) {
                                return const Center(
                                  child: Padding(
                                    padding: EdgeInsets.all(8.0),
                                    child: CircularProgressIndicator(),
                                  ),
                                );
                              }
                              
                              final review = _reviews[index];
                              return _buildReviewItem(review);
                            },
                          ),
          ),
        ],
      ),
    );
  }

  Widget _buildReviewItem(dynamic review) {
    final user = review['user'];
    final userName = user != null 
        ? '${user['first_name'] ?? ''} ${user['last_name'] ?? ''}'.trim() 
        : 'Unknown User';
    final userImage = user != null ? user['profile_image'] : null;
    final rating = double.tryParse(review['rating'].toString()) ?? 0.0;
    final comment = review['review'] ?? '';
    final timeAgo = review['created_at'] != null 
        ? timeago.format(DateTime.parse(review['created_at'])) 
        : '';

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            CircleAvatar(
              radius: 20,
              backgroundColor: Colors.grey[200],
              backgroundImage: userImage != null 
                  ? NetworkImage('https://al-services.com/storage/$userImage') 
                  : null,
              child: userImage == null 
                  ? Text(userName.isNotEmpty ? userName[0].toUpperCase() : 'U', 
                      style: const TextStyle(color: Colors.grey)) 
                  : null,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    userName.isNotEmpty ? userName : 'Unknown User',
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.w600,
                      color: const Color(0xFF1F2937),
                    ),
                  ),
                  Row(
                    children: [
                      ...List.generate(5, (index) {
                        return Icon(
                          index < rating ? Icons.star_rounded : Icons.star_outline_rounded,
                          size: 14,
                          color: const Color(0xFFEAB308),
                        );
                      }),
                      const SizedBox(width: 8),
                      Text(
                        timeAgo,
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 12,
                          color: const Color(0xFF9CA3AF),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
        if (comment.isNotEmpty) ...[
          const SizedBox(height: 12),
          Text(
            comment,
            style: GoogleFonts.plusJakartaSans(
              color: const Color(0xFF4B5563),
              height: 1.5,
            ),
          ),
        ],
      ],
    );
  }
}
