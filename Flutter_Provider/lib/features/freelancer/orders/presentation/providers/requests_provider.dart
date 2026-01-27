import 'dart:async';
import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';
import 'package:flutter_provider/features/auth/presentation/providers/auth_provider.dart';
import '../../../../../core/utils/event_bus.dart';
import '../../data/models/order_model.dart';
import '../../data/services/requests_service.dart';

class RequestsProvider extends ChangeNotifier {
  final RequestsService _service;
  AuthProvider? _authProvider;
  
  StreamSubscription? _pusherSubscription;
  bool _isLoading = false;
  List<OrderModel> _pendingOrders = [];
  List<OrderModel> _activeOrders = [];
  List<OrderModel> _completedOrders = [];
  String? _error;

  RequestsProvider(Dio dio) : _service = RequestsService(dio);

  void update(AuthProvider authProvider) {
    _authProvider = authProvider;
    notifyListeners();
  }

  bool get isLoading => _isLoading;
  List<OrderModel> get pendingOrders => _pendingOrders;
  List<OrderModel> get activeOrders => _activeOrders;
  List<OrderModel> get completedOrders => _completedOrders;
  String? get error => _error;

  void setupRealtime(int userId) async {
    _pusherSubscription?.cancel();
    _pusherSubscription = GlobalEventBus.pusherStream.listen((event) {
      debugPrint("RequestsProvider received event: ${event.eventName}");
      if (event.eventName.contains('new-gig-order')) {
         debugPrint("New order received via Pusher. Fetching orders...");
         fetchOrders();
      }
    });
  }

  @override
  void dispose() {
    _pusherSubscription?.cancel();
    super.dispose();
  }

  Future<void> fetchOrders() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = _authProvider?.user?.token;
      if (token == null) {
        _error = "Unauthenticated";
        notifyListeners();
        return;
      }

      // Fetch all categories in parallel or one by one
      // The API supports filtering, so we can make 3 calls or 1 call and filter locally.
      // API supports 'status' param.
      // Let's make 3 calls for simplicity to match tabs, or make 1 call to get all if API supported it.
      // API implementation: if status is provided, it filters. If not, it might return all? 
      // My implementation: if status is provided, it filters. If not, it returns all.
      // Let's fetch all and filter locally to reduce calls? No, pagination might mess that up.
      // Let's fetch per tab as the user navigates? Or fetch all 3 now?
      // Let's fetch all 3 lists to populate the tabs initially.

      final results = await Future.wait([
        _service.getOrders(token, status: 'pending'),
        _service.getOrders(token, status: 'active'),
        _service.getOrders(token, status: 'completed'),
      ]);

      _pendingOrders = results[0];
      _activeOrders = results[1];
      _completedOrders = results[2];
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> acceptOrder(int id) async {
    final token = _authProvider?.user?.token;
    if (token == null) return false;

    // Direct transition to in_progress to skip "Start Order" step as per requirement
    final success = await _service.updateOrderStatus(token, id, 'in_progress'); 
    
    if (success) {
      // Refresh list
      await fetchOrders();
    }
    return success;
  }
  
  Future<bool> startOrder(int id) async {
      final token = _authProvider?.user?.token;
      if (token == null) return false;
      final success = await _service.updateOrderStatus(token, id, 'in_progress');
      if (success) await fetchOrders();
      return success;
  }

  Future<bool> completeOrder(int id) async {
    final token = _authProvider?.user?.token;
    if (token == null) return false;
    final success = await _service.updateOrderStatus(token, id, 'completed');
    if (success) {
      await fetchOrders();
    }
    return success;
  }

  Future<bool> declineOrder(int id) async {
    final token = _authProvider?.user?.token;
    if (token == null) return false;
    final success = await _service.updateOrderStatus(token, id, 'cancelled');
    if (success) {
      await fetchOrders();
    }
    return success;
  }

  Future<bool> deliverWork(int id, String note, List<String> filePaths) async {
    final token = _authProvider?.user?.token;
    if (token == null) return false;
    
    _isLoading = true;
    notifyListeners();

    try {
      final success = await _service.deliverWork(token, id, note, filePaths);
      if (success) {
        await fetchOrders();
      }
      return success;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
