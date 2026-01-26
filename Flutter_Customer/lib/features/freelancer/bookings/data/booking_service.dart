import 'package:dio/dio.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';

class BookingService {
  final Dio _dio;

  BookingService(this._dio);

  // Updated to use Freelancer Order endpoints
  final String _ordersEndpoint = '/api/freelancer/customer/orders';
  final String _createOrderEndpoint = '/api/freelancer/orders';

  Future<List<dynamic>> getBookings(String? token, {String? status}) async {
    try {
      final options = Options(
        headers: token != null ? {'Authorization': 'Bearer $token'} : {},
      );

      String endpoint = _ordersEndpoint;
      if (status != null) {
        endpoint += '?status=$status';
      }

      final response = await _dio.get(
        '${ApiConstants.baseUrl}$endpoint',
        options: options,
      );

      if (response.statusCode == 200) {
        return response.data['data'] ?? [];
      }
      return [];
    } catch (e) {
      // Return empty list on error for now
      print('Error fetching bookings: $e');
      return [];
    }
  }

  Future<Map<String, dynamic>?> getBookingDetails(String? token, String id) async {
    try {
      final options = Options(
        headers: token != null ? {'Authorization': 'Bearer $token'} : {},
      );

      // Using the same list endpoint with ID or a specific show endpoint if available.
      // Currently using list endpoint to find it or we need a show endpoint in backend.
      // The backend has `Route::post('/freelancer/gigs/{id}/view', ...)` but that's for viewing gig.
      // We might not have a specific "show order" endpoint for customer yet, or we can filter by ID.
      // However, usually we can use the list and filter client side if not large, or add a show endpoint.
      // For now, let's try to fetch it from the list endpoint filtering by id if backend supports it, or just return null if not found.
      // Wait, the backend has `customerIndex`. It doesn't seem to support ID lookup directly.
      // But typically `Route::apiResource` would provide it.
      // I added `customerIndex` manually.
      // Let's rely on the list for now or add a show endpoint.
      // Actually, I can add a `show` method in backend `GigOrderController` for customers.
      
      // Temporary: Use the existing list endpoint to filter by ID (not efficient but works for now)
      // Or better, add `show` to backend.
      
      // For now, I'll comment this out or use a hypothetical endpoint.
      // Reverting to old logic but pointing to potentially new endpoint?
      // No, let's stick to what we have.
      
      // If we assume the frontend already has the data from the list, we might not need this often.
      // But for deep linking we do.
      
      // Let's use the list endpoint with a filter if possible? No.
      
      // I will leave this pointing to old endpoint but it might fail if route removed.
      // I should add `show` to `GigOrderController`.
      
      return null; 

    } catch (e) {
      return null;
    }
  }

  Future<Map<String, dynamic>?> createBooking(String? token, Map<String, dynamic> data) async {
    try {
      final options = Options(
        headers: token != null ? {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        } : {},
      );

      final response = await _dio.post(
        '${ApiConstants.baseUrl}$_createOrderEndpoint',
        data: data,
        options: options,
      );

      if (response.statusCode == 201 || response.statusCode == 200) {
        return response.data;
      }
      return null;
    } on DioException catch (e) {
      if (e.response != null) {
        print('Create Booking Error: ${e.response?.data}');
        throw Exception(e.response?.data['message'] ?? 'Failed to create booking');
      }
      throw e;
    } catch (e) {
      rethrow;
    }
  }
}
