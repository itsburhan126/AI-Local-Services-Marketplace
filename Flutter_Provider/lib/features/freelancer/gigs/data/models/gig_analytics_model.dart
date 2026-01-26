class GigAnalyticsModel {
  final List<ChartData> salesChart;
  final List<ChartData> ordersChart;
  final double totalEarnings;
  final double todayEarnings;
  final double earningsChange;
  final List<dynamic> recentOrders;
  final int viewCount;
  final int bookingsCount;

  GigAnalyticsModel({
    required this.salesChart,
    required this.ordersChart,
    required this.totalEarnings,
    required this.todayEarnings,
    required this.earningsChange,
    required this.recentOrders,
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
      todayEarnings: double.tryParse(json['today_earnings'].toString()) ?? 0.0,
      earningsChange: double.tryParse(json['earnings_change'].toString()) ?? 0.0,
      recentOrders: json['recent_orders'] ?? [],
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
