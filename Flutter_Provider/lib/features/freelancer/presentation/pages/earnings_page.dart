import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';

import '../../wallet/data/models/wallet_transaction_model.dart';
import '../../wallet/presentation/providers/wallet_provider.dart';
import 'earning_history_page.dart';
import 'withdraw_history_page.dart';
import 'pending_payments_page.dart';

import 'package:shimmer/shimmer.dart';

class EarningsPage extends StatefulWidget {
  const EarningsPage({super.key});

  @override
  State<EarningsPage> createState() => _EarningsPageState();
}

class _EarningsPageState extends State<EarningsPage>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<WalletProvider>().fetchWallet();
    });
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
      body: Consumer<WalletProvider>(
        builder: (context, walletProvider, child) {
          if (walletProvider.isLoading) {
            return SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Shimmer.fromColors(
                baseColor: Colors.grey[300]!,
                highlightColor: Colors.grey[100]!,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    const SizedBox(height: 20),
                    Container(
                      width: 150,
                      height: 40,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Container(
                      width: 100,
                      height: 14,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(4),
                      ),
                    ),
                    const SizedBox(height: 20),
                    Row(
                      children: [
                        Expanded(
                          child: Container(
                            height: 100,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                            ),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Container(
                            height: 100,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 40),
                    Container(
                      width: double.infinity,
                      height: 50,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    const SizedBox(height: 40),
                    Align(
                      alignment: Alignment.centerLeft,
                      child: Container(
                        width: 100,
                        height: 20,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(4),
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),
                    Container(
                      width: double.infinity,
                      height: 220,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                  ],
                ),
              ),
            );
          }

          if (walletProvider.error != null) {
            return Center(
              child: Text(
                walletProvider.error!,
                style: GoogleFonts.plusJakartaSans(
                  color: Colors.red,
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                ),
              ),
            );
          }

          final availableForWithdrawal = walletProvider.walletBalance;
          final pendingAmount = walletProvider.pendingBalance;
          final totalEarnings = walletProvider.earningsToDate;
          final withdrawnTotal = walletProvider.withdrawnTotal;
          final clearedAmount = availableForWithdrawal + withdrawnTotal;

          final transactions = walletProvider.transactions;
          final now = DateTime.now();

          final earningsInCurrentMonth = transactions
              .where((t) =>
                  t.type == 'credit' &&
                  t.createdAt.year == now.year &&
                  t.createdAt.month == now.month)
              .fold(0.0, (sum, t) => sum + t.amount);

          final completedOrders = transactions
              .where((t) =>
                  t.type == 'credit' &&
                  t.referenceType == 'gig_order')
              .toList();
          final completedOrdersCount = completedOrders.length;

          final pendingClearanceCount =
              transactions.where((t) => t.status == 'pending').length;

          final clearedOrdersCount =
              transactions.where((t) => t.status == 'completed' || t.status == 'cleared').length;

          final completedEarnings = completedOrders.fold(0.0, (sum, t) => sum + t.amount);

          final avgSellingPrice = completedOrdersCount > 0
              ? completedEarnings / completedOrdersCount
              : 0.0;

          return SingleChildScrollView(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                const SizedBox(height: 20),
                Text(
                  '\$${availableForWithdrawal.toStringAsFixed(2)}',
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
                        amount: pendingAmount,
                        color: const Color(0xFFF97316),
                        subtitle: 'Payments being cleared',
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: _buildSummaryCard(
                        label: 'Cleared earnings',
                        amount: clearedAmount,
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
                    'Total earnings: \$${totalEarnings.toStringAsFixed(2)}',
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
                        const SnackBar(
                          content: Text('Withdrawal feature coming soon'),
                        ),
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
                TabBar(
                  controller: _tabController,
                  labelColor: Colors.black,
                  unselectedLabelColor: Colors.grey,
                  indicatorColor: Colors.black,
                  labelStyle: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                  ),
                  tabs: const [
                    Tab(text: 'Earnings'),
                    Tab(text: 'Completed Orders'),
                  ],
                ),
                SizedBox(
                  height: 220,
                  child: TabBarView(
                    controller: _tabController,
                    children: [
                      _buildEarningsChart(transactions),
                      _buildCompletedOrdersView(completedOrders),
                    ],
                  ),
                ),
                const SizedBox(height: 30),
                _buildAnalyticsRow(
                  'Earnings in $monthName',
                  '\$${earningsInCurrentMonth.toStringAsFixed(2)}',
                ),
                _buildAnalyticsRow(
                  'Avg. selling price',
                  '\$${avgSellingPrice.toStringAsFixed(2)}',
                ),
                _buildAnalyticsRow(
                  'Pending Clearance',
                  '$pendingClearanceCount (\$${pendingAmount.toStringAsFixed(2)})',
                ),
                _buildAnalyticsRow(
                  'Cleared Orders',
                  '$clearedOrdersCount (\$${clearedAmount.toStringAsFixed(2)})',
                ),
                const SizedBox(height: 40),
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
                  '\$${pendingAmount.toStringAsFixed(2)}',
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => const PendingPaymentsPage(),
                      ),
                    );
                  },
                ),
                const Divider(),
                _buildRevenueTile(
                  'Earnings to date',
                  '\$${totalEarnings.toStringAsFixed(2)}',
                  highlight: true,
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => const EarningHistoryPage(),
                      ),
                    );
                  },
                ),
                const Divider(),
                _buildRevenueTile(
                  'Expenses to date',
                  '\$0',
                  onTap: () {},
                ),
                const Divider(),
                _buildRevenueTile(
                  'Withdrawn to date',
                  '\$${withdrawnTotal.toStringAsFixed(2)}',
                  highlight: true,
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => const WithdrawHistoryPage(),
                      ),
                    );
                  },
                ),
                const SizedBox(height: 40),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildEarningsChart(List<WalletTransactionModel> transactions) {
    final now = DateTime.now();
    final dailyEarnings = List<double>.filled(7, 0.0);
    double maxEarning = 0;

    for (var i = 0; i < 7; i++) {
      final day = now.subtract(Duration(days: 6 - i));
      final sum = transactions
          .where(
            (t) =>
                t.type == 'credit' &&
                t.createdAt.year == day.year &&
                t.createdAt.month == day.month &&
                t.createdAt.day == day.day,
          )
          .fold(0.0, (prev, t) => prev + t.amount);
      dailyEarnings[i] = sum;
      if (sum > maxEarning) {
        maxEarning = sum;
      }
    }

    final maxY = maxEarning > 0 ? maxEarning * 1.2 : 10.0;

    return Container(
      padding: const EdgeInsets.only(top: 20, right: 20),
      child: BarChart(
        BarChartData(
          alignment: BarChartAlignment.spaceAround,
          maxY: maxY,
          barTouchData: BarTouchData(
            enabled: true,
            touchTooltipData: BarTouchTooltipData(
              getTooltipColor: (_) => Colors.black87,
              getTooltipItem: (group, groupIndex, rod, rodIndex) {
                return BarTooltipItem(
                  '\$${rod.toY.toStringAsFixed(0)}',
                  const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                  ),
                );
              },
            ),
          ),
          titlesData: FlTitlesData(
            show: true,
            bottomTitles: AxisTitles(
              sideTitles: SideTitles(
                showTitles: true,
                getTitlesWidget: (value, meta) {
                  final index = value.toInt();
                  if (index < 0 || index >= 7) {
                    return const SizedBox.shrink();
                  }
                  final date = now.subtract(Duration(days: 6 - index));
                  return Padding(
                    padding: const EdgeInsets.only(top: 8),
                    child: Text(
                      DateFormat('E').format(date)[0],
                      style: const TextStyle(
                        color: Colors.grey,
                        fontWeight: FontWeight.bold,
                        fontSize: 12,
                      ),
                    ),
                  );
                },
              ),
            ),
            leftTitles: AxisTitles(
              sideTitles: SideTitles(
                showTitles: true,
                getTitlesWidget: (value, meta) {
                  if (value == 0) {
                    return const SizedBox.shrink();
                  }
                  return Text(
                    '\$${value.toInt()}',
                    style: const TextStyle(
                      color: Colors.grey,
                      fontWeight: FontWeight.bold,
                      fontSize: 10,
                    ),
                  );
                },
                reservedSize: 32,
                interval: maxY / 4,
              ),
            ),
            topTitles: const AxisTitles(
              sideTitles: SideTitles(showTitles: false),
            ),
            rightTitles: const AxisTitles(
              sideTitles: SideTitles(showTitles: false),
            ),
          ),
          gridData: FlGridData(
            show: true,
            drawVerticalLine: false,
            horizontalInterval: maxY / 5,
            getDrawingHorizontalLine: (value) {
              return FlLine(
                color: Colors.grey.withOpacity(0.1),
                strokeWidth: 1,
              );
            },
          ),
          borderData: FlBorderData(show: false),
          barGroups: List.generate(7, (index) {
            return BarChartGroupData(
              x: index,
              barRods: [
                BarChartRodData(
                  toY: dailyEarnings[index],
                  color: const Color(0xFF10B981),
                  width: 12,
                  borderRadius: const BorderRadius.vertical(
                    top: Radius.circular(4),
                  ),
                  backDrawRodData: BackgroundBarChartRodData(
                    show: true,
                    toY: maxY,
                    color: const Color(0xFFF1F5F9),
                  ),
                ),
              ],
            );
          }),
        ),
      ),
    );
  }

  Widget _buildCompletedOrdersView(
    List<WalletTransactionModel> completedTransactions,
  ) {
    if (completedTransactions.isEmpty) {
      return Center(
        child: Text(
          'No completed orders yet',
          style: GoogleFonts.plusJakartaSans(
            color: Colors.grey,
          ),
        ),
      );
    }

    return ListView.separated(
      padding: const EdgeInsets.only(top: 12),
      itemCount: completedTransactions.length,
      separatorBuilder: (_, __) => const SizedBox(height: 8),
      itemBuilder: (context, index) {
        final item = completedTransactions[index];
        final dateText = DateFormat('MMM d, yyyy').format(item.createdAt);

        final isCleared =
            item.status == 'completed' || item.status == 'cleared';

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
                    'Order #${item.referenceId ?? 'N/A'}',
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
                    padding: const EdgeInsets.symmetric(
                      horizontal: 8,
                      vertical: 4,
                    ),
                    decoration: BoxDecoration(
                      color: isCleared
                          ? const Color(0xFFDCFCE7)
                          : const Color(0xFFFEF3C7),
                      borderRadius: BorderRadius.circular(999),
                    ),
                    child: Text(
                      isCleared ? 'Cleared' : 'Pending',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                        color: isCleared
                            ? const Color(0xFF166534)
                            : const Color(0xFFB45309),
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
      padding: const EdgeInsets.symmetric(vertical: 8),
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
        border: Border.all(
          color: color.withOpacity(0.1),
        ),
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

  Widget _buildRevenueTile(
    String label,
    String value, {
    bool highlight = false,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 12),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              label,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 15,
                color: Colors.black87,
                fontWeight: FontWeight.w500,
              ),
            ),
            Row(
              children: [
                Text(
                  value,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 15,
                    fontWeight: FontWeight.bold,
                    color: highlight ? const Color(0xFF10B981) : Colors.black,
                  ),
                ),
                const SizedBox(width: 8),
                const Icon(
                  Icons.arrow_forward_ios,
                  size: 14,
                  color: Colors.grey,
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
