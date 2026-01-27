import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';

import 'earning_history_page.dart';
import 'withdraw_history_page.dart';

class EarningsPage extends StatefulWidget {
  const EarningsPage({super.key});

  @override
  State<EarningsPage> createState() => _EarningsPageState();
}

class _EarningsPageState extends State<EarningsPage> with SingleTickerProviderStateMixin {
  late TabController _tabController;

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
            // Available for withdrawal
            const SizedBox(height: 20),
            Text(
              '\$611.20',
              style: GoogleFonts.plusJakartaSans(
                color: const Color(0xFF10B981), // Green color
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
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                   // Navigate to withdraw page or show modal
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
                  _buildOrdersChart(),
                ],
              ),
            ),
            
            const SizedBox(height: 30),
            
            // Analytics
            _buildAnalyticsRow('Earnings in January', '\$24'),
            _buildAnalyticsRow('Avg. selling price', '\$34.03'),
            _buildAnalyticsRow('Active orders', '0 (\$0)'),
            _buildAnalyticsRow('Completed orders', '0 (\$0)'),
            
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
            _buildRevenueTile('Payments being cleared', '\$0', onTap: () {}),
            const Divider(),
            _buildRevenueTile('Earnings to date', '\$611.20', highlight: true, onTap: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => const EarningHistoryPage()));
            }),
            const Divider(),
            _buildRevenueTile('Expenses to date', '\$0', onTap: () {}),
            const Divider(),
            _buildRevenueTile('Withdrawn to date', '\$611.20', highlight: true, onTap: () {
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

    Widget _buildOrdersChart() {
    return Center(
      child: Text(
        'No orders yet',
        style: GoogleFonts.plusJakartaSans(color: Colors.grey),
      ),
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
