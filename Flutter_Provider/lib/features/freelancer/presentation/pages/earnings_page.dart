import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';

import 'earning_history_page.dart';
import 'withdraw_history_page.dart';
import 'pending_payments_page.dart';

class EarningsPage extends StatefulWidget {
  const EarningsPage({super.key});

  @override
  State<EarningsPage> createState() => _EarningsPageState();
}

class _EarningsPageState extends State<EarningsPage> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final List<_EarningItem> _earningItems = [
    _EarningItem(amount: 120.0, status: EarningStatus.pending, date: DateTime(2024, 1, 20)),
    _EarningItem(amount: 80.0, status: EarningStatus.pending, date: DateTime(2024, 1, 22)),
    _EarningItem(amount: 200.0, status: EarningStatus.cleared, date: DateTime(2024, 1, 15)),
    _EarningItem(amount: 150.0, status: EarningStatus.cleared, date: DateTime(2024, 1, 10)),
  ];
  final double _withdrawnTotal = 150.0;

  double get _pendingAmount => _earningItems
      .where((e) => e.status == EarningStatus.pending)
      .fold(0.0, (sum, e) => sum + e.amount);

  double get _clearedAmount => _earningItems
      .where((e) => e.status == EarningStatus.cleared)
      .fold(0.0, (sum, e) => sum + e.amount);

  double get _totalEarnings => _pendingAmount + _clearedAmount;

  double get _availableForWithdrawal {
    final available = _clearedAmount - _withdrawnTotal;
    return available > 0 ? available : 0;
  }

  double get _earningsInCurrentMonth {
    final now = DateTime.now();
    return _earningItems
        .where((e) => e.date.year == now.year && e.date.month == now.month)
        .fold(0.0, (sum, e) => sum + e.amount);
  }

  double get _avgSellingPrice {
    if (_earningItems.isEmpty) return 0;
    return _totalEarnings / _earningItems.length;
  }

  int get _activeOrdersCount =>
      _earningItems.where((e) => e.status == EarningStatus.pending).length;

  int get _completedOrdersCount =>
      _earningItems.where((e) => e.status == EarningStatus.cleared).length;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final monthName = DateFormat.MMMM().format(DateTime.now());
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.black),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Earnings',
          style: GoogleFonts.plusJakartaSans(
            color: Colors.black,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            const SizedBox(height: 20),
            Text(
              '\$${_availableForWithdrawal.toStringAsFixed(2)}',
              style: GoogleFonts.plusJakartaSans(
                color: const Color(0xFF10B981),
                fontSize: 40,
                fontWeight: FontWeight.bold,
              ),
            ).animate().scale(),
            const SizedBox(height: 8),
            Text(
              'Available for withdrawal',
              style: GoogleFonts.plusJakartaSans(
                color: Colors.black87,
                fontSize: 14,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 20),
            Row(
              children: [
                Expanded(
                  child: _buildSummaryCard(
                    label: 'Pending earnings',
                    amount: _pendingAmount,
                    color: const Color(0xFFF97316),
                    subtitle: 'Payments being cleared',
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: _buildSummaryCard(
                    label: 'Cleared earnings',
                    amount: _clearedAmount,
                    color: const Color(0xFF10B981),
                    subtitle: 'Ready after safety period',
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Align(
              alignment: Alignment.centerLeft,
              child: Text(
                'Total earnings: \$${_totalEarnings.toStringAsFixed(2)}',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: const Color(0xFF64748B),
                ),
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                   ScaffoldMessenger.of(context).showSnackBar(
                     const SnackBar(content: Text('Withdrawal feature coming soon')),
                   );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF10B981),
                  padding: const EdgeInsets.symmetric(vertical: 14),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: Text(
                  'Withdraw Funds',
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                  ),
                ),
              ),
            ),
            
            const SizedBox(height: 40),
            
            // Overview Section
            Align(
              alignment: Alignment.centerLeft,
              child: Text(
                'Overview',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
            const SizedBox(height: 20),
            
            // Tabs
            TabBar(
              controller: _tabController,
              labelColor: Colors.black,
              unselectedLabelColor: Colors.grey,
              indicatorColor: Colors.black,
              labelStyle: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold),
              tabs: const [
                Tab(text: 'Earnings'),
                Tab(text: 'Completed Orders'),
              ],
            ),
            
            SizedBox(
              height: 200,
              child: TabBarView(
                controller: _tabController,
                children: [
                  _buildEarningsChart(),
                  _buildCompletedOrdersView(),
                ],
              ),
            ),
            
            const SizedBox(height: 30),
            
            // Analytics
            _buildAnalyticsRow(
              'Earnings in $monthName',
              '\$${_earningsInCurrentMonth.toStringAsFixed(2)}',
            ),
            _buildAnalyticsRow(
              'Avg. selling price',
              '\$${_avgSellingPrice.toStringAsFixed(2)}',
            ),
            _buildAnalyticsRow(
              'Active orders',
              '$_activeOrdersCount (\$${_pendingAmount.toStringAsFixed(2)})',
            ),
            _buildAnalyticsRow(
              'Completed orders',
              '$_completedOrdersCount (\$${_clearedAmount.toStringAsFixed(2)})',
            ),
            
            const SizedBox(height: 40),
            
            // Revenues Section
            Align(
              alignment: Alignment.centerLeft,
              child: Text(
                'Revenues',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
            const SizedBox(height: 10),
            _buildRevenueTile(
              'Payments being cleared',
              '\$${_pendingAmount.toStringAsFixed(2)}',
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (_) => const PendingPaymentsPage()),
                );
              },
            ),
            const Divider(),
            _buildRevenueTile('Earnings to date', '\$${_totalEarnings.toStringAsFixed(2)}', highlight: true, onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => const EarningHistoryPage()));
            }),
            const Divider(),
            _buildRevenueTile('Expenses to date', '\$0', onTap: () {}),
            const Divider(),
            _buildRevenueTile('Withdrawn to date', '\$${_withdrawnTotal.toStringAsFixed(2)}', highlight: true, onTap: () {
               Navigator.push(context, MaterialPageRoute(builder: (_) => const WithdrawHistoryPage()));
            }),
            
            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }

  Widget _buildEarningsChart() {
    return Container(
      padding: const EdgeInsets.only(top: 20, right: 20),
      child: BarChart(
        BarChartData(
          alignment: BarChartAlignment.spaceAround,
          maxY: 10,
          barTouchData: BarTouchData(enabled: false),
          titlesData: FlTitlesData(
            show: true,
            bottomTitles: AxisTitles(
              sideTitles: SideTitles(showTitles: false),
            ),
            leftTitles: AxisTitles(
              sideTitles: SideTitles(
                showTitles: true,
                getTitlesWidget: (value, meta) {
                  return Text(
                    '\$${value.toInt()}',
                    style: const TextStyle(
                      color: Colors.grey,
                      fontWeight: FontWeight.bold,
                      fontSize: 10,
                    ),
                  );
                },
                reservedSize: 28,
              ),
            ),
            topTitles: AxisTitles(sideTitles: SideTitles(showTitles: false)),
            rightTitles: AxisTitles(sideTitles: SideTitles(showTitles: false)),
          ),
          gridData: FlGridData(
            show: true,
            drawVerticalLine: false,
            horizontalInterval: 2,
            getDrawingHorizontalLine: (value) {
              return FlLine(
                color: Colors.grey.withOpacity(0.2),
                strokeWidth: 1,
                dashArray: [5, 5],
              );
            },
          ),
          borderData: FlBorderData(show: false),
          barGroups: [
            BarChartGroupData(x: 0, barRods: [BarChartRodData(toY: 6, color: Colors.transparent)]), // Spacer/Max
            BarChartGroupData(x: 1, barRods: [BarChartRodData(toY: 4, color: Colors.green, width: 16, borderRadius: BorderRadius.circular(4))]),
            BarChartGroupData(x: 2, barRods: [BarChartRodData(toY: 2, color: Colors.green, width: 16, borderRadius: BorderRadius.circular(4))]),
            BarChartGroupData(x: 3, barRods: [BarChartRodData(toY: 5, color: Colors.green, width: 16, borderRadius: BorderRadius.circular(4))]),
            BarChartGroupData(x: 4, barRods: [BarChartRodData(toY: 3, color: Colors.green, width: 16, borderRadius: BorderRadius.circular(4))]),
            BarChartGroupData(x: 5, barRods: [BarChartRodData(toY: 7, color: Colors.green, width: 16, borderRadius: BorderRadius.circular(4))]),
          ],
        ),
      ),
    );
  }

  Widget _buildCompletedOrdersView() {
    final completed = _earningItems
        .where((e) => e.status == EarningStatus.cleared)
        .toList();

    if (completed.isEmpty) {
      return Center(
        child: Text(
          'No completed orders yet',
          style: GoogleFonts.plusJakartaSans(color: Colors.grey),
        ),
      );
    }

    return ListView.separated(
      padding: const EdgeInsets.only(top: 12),
      itemCount: completed.length,
      separatorBuilder: (_, __) => const SizedBox(height: 8),
      itemBuilder: (context, index) {
        final item = completed[index];
        final dateText = DateFormat('MMM d, yyyy').format(item.date);

        return Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.04),
                blurRadius: 8,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Completed order',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 14,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF111827),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    dateText,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 12,
                      color: const Color(0xFF9CA3AF),
                    ),
                  ),
                ],
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    '\$${item.amount.toStringAsFixed(2)}',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 15,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF10B981),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: const Color(0xFFDCFCE7),
                      borderRadius: BorderRadius.circular(999),
                    ),
                    child: Text(
                      'Cleared',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF166534),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildAnalyticsRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 15,
              color: Colors.black87,
            ),
          ),
          Text(
            value,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 15,
              fontWeight: FontWeight.bold,
              color: Colors.black,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryCard({
    required String label,
    required double amount,
    required Color color,
    required String subtitle,
  }) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 12,
            offset: const Offset(0, 6),
          ),
        ],
        border: Border.all(color: color.withOpacity(0.1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: const Color(0xFF64748B),
            ),
          ),
          const SizedBox(height: 6),
          Text(
            '\$${amount.toStringAsFixed(2)}',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.w800,
              color: color,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            subtitle,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 11,
              fontWeight: FontWeight.w500,
              color: const Color(0xFF9CA3AF),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRevenueTile(String title, String value, {bool highlight = false, VoidCallback? onTap}) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 16.0),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              title,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 16,
                color: Colors.black87,
              ),
            ),
            Row(
              children: [
                Text(
                  value,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: highlight ? const Color(0xFF10B981) : Colors.grey,
                  ),
                ),
                const SizedBox(width: 8),
                const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.grey),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

enum EarningStatus { pending, cleared }

class _EarningItem {
  final double amount;
  final EarningStatus status;
  final DateTime date;

  _EarningItem({
    required this.amount,
    required this.status,
    required this.date,
  });
}
