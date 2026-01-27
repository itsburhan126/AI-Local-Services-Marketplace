class GigAnalyticsModel {
  final List<ChartData> salesChart;
  final List<ChartData> ordersChart;
  final double totalEarnings;
  final double pendingAmount;
  final double clearanceAmount;
  final double clearedAmount;
  final double todayEarnings;
  final double earningsChange;
  final int activeOrders;
  final int completedOrders;
  final int pendingOrders;
  final double averageRating;
  final int totalReviews;
  final List<dynamic> recentOrders;
  final List<dynamic> recentReviews;
  final int viewCount;
  final int bookingsCount;

  GigAnalyticsModel({
    required this.salesChart,
    required this.ordersChart,
    required this.totalEarnings,
    required this.pendingAmount,
    required this.clearanceAmount,
    required this.clearedAmount,
    required this.todayEarnings,
    required this.earningsChange,
    required this.activeOrders,
    required this.completedOrders,
    required this.pendingOrders,
    required this.averageRating,
    required this.totalReviews,
    required this.recentOrders,
    required this.recentReviews,
    required this.viewCount,
    required this.bookingsCount,
  });

  factory GigAnalyticsModel.fromJson(Map<String, dynamic> json) {
    return GigAnalyticsModel(
      salesChart: (json['sales_chart'] as List)
          .map((e) => ChartData.fromJson(e))
          .toList(),
      ordersChart: (json['orders_chart'] as List)
          .map((e) => ChartData.fromJson(e))
          .toList(),
      totalEarnings: double.tryParse(json['total_earnings'].toString()) ?? 0.0,
      pendingAmount: double.tryParse(json['pending_amount'].toString()) ?? 0.0,
      clearanceAmount: double.tryParse(json['clearance_amount'].toString()) ?? 0.0,
      clearedAmount: double.tryParse(json['cleared_amount'].toString()) ?? 0.0,
      todayEarnings: double.tryParse(json['today_earnings'].toString()) ?? 0.0,
      earningsChange: double.tryParse(json['earnings_change'].toString()) ?? 0.0,
      activeOrders: int.tryParse(json['active_orders'].toString()) ?? 0,
      completedOrders: int.tryParse(json['completed_orders'].toString()) ?? 0,
      pendingOrders: int.tryParse(json['pending_orders'].toString()) ?? 0,
      averageRating: double.tryParse(json['average_rating'].toString()) ?? 0.0,
      totalReviews: int.tryParse(json['total_reviews'].toString()) ?? 0,
      recentOrders: json['recent_orders'] ?? [],
      recentReviews: json['recent_reviews'] ?? [],
      viewCount: int.tryParse(json['view_count'].toString()) ?? 0,
      bookingsCount: int.tryParse(json['bookings_count'].toString()) ?? 0,
    );
  }
}

class ChartData {
  final String date;
  final double value;

  ChartData({required this.date, required this.value});

  factory ChartData.fromJson(Map<String, dynamic> json) {
    return ChartData(
      date: json['date'],
      value: double.tryParse(json['value'].toString()) ?? 0.0,
    );
  }
}
