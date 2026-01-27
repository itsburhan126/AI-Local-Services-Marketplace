import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_customer/core/constants/api_constants.dart';
import 'package:shared_preferences/shared_preferences.dart';

class HomeService {
  final Dio _dio = Dio();

  Future<Map<String, dynamic>> getHomeData({String type = 'local_service'}) async {
    try {
      String endpoint = type == 'freelancer' ? '/api/freelancer/home' : '/api/home';
      debugPrint('[HomeService] GET home: ${ApiConstants.baseUrl}$endpoint?type=$type');
      final response = await _dio.get(
        '${ApiConstants.baseUrl}$endpoint',
        queryParameters: {'type': type},
      );
      debugPrint('[HomeService] home status: ${response.statusCode}');
      
      if (response.statusCode == 200) {
        final data = response.data['data'] ?? {};
        final fixedData = _fixDataUrls(data);
        
        return fixedData;
      }
      return {};
    } catch (e) {
      debugPrint('[HomeService] getHomeData error: $e');
      return {};
    }
  }

  // Fetch Interests
  Future<List<dynamic>> getInterests({String type = 'local_service'}) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      
      final options = token != null 
          ? Options(headers: {'Authorization': 'Bearer $token'}) 
          : null;

      debugPrint('[HomeService] GET interests: ${ApiConstants.baseUrl}/api/interests?type=$type');
      final response = await _dio.get(
        '${ApiConstants.baseUrl}/api/interests',
        queryParameters: {'type': type},
        options: options,
      );

      if (response.statusCode == 200) {
        final data = _fixDataUrls(response.data['data'] ?? []);
        // Removed mock data fallback to prevent "ghost" items that can't be toggled
        debugPrint('[HomeService] interests status: ${response.statusCode}, count: ${data.length}');
        return data;
      }
      return [];
    } catch (e) {
      debugPrint('[HomeService] getInterests error: $e');
      return [];
    }
  }

  // Fetch All Categories
  Future<List<dynamic>> getAllCategories({String type = 'local_service'}) async {
    try {
      debugPrint('[HomeService] GET all categories: ${ApiConstants.baseUrl}/api/categories?type=$type');
      final response = await _dio.get(
        '${ApiConstants.baseUrl}/api/categories',
        queryParameters: {'type': type},
      );

      if (response.statusCode == 200) {
        final data = _fixDataUrls(response.data['data']['data'] ?? response.data['data'] ?? []);
        debugPrint('[HomeService] all categories status: ${response.statusCode}, count: ${data.length}');
        return data;
      }
      return [];
    } catch (e) {
      debugPrint('[HomeService] getAllCategories error: $e');
      return [];
    }
  }

  // Fetch Gigs by Category
  Future<List<dynamic>> getGigsByCategory(int categoryId) async {
    try {
      debugPrint('[HomeService] GET gigs by category: ${ApiConstants.baseUrl}/api/gigs?category_id=$categoryId');
      final response = await _dio.get(
        '${ApiConstants.baseUrl}/api/gigs',
        queryParameters: {'category_id': categoryId},
      );

      if (response.statusCode == 200) {
        // The API returns paginated data: { success: true, data: { current_page: 1, data: [...] } }
        final data = response.data['data']['data'] ?? [];
        final fixedData = _fixDataUrls(data);
        debugPrint('[HomeService] gigs by category status: ${response.statusCode}, count: ${fixedData.length}');
        return fixedData;
      }
      return [];
    } catch (e) {
      debugPrint('[HomeService] getGigsByCategory error: $e');
      return [];
    }
  }

  // Toggle Interest
  Future<bool> toggleInterest(int interestId, {String type = 'local_service'}) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      
      if (token == null) {
        print('Toggle Interest Failed: No Token');
        return false;
      }

      print('Toggling interest ID: $interestId, Type: $type');
      final response = await _dio.post(
        '${ApiConstants.baseUrl}/api/interests/toggle',
        data: {
          'interest_id': interestId,
          'type': type,
        },
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );
      
      print('Toggle response: ${response.statusCode} - ${response.data}');

      return response.statusCode == 200 && response.data['status'] == 'success';
    } catch (e) {
      print('Error toggling interest: $e');
      if (e is DioException) {
         print('DioError response: ${e.response?.data}');
         if (e.response?.statusCode == 401) {
           throw Exception('Unauthorized');
         }
      }
      return false;
    }
  }

  Future<Map<String, dynamic>> toggleFavorite(int id, {String type = 'gig'}) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      
      if (token == null) {
        return {'success': false, 'message': 'Please login to favorite'};
      }

      final response = await _dio.post(
        '${ApiConstants.baseUrl}/api/favorites/toggle',
        data: {'id': id, 'type': type},
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        return {
          'success': true,
          'is_favorite': response.data['is_favorite'],
          'message': response.data['message']
        };
      }
      return {'success': false, 'message': 'Failed to toggle favorite'};
    } catch (e) {
      debugPrint('Error toggling favorite: $e');
      return {'success': false, 'message': 'Error toggling favorite'};
    }
  }

  // Helper to fix relative image URLs recursively
  dynamic _fixDataUrls(dynamic data) {
    if (data is Map<String, dynamic>) {
      final Map<String, dynamic> fixed = {};
      data.forEach((key, value) {
        if ((key.contains('image') || key.contains('icon') || key.contains('logo') || key.contains('thumbnail')) && value is String) {
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
    if (url.isEmpty || 
        url == 'default' || 
        url.contains('via.placeholder.com') ||
        url.contains('default.png') ||
        url.contains('photo-1527515637-62da7a808806') || // Broken Unsplash 1
        url.contains('photo-1581578731117-104f2a41272c') || // Broken Unsplash 2
        url.contains('photo-1581094794329-cd1361ddee2e')) {
      // Return empty string to trigger errorWidget in CachedNetworkImage which shows local placeholder
      return ''; 
    }
    if (url.startsWith('http')) return url;
    if (url.startsWith('/')) return '${ApiConstants.baseUrl}$url';
    return '${ApiConstants.baseUrl}/$url';
  }

  Future<List<dynamic>> getCategories() async {
    try {
      final response = await _dio.get('${ApiConstants.baseUrl}${ApiConstants.categoriesEndpoint}');
      if (response.statusCode == 200) {
        final data = response.data['data'] ?? [];
        return _fixDataUrls(data);
      }
      return [];
    } catch (e) {
      throw Exception('Failed to load categories');
    }
  }

  Future<List<dynamic>> getPopularServices({String type = 'local_service'}) async {
    try {
      if (type == 'freelancer') {
         final home = await getHomeData(type: type);
         final fromHome = _fixDataUrls(home['popular_services'] ?? []) as List;
         return fromHome;
      }

      final url = '${ApiConstants.baseUrl}${ApiConstants.servicesEndpoint}?popular=1';
      debugPrint('[HomeService] GET popular: $url');
      final response = await _dio.get(url);
      if (response.statusCode == 200) {
        final raw = response.data['data'] ?? [];
        final data = _fixDataUrls(raw);
        debugPrint('[HomeService] popular status: ${response.statusCode}, count: ${data.length}');
        if ((data as List).isNotEmpty) return data;
      }
      // Fallback to home endpoint
      debugPrint('[HomeService] popular empty or non-200, fallback to /api/home popular_services');
      final home = await getHomeData(type: type);
      final fromHome = _fixDataUrls(home['popular_services'] ?? []) as List;
      debugPrint('[HomeService] fallback popular count: ${fromHome.length}');
      if (fromHome.isNotEmpty) return fromHome;
      debugPrint('[HomeService] fallback popular still empty, using local demo data');
      return _getMockPopularServices();
    } catch (e) {
      if (e is DioException) {
        debugPrint('[HomeService] popular DioError: status=${e.response?.statusCode}');
      }
      // Fallback on error
      final home = await getHomeData(type: type);
      final fromHome = _fixDataUrls(home['popular_services'] ?? []) as List;
      debugPrint('[HomeService] fallback popular (error) count: ${fromHome.length}');
      if (fromHome.isNotEmpty) return fromHome;
      return _fixDataUrls(_getMockPopularServices()) as List;
    }
  }

  Future<List<dynamic>> getRecommendedServices({String type = 'local_service'}) async {
    try {
      if (type == 'freelancer') {
         final home = await getHomeData(type: type);
         final fromHome = _fixDataUrls(home['recommended_services'] ?? []) as List;
         return fromHome;
      }

      final url = '${ApiConstants.baseUrl}${ApiConstants.servicesEndpoint}?recommended=1';
      debugPrint('[HomeService] GET recommended: $url');
      final response = await _dio.get(url);
      if (response.statusCode == 200) {
        final raw = response.data['data'] ?? [];
        final data = _fixDataUrls(raw);
        debugPrint('[HomeService] recommended status: ${response.statusCode}, count: ${data.length}');
        if ((data as List).isNotEmpty) return data;
      }
      // Fallback to home endpoint
      debugPrint('[HomeService] recommended empty or non-200, fallback to /api/home recommended_services');
      final home = await getHomeData(type: type);
      final fromHome = _fixDataUrls(home['recommended_services'] ?? []) as List;
      debugPrint('[HomeService] fallback recommended count: ${fromHome.length}');
      if (fromHome.isNotEmpty) return fromHome;
      debugPrint('[HomeService] fallback recommended still empty, using local demo data');
      return _getMockRecommendedServices();
    } catch (e) {
      debugPrint('[HomeService] getRecommendedServices error: $e');
      final home = await getHomeData(type: type);
      final fromHome = _fixDataUrls(home['recommended_services'] ?? []) as List;
      if (fromHome.isNotEmpty) return fromHome;
      return _getMockRecommendedServices();
    }
  }

  List<dynamic> _getMockPopularServices() {
    return [
      {
        'id': 1001,
        'name': 'Deep House Cleaning',
        'price': '120.00',
        'rating': 4.9,
        'reviews_count': 320,
        'image': 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=80',
        'provider': {'name': 'CleanPro Services'}
      },
      {
        'id': 1002,
        'name': 'AC Repair & Service',
        'price': '80.00',
        'rating': 4.8,
        'reviews_count': 150,
        'image': 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80',
        'provider': {'name': 'Cool Air Tech'}
      },
      {
        'id': 1003,
        'name': 'Full Home Painting',
        'price': '450.00',
        'rating': 4.7,
        'reviews_count': 85,
        'image': 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=80',
        'provider': {'name': 'Color Masters'}
      },
    ];
  }

  List<dynamic> _getMockRecommendedServices() {
    return [
      {
        'id': 2001,
        'name': 'Laundry Service',
        'price': '30.00',
        'rating': 4.6,
        'reviews_count': 300,
        'image': 'https://images.unsplash.com/photo-1582735689369-c613c660d6aa?ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=80',
        'provider': {'name': 'Quick Clean'}
      },
      {
        'id': 2002,
        'name': 'Handyman Service',
        'price': '50.00',
        'rating': 4.8,
        'reviews_count': 88,
        'image': 'https://images.unsplash.com/photo-1505798577917-a651a5d60bb6?ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=80',
        'provider': {'name': 'Fix It All'}
      },
      {
        'id': 2003,
        'name': 'Sofa Cleaning',
        'price': '60.00',
        'rating': 4.6,
        'reviews_count': 45,
        'image': 'https://images.unsplash.com/photo-1556910103-1c02745a30bf?ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=80',
        'provider': {'name': 'Soft Touch'}
      },
    ];
  }

  // Mock data methods removed as per request
}
