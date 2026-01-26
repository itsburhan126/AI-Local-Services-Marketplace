import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:dio/dio.dart';
import 'package:timeago/timeago.dart' as timeago;
import '../../../../auth/presentation/providers/auth_provider.dart';
import '../providers/requests_provider.dart';
import '../../data/models/order_model.dart';
import 'order_details_page.dart';

class RequestsPage extends StatefulWidget {
  const RequestsPage({super.key});

  @override
  State<RequestsPage> createState() => _RequestsPageState();
}

class _RequestsPageState extends State<RequestsPage> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      final provider = context.read<RequestsProvider>();
      provider.fetchOrders();
      final authProvider = context.read<AuthProvider>();
      if (authProvider.user != null && authProvider.user!.id != null) {
        provider.setupRealtime(authProvider.user!.id!);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final isFreelancer = authProvider.user?.mode == 'freelancer';
    final title = isFreelancer ? 'Orders' : 'Job Requests';

    return DefaultTabController(
      length: 3,
      child: Scaffold(
        appBar: AppBar(
          title: Text(
            title,
            style: GoogleFonts.plusJakartaSans(
              fontWeight: FontWeight.bold,
              color: const Color(0xFF1E293B),
            ),
          ),
          bottom: TabBar(
            labelStyle: GoogleFonts.plusJakartaSans(
              fontWeight: FontWeight.w600,
            ),
            unselectedLabelStyle: GoogleFonts.plusJakartaSans(),
            labelColor: Theme.of(context).primaryColor,
            indicatorColor: Theme.of(context).primaryColor,
            tabs: const [
              Tab(text: 'Pending'),
              Tab(text: 'Active'),
              Tab(text: 'Completed'),
            ],
          ),
          actions: [
            IconButton(
              icon: const Icon(Icons.refresh),
              onPressed: () => context.read<RequestsProvider>().fetchOrders(),
            ),
          ],
        ),
        body: Consumer<RequestsProvider>(
          builder: (context, provider, child) {
            if (provider.isLoading) {
              return const Center(child: CircularProgressIndicator());
            }
            
            if (provider.error != null) {
              return Center(child: Text('Error: ${provider.error}'));
            }

            return TabBarView(
              children: [
                _buildRequestList(context, provider.pendingOrders, 'pending'),
                _buildRequestList(context, provider.activeOrders, 'active'),
                _buildRequestList(context, provider.completedOrders, 'completed'),
              ],
            );
          },
        ),
      ),
    );
  }

  Widget _buildRequestList(BuildContext context, List<OrderModel> orders, String status) {
    if (orders.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.inbox_outlined, size: 64, color: Colors.grey[300]),
            const SizedBox(height: 16),
            Text(
              'No $status orders',
              style: GoogleFonts.plusJakartaSans(
                color: Colors.grey[500],
                fontSize: 16,
              ),
            ),
          ],
        ),
      );
    }

    return ListView.separated(
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
      itemCount: orders.length,
      separatorBuilder: (context, index) => const SizedBox(height: 16),
      itemBuilder: (context, index) {
        return _buildRequestCard(context, orders[index], index, status);
      },
    );
  }

  Widget _buildRequestCard(BuildContext context, OrderModel order, int index, String status) {
    Color statusColor;
    String statusText;

    switch (order.status) {
      case 'in_progress':
      case 'accepted':
        statusColor = Colors.green;
        statusText = 'In Progress';
        break;
      case 'completed':
        statusColor = Colors.blue;
        statusText = 'Completed';
        break;
      case 'cancelled':
        statusColor = Colors.red;
        statusText = 'Cancelled';
        break;
      default:
        statusColor = Colors.orange;
        statusText = 'Pending';
    }

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(20),
          onTap: () {
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => OrderDetailsPage(order: order),
              ),
            );
          },
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 10,
                        vertical: 5,
                      ),
                      decoration: BoxDecoration(
                        color: statusColor.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        statusText,
                        style: GoogleFonts.plusJakartaSans(
                          color: statusColor,
                          fontWeight: FontWeight.bold,
                          fontSize: 12,
                        ),
                      ),
                    ),
                    Text(
                      timeago.format(order.createdAt),
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.grey[400],
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Container(
                      width: 50,
                      height: 50,
                      decoration: BoxDecoration(
                        color: Colors.grey[200],
                        shape: BoxShape.circle,
                        image: order.user?.profileImage != null
                            ? DecorationImage(
                                image: NetworkImage(order.user!.profileImage!),
                                fit: BoxFit.cover,
                              )
                            : null,
                      ),
                      child: order.user?.profileImage == null
                          ? const Icon(Icons.person, color: Colors.grey)
                          : null,
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            order.user?.name ?? 'Unknown User',
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                              color: const Color(0xFF1E293B),
                            ),
                          ),
                          Text(
                            order.gig?.title ?? order.package?.name ?? 'Service',
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.plusJakartaSans(
                              color: Colors.grey[500],
                              fontSize: 14,
                            ),
                          ),
                        ],
                      ),
                    ),
                    Text(
                      '\$${order.totalAmount.toStringAsFixed(0)}',
                      style: GoogleFonts.plusJakartaSans(
                        fontWeight: FontWeight.bold,
                        fontSize: 18,
                        color: Theme.of(context).primaryColor,
                      ),
                    ),
                  ],
                ),
                if (status == 'pending') ...[
                  const SizedBox(height: 16),
                  const Divider(),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: OutlinedButton(
                          onPressed: () {
                              context.read<RequestsProvider>().declineOrder(order.id);
                          },
                          style: OutlinedButton.styleFrom(
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            side: BorderSide(
                              color: Colors.red.withOpacity(0.5),
                            ),
                            foregroundColor: Colors.red,
                          ),
                          child: const Text('Decline'),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: ElevatedButton(
                          onPressed: () {
                              context.read<RequestsProvider>().acceptOrder(order.id);
                          },
                          style: ElevatedButton.styleFrom(
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            backgroundColor: Theme.of(context).primaryColor,
                            foregroundColor: Colors.white,
                          ),
                          child: const Text('Accept'),
                        ),
                      ),
                    ],
                  ),
                ],
                if (status == 'active' && order.status != 'completed') ...[
                   const SizedBox(height: 16),
                   const Divider(),
                   const SizedBox(height: 12),
                   SizedBox(
                     width: double.infinity,
                     child: ElevatedButton(
                          onPressed: () {
                              context.read<RequestsProvider>().completeOrder(order.id);
                          },
                          style: ElevatedButton.styleFrom(
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            backgroundColor: Colors.green,
                            foregroundColor: Colors.white,
                          ),
                          child: const Text('Mark as Completed'),
                        ),
                   ),
                ]
              ],
            ),
          ),
        ),
      ),
    ).animate().fadeIn(delay: (index * 100).ms).slideY(begin: 0.1, end: 0);
  }
}
