import 'dart:io';
import 'package:dio/dio.dart';
import '../../../core/constants/api_constants.dart';

class ProfileService {
  final Dio _dio = Dio(
    BaseOptions(
      baseUrl: ApiConstants.baseUrl,
      connectTimeout: const Duration(seconds: 12),
      receiveTimeout: const Duration(seconds: 12),
      headers: {'Accept': 'application/json'},
    ),
  );

  Future<Map<String, dynamic>> getMyUser(String token) async {
    final response = await _dio.get(
      ApiConstants.userEndpoint,
      options: Options(headers: {'Authorization': 'Bearer $token'}),
    );

    final data = response.data;
    if (data is Map<String, dynamic>) {
      final wrapped = data['data'];
      if (wrapped is Map<String, dynamic>) {
        final user = wrapped['user'];
        if (user is Map<String, dynamic>) return user;
      }
      if (data['user'] is Map<String, dynamic>) return data['user'];
    }
    throw Exception('Invalid profile response');
  }

  Future<Map<String, dynamic>> updateProviderProfile(
    String token, {
    String? name,
    String? companyName,
    String? about,
    String? address,
    String? country,
    List<String>? languages,
    List<String>? skills,
    int? yearsExperience,
    File? image,
  }) async {
    final formData = FormData();
    
    if (name != null) formData.fields.add(MapEntry('name', name));
    if (companyName != null) formData.fields.add(MapEntry('company_name', companyName));
    if (about != null) formData.fields.add(MapEntry('about', about));
    if (address != null) formData.fields.add(MapEntry('address', address));
    if (country != null) formData.fields.add(MapEntry('country', country));
    if (yearsExperience != null) formData.fields.add(MapEntry('years_experience', yearsExperience.toString()));

    if (languages != null) {
      for (var lang in languages) {
        formData.fields.add(MapEntry('languages[]', lang));
      }
    }
    
    if (skills != null) {
      for (var skill in skills) {
        formData.fields.add(MapEntry('skills[]', skill));
      }
    }

    if (image != null) {
      formData.files.add(MapEntry(
        'avatar',
        await MultipartFile.fromFile(image.path),
      ));
    }

    final response = await _dio.post( // Use POST with _method spoofing if needed, or just PUT if server supports it.
      // Actually Laravel PUT with Multipart often fails.
      // Let's try POST with _method = PUT.
      '/api/provider/profile',
      data: formData,
      options: Options(
        headers: {
          'Authorization': 'Bearer $token',
          // 'Content-Type': 'multipart/form-data', // Dio sets this automatically
        },
      ),
      queryParameters: {'_method': 'PUT'},
    );

    final data = response.data;
    if (data is Map<String, dynamic>) {
      final wrapped = data['data'];
      if (wrapped is Map<String, dynamic> &&
          wrapped['user'] is Map<String, dynamic>) {
        return wrapped['user'] as Map<String, dynamic>;
      }
    }
    throw Exception('Failed to update profile');
  }

  Future<List<dynamic>> getPortfolios(String token) async {
    final response = await _dio.get(
      '/api/provider/portfolio',
      options: Options(headers: {'Authorization': 'Bearer $token'}),
    );
    final data = response.data;
    if (data is Map<String, dynamic> && data['data'] is List) {
      return data['data'] as List;
    }
    return [];
  }

  Future<List<dynamic>> getCountries() async {
    final response = await _dio.get('/api/countries');
    final data = response.data;
    if (data is Map<String, dynamic> && data['data'] is List) {
      return data['data'] as List;
    }
    return [];
  }

  Future<List<dynamic>> getLanguages() async {
    final response = await _dio.get('/api/languages');
    final data = response.data;
    if (data is Map<String, dynamic> && data['data'] is List) {
      return data['data'] as List;
    }
    return [];
  }

  Future<List<dynamic>> getSkills() async {
    final response = await _dio.get('/api/skills');
    final data = response.data;
    if (data is Map<String, dynamic> && data['data'] is List) {
      return data['data'] as List;
    }
    return [];
  }

  Future<Map<String, dynamic>> addPortfolio(
    String token, {
    required String title,
    String? description,
    String? link,
    required List<File> images,
  }) async {
    final imageFiles = <MultipartFile>[];
    for (var image in images) {
      imageFiles.add(await MultipartFile.fromFile(
        image.path,
        filename: image.path.split(Platform.pathSeparator).last,
      ));
    }

    final formData = FormData.fromMap({
      'title': title,
      if (description != null) 'description': description,
      if (link != null) 'link': link,
      'images[]': imageFiles,
    });

    final response = await _dio.post(
      '/api/provider/portfolio',
      data: formData,
      options: Options(
        headers: {'Authorization': 'Bearer $token'},
        contentType: 'multipart/form-data',
      ),
    );

    final data = response.data;
    if (data is Map<String, dynamic> && data['data'] is Map<String, dynamic>) {
      return data['data'] as Map<String, dynamic>;
    }
    throw Exception('Failed to add portfolio');
  }

  Future<void> deletePortfolio(String token, int id) async {
    await _dio.delete(
      '/api/provider/portfolio/$id',
      options: Options(headers: {'Authorization': 'Bearer $token'}),
    );
  }
}
