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

  Future<bool> deliverWork(String token, String orderId, String note, List<String>? files) async {
    try {
      final options = Options(
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      );

      final response = await _dio.post(
        '${ApiConstants.baseUrl}/api/freelancer/orders/$orderId/deliver',
        data: {
          'delivery_note': note,
          'delivery_files': files,
        },
        options: options,
      );

      return response.statusCode == 200;
    } catch (e) {
      print('Deliver Work Error: $e');
      throw e;
    }
  }

  Future<bool> approveWork(String token, String orderId) async {
    try {
      final options = Options(
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      );

      final response = await _dio.post(
        '${ApiConstants.baseUrl}/api/freelancer/orders/$orderId/approve',
        options: options,
      );

      return response.statusCode == 200;
    } catch (e) {
      print('Approve Work Error: $e');
      throw e;
    }
  }

  Future<bool> rejectWork(String token, String orderId) async {
    try {
      final options = Options(
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      );

      final response = await _dio.post(
        '${ApiConstants.baseUrl}/api/freelancer/orders/$orderId/reject',
        options: options,
      );

      return response.statusCode == 200;
    } catch (e) {
      print('Reject Work Error: $e');
      throw e;
    }
  }

  Future<bool> submitReview(String token, String orderId, int rating, String review) async {
    try {
      final options = Options(
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      );

      final response = await _dio.post(
        '${ApiConstants.baseUrl}/api/reviews',
        data: {
          'gig_order_id': orderId,
          'rating': rating,
          'review': review,
        },
        options: options,
      );

      return response.statusCode == 200;
    } catch (e) {
      print('Submit Review Error: $e');
      throw e;
    }
  }
}
