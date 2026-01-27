import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:flutter_customer/features/auth/presentation/providers/auth_provider.dart';
import '../../data/booking_service.dart';
import 'package:flutter_customer/features/freelancer/data/services/gig_service.dart';

class BookingProvider with ChangeNotifier {
  final BookingService _bookingService;
  final GigService _gigService = GigService();
  
  AuthProvider? _authProvider;

  BookingProvider(Dio dio) : _bookingService = BookingService(dio);

  void update(AuthProvider authProvider) {
    _authProvider = authProvider;
    notifyListeners();
  }
  
  List<dynamic> _activeBookings = [];
  List<dynamic> _completedBookings = [];
  List<dynamic> _cancelledBookings = [];
  bool _isLoading = false;
  String? _error;

  List<dynamic> get activeBookings => _activeBookings;
  List<dynamic> get completedBookings => _completedBookings;
  List<dynamic> get cancelledBookings => _cancelledBookings;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> loadBookings() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = _authProvider?.user?.token;
      if (token == null) {
          // Clear bookings if no token
          _activeBookings = [];
          _completedBookings = [];
          _cancelledBookings = [];
          _isLoading = false;
          notifyListeners();
          return;
      }

      final results = await Future.wait([
        _bookingService.getBookings(token, status: 'active'),
        _bookingService.getBookings(token, status: 'completed'),
        _bookingService.getBookings(token, status: 'cancelled'),
      ]);

      _activeBookings = results[0];
      _completedBookings = results[1];
      _cancelledBookings = results[2];
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> createBooking(Map<String, dynamic> data) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = _authProvider?.user?.token;
      if (token == null) {
         throw Exception("Authentication required");
      }

      await _bookingService.createBooking(token, data);
      await loadBookings(); // Refresh list
      return true;
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> deliverWork(String orderId, String note, List<String>? files) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = _authProvider?.user?.token;
      if (token == null) throw Exception("Authentication required");

      await _bookingService.deliverWork(token, orderId, note, files);
      await loadBookings();
      return true;
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> approveWork(String orderId) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = _authProvider?.user?.token;
      if (token == null) throw Exception("Authentication required");

      await _bookingService.approveWork(token, orderId);
      await loadBookings();
      return true;
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> rejectWork(String orderId) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = _authProvider?.user?.token;
      if (token == null) throw Exception("Authentication required");

      await _bookingService.rejectWork(token, orderId);
      await loadBookings();
      return true;
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> submitReview(String orderId, int rating, String review) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = _authProvider?.user?.token;
      if (token == null) throw Exception("Authentication required");

      await _bookingService.submitReview(token, orderId, rating, review);
      await loadBookings();
      return true;
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
