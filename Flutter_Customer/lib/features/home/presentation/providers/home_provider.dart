import 'package:flutter/material.dart';
import '../../data/home_service.dart';

class HomeProvider with ChangeNotifier {
  final HomeService _homeService = HomeService();
  
  Map<String, dynamic> _data = {};
  bool _isLoading = false;
  String? _error;

  // Getters
  List<dynamic> get categories => _data['categories'] ?? [];
  List<dynamic> get popularServices => _data['popular_services'] ?? [];
  List<dynamic> get recommendedServices => _data['recommended_services'] ?? [];
  List<dynamic> get newServices => _data['new_services'] ?? [];
  List<dynamic> get banners => _data['banners'] ?? [];
  Map<String, dynamic>? get singleBanner => _data['single_banner'];
  List<dynamic> get promotionalBanners => _data['promotional_banners'] ?? [];
  List<dynamic> get recentlyViewed => _data['recently_viewed'] ?? [];
  List<dynamic> get recentlySaved => _data['recently_saved'] ?? [];
  List<dynamic> get sparkInterest => _data['spark_interest'] ?? [];
  List<dynamic> get interests => _interests;
  Map<String, dynamic>? get referral => _data['referral'];
  
  // New "Big App" Features
  Map<String, dynamic>? get flashSale => _data['flash_sale'];
  List<dynamic> get trustSafety => _data['trust_safety'] ?? [];
  List<dynamic> get testimonials => _data['testimonials'] ?? [];

  bool get isLoading => _isLoading;
  String? get error => _error;

  List<dynamic> _interests = [];

  Future<void> loadHomeData({String type = 'local_service'}) async {
    debugPrint('[HomeProvider] loadHomeData: start type=$type');
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final results = await Future.wait([
        _homeService.getHomeData(type: type),
        _homeService.getInterests(type: type),
        // Popular and Recommended are part of getHomeData response usually?
        // Wait, HomeService.getHomeData returns Map<String, dynamic>
        // But the current implementation calls getPopularServices separately?
        // Let's check HomeProvider again.
        _homeService.getPopularServices(type: type),
        _homeService.getRecommendedServices(type: type),
      ]);
      _data = results[0] as Map<String, dynamic>;
      _interests = results[1] as List<dynamic>;
      final popular = results[2] as List<dynamic>;
      final recommended = results[3] as List<dynamic>;

      debugPrint('[HomeProvider] loadHomeData: fetched '
          'banners=${(_data['banners'] ?? []).length}, '
          'categories=${(_data['categories'] ?? []).length}, '
          'interests=${_interests.length}, '
          'popular=${popular.length}, '
          'recommended=${recommended.length}');

      if (popular.isNotEmpty) {
        _data['popular_services'] = popular;
      }
      if (recommended.isNotEmpty) {
        _data['recommended_services'] = recommended;
      }
    } catch (e) {
      _error = e.toString();
      debugPrint('Error loading home data: $e');
    } finally {
      _isLoading = false;
      debugPrint('[HomeProvider] loadHomeData: complete isLoading=$_isLoading error=${_error ?? 'none'}');
      notifyListeners();
    }
  }

  Future<void> loadInterests({String type = 'local_service'}) async {
    _isLoading = true;
    notifyListeners();
    try {
      _interests = await _homeService.getInterests(type: type);
    } catch (e) {
      debugPrint('Error loading interests: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> toggleInterest(int interestId, {String type = 'local_service'}) async {
    // Optimistic update
    final index = _interests.indexWhere((i) => i['id'] == interestId);
    if (index != -1) {
      final currentStatus = _interests[index]['is_selected'] ?? false;
      _interests[index]['is_selected'] = !currentStatus;
      notifyListeners();

      try {
        final success = await _homeService.toggleInterest(interestId, type: type);
        if (!success) {
          // Revert if failed
          _interests[index]['is_selected'] = currentStatus;
          notifyListeners();
          return false;
        }
        
        // Success: The API will handle the cooldown logic on next fetch.
        return true;
      } catch (e) {
        // Revert on error
        _interests[index]['is_selected'] = currentStatus;
        notifyListeners();
        if (e.toString().contains('Unauthorized')) {
          rethrow;
        }
        return false;
      }
    }
    return false;
  }

  Future<List<dynamic>> fetchGigsByCategory(int categoryId) async {
    try {
      return await _homeService.getGigsByCategory(categoryId);
    } catch (e) {
      debugPrint('Error fetching gigs by category: $e');
      return [];
    }
  }
}
