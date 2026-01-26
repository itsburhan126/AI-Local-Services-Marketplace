import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import 'package:shared_preferences/shared_preferences.dart';

class GigService {
  final Dio _dio = Dio();

  Future<Map<String, dynamic>?> getGigDetails(int id) async {
    try {
      debugPrint('[GigService] GET details: ${ApiConstants.baseUrl}/api/gigs/$id');
      final response = await _dio.get('${ApiConstants.baseUrl}/api/gigs/$id');
      
      if (response.statusCode == 200) {
        final data = response.data['data'];
        return _fixDataUrls(data);
      }
      return null;
    } catch (e) {
      debugPrint('[GigService] getGigDetails error: $e');
      return null;
    }
  }

  Future<bool> incrementGigView(int id) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      
      final options = Options(
        headers: token != null ? {'Authorization': 'Bearer $token'} : {},
      );

      await _dio.post(
        '${ApiConstants.baseUrl}/api/freelancer/gigs/$id/view',
        options: options,
      );
      return true;
    } catch (e) {
      debugPrint('[GigService] incrementGigView error: $e');
      return false;
    }
  }

  Future<Map<String, dynamic>?> createGigOrder(Map<String, dynamic> data) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      
      final options = Options(
        headers: token != null ? {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        } : {},
      );

      final response = await _dio.post(
        '${ApiConstants.baseUrl}/api/freelancer/orders',
        data: data,
        options: options,
      );
      
      if (response.statusCode == 201) {
        return response.data;
      }
      throw Exception(response.data['message'] ?? 'Failed to place order');
    } catch (e) {
      if (e is DioException) {
         throw Exception(e.response?.data['message'] ?? e.message);
      }
      rethrow;
    }
  }

  // Helper to fix relative image URLs recursively
  dynamic _fixDataUrls(dynamic data) {
    if (data is Map<String, dynamic>) {
      final Map<String, dynamic> fixed = {};
      data.forEach((key, value) {
        if ((key.contains('image') || key.contains('icon') || key.contains('logo') || key.contains('thumbnail') || key.contains('avatar')) && value is String) {
          fixed[key] = _getValidUrl(value);
        } else {
          fixed[key] = _fixDataUrls(value);
        }
      });
      return fixed;
    } else if (data is List) {
      return data.map((item) => _fixDataUrls(item)).toList();
    }
    return data;
  }

  String _getValidUrl(String url) {
    if (url.isEmpty || url == 'default') return 'https://images.unsplash.com/photo-1558655146-d09347e92766?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
    if (url.startsWith('http')) return url;
    if (url.startsWith('/')) return '${ApiConstants.baseUrl}$url';
    return '${ApiConstants.baseUrl}/$url';
  }
}
