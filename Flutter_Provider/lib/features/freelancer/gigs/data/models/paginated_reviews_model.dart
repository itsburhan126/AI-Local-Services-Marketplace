class PaginatedReviewsModel {
  final int currentPage;
  final int lastPage;
  final int total;
  final List<dynamic> reviews;

  PaginatedReviewsModel({
    required this.currentPage,
    required this.lastPage,
    required this.total,
    required this.reviews,
  });

  factory PaginatedReviewsModel.fromJson(Map<String, dynamic> json) {
    return PaginatedReviewsModel(
      currentPage: json['current_page'] ?? 1,
      lastPage: json['last_page'] ?? 1,
      total: json['total'] ?? 0,
      reviews: json['data'] ?? [],
    );
  }
}
