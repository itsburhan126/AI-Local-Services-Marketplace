import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class RecentlyViewedService {
  static const String _key = 'recently_viewed_freelancer_services';
  static const int _limit = 10;

  Future<void> addService(Map<String, dynamic> service) async {
    final prefs = await SharedPreferences.getInstance();
    final List<String> currentList = prefs.getStringList(_key) ?? [];
    
    // Create a simplified version of the service to store (id, name, image, price, rating, reviews, provider)
    // This avoids storing too much data
    final Map<String, dynamic> simplifiedService = {
      'id': service['id'],
      'name': service['name'],
      'image': service['image'] ?? service['thumbnail'],
      'price': service['price'],
      'rating': service['rating'],
      'reviews': service['reviews'],
      'provider': service['provider'],
      'category': service['category'],
    };

    final String jsonString = jsonEncode(simplifiedService);

    // Remove if already exists (to move to top)
    currentList.removeWhere((item) {
      try {
        final decoded = jsonDecode(item);
        return decoded['id'].toString() == simplifiedService['id'].toString();
      } catch (e) {
        return false;
      }
    });

    // Add to top
    currentList.insert(0, jsonString);

    // Limit
    if (currentList.length > _limit) {
      currentList.removeRange(_limit, currentList.length);
    }

    await prefs.setStringList(_key, currentList);
  }

  Future<List<Map<String, dynamic>>> getServices() async {
    final prefs = await SharedPreferences.getInstance();
    final List<String> currentList = prefs.getStringList(_key) ?? [];
    
    return currentList.map((item) {
      try {
        return jsonDecode(item) as Map<String, dynamic>;
      } catch (e) {
        return <String, dynamic>{};
      }
    }).where((item) => item.isNotEmpty).toList();
  }
}
