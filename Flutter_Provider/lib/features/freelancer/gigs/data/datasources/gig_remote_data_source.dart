import 'dart:convert';
import 'dart:io';
import 'package:dio/dio.dart';
import '../../../../../core/constants/api_constants.dart';
import '../models/gig_model.dart';
import '../models/tag_model.dart';
import '../models/paginated_reviews_model.dart';
import 'package:flutter_provider/features/freelancer/gigs/data/models/gig_analytics_model.dart';
import '../../../../services/data/models/category_model.dart';

import '../../../../services/data/models/service_type_model.dart';

abstract class GigRemoteDataSource {
  Future<GigModel> createGig(
    String token,
    GigModel gig, {
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  });
  Future<GigModel> updateGig(
    String token,
    GigModel gig, {
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  });
  Future<List<GigModel>> getProviderGigs(String token);
  Future<void> deleteGig(String token, int id);
  Future<List<ServiceTypeModel>> getServiceTypes();
  Future<List<CategoryModel>> getCategories({int? parentId});
  Future<List<TagModel>> getTags({String? query});
  Future<GigAnalyticsModel> getGigAnalytics(String token, int id);
  Future<PaginatedReviewsModel> getGigReviews(String token, int id, int page);
  Future<GigModel> getGigDetails(String token, int id);
  Future<GigModel> updateGigStatus(String token, int id, String status);
}

class GigRemoteDataSourceImpl implements GigRemoteDataSource {
  final Dio dio;

  GigRemoteDataSourceImpl({required this.dio}) {
    dio.options.baseUrl = ApiConstants.baseUrl;
  }

  @override
  Future<List<ServiceTypeModel>> getServiceTypes() async {
    try {
      final response = await dio.get('/api/service-types');

      if (response.statusCode == 200) {
        final List data = response.data['data'];
        return data.map((e) => ServiceTypeModel.fromJson(e)).toList();
      } else {
        throw Exception('Failed to fetch service types');
      }
    } catch (e) {
      throw Exception('Failed to fetch service types: $e');
    }
  }

  @override
  Future<List<CategoryModel>> getCategories({int? parentId}) async {
    try {
      final queryParams = {'type': 'freelancer'};
      if (parentId != null) {
        queryParams['parent_id'] = parentId.toString();
      }

      final response = await dio.get(
        '/api/categories',
        queryParameters: queryParams,
      );

      if (response.statusCode == 200) {
        final List data = response.data['data'];
        return data.map((e) => CategoryModel.fromJson(e)).toList();
      } else {
        throw Exception('Failed to fetch categories');
      }
    } catch (e) {
      throw Exception('Failed to fetch categories: $e');
    }
  }

  @override
  Future<List<TagModel>> getTags({String? query}) async {
    try {
      final response = await dio.get(
        '/api/tags',
        queryParameters: query != null ? {'query': query} : null,
      );

      if (response.statusCode == 200) {
        final List data = response.data['data'];
        return data.map((e) => TagModel.fromJson(e)).toList();
      } else {
        throw Exception('Failed to fetch tags');
      }
    } catch (e) {
      throw Exception('Failed to fetch tags: $e');
    }
  }

  @override
  Future<GigModel> createGig(
    String token,
    GigModel gig, {
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  }) async {
    try {
      final Map<String, dynamic> gigData = gig.toJson();

      // Remove complex objects to send as JSON strings if needed, or rely on Laravel's handling
      // Since we use FormData, nested arrays need careful handling.
      // Best approach: Send 'packages' and 'extras' as JSON encoded strings
      gigData['packages'] = jsonEncode(gigData['packages']);
      gigData['extras'] = jsonEncode(gigData['extras']);
      gigData['faqs'] = jsonEncode(gigData['faqs']);
      gigData['tags'] = jsonEncode(gigData['tags']);
      gigData['metadata'] = jsonEncode(gigData['metadata']);

      // Remove empty file lists from data to avoid confusion (we send files separately)
      gigData.remove('images');
      gigData.remove('documents');
      gigData.remove('thumbnail_image');

      final formData = FormData.fromMap(gigData);

      if (thumbnail != null) {
        formData.files.add(
          MapEntry(
            'thumbnail',
            await MultipartFile.fromFile(
              thumbnail.path,
              filename: 'thumbnail.jpg',
            ),
          ),
        );
      }

      if (images != null) {
        for (var i = 0; i < images.length; i++) {
          formData.files.add(
            MapEntry(
              'images[]',
              await MultipartFile.fromFile(
                images[i].path,
                filename: 'image_$i.jpg',
              ),
            ),
          );
        }
      }

      if (video != null) {
        formData.files.add(
          MapEntry(
            'video',
            await MultipartFile.fromFile(video.path, filename: 'video.mp4'),
          ),
        );
      }

      if (documents != null) {
        for (var i = 0; i < documents.length; i++) {
          formData.files.add(
            MapEntry(
              'documents[]',
              await MultipartFile.fromFile(
                documents[i].path,
                filename: 'doc_$i.pdf',
              ),
            ),
          );
        }
      }

      final response = await dio.post(
        '/api/provider/gigs',
        data: formData,
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 201) {
        return GigModel.fromJson(response.data['data']);
      } else {
        throw Exception('Failed to create gig');
      }
    } catch (e) {
      throw Exception('Failed to create gig: $e');
    }
  }

  @override
  Future<GigModel> updateGig(
    String token,
    GigModel gig, {
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  }) async {
    try {
      final Map<String, dynamic> gigData = gig.toJson();

      // Similar to createGig, encode complex fields
      gigData['packages'] = jsonEncode(gigData['packages']);
      gigData['extras'] = jsonEncode(gigData['extras']);
      gigData['faqs'] = jsonEncode(gigData['faqs']);
      gigData['tags'] = jsonEncode(gigData['tags']);
      gigData['metadata'] = jsonEncode(gigData['metadata']);

      gigData.remove('images');
      gigData.remove('documents');
      gigData.remove('thumbnail_image');

      // For update, we use POST with _method=PUT to handle file uploads in Laravel
      gigData['_method'] = 'PUT';

      final formData = FormData.fromMap(gigData);

      if (thumbnail != null) {
        formData.files.add(
          MapEntry(
            'thumbnail',
            await MultipartFile.fromFile(
              thumbnail.path,
              filename: 'thumbnail.jpg',
            ),
          ),
        );
      }

      if (images != null) {
        for (var i = 0; i < images.length; i++) {
          formData.files.add(
            MapEntry(
              'images[]',
              await MultipartFile.fromFile(
                images[i].path,
                filename: 'image_$i.jpg',
              ),
            ),
          );
        }
      }

      if (video != null) {
        formData.files.add(
          MapEntry(
            'video',
            await MultipartFile.fromFile(video.path, filename: 'video.mp4'),
          ),
        );
      }

      if (documents != null) {
        for (var i = 0; i < documents.length; i++) {
          formData.files.add(
            MapEntry(
              'documents[]',
              await MultipartFile.fromFile(
                documents[i].path,
                filename: 'doc_$i.pdf',
              ),
            ),
          );
        }
      }

      final response = await dio.post(
        '/api/provider/gigs/${gig.id}',
        data: formData,
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        return GigModel.fromJson(response.data['data']);
      } else {
        throw Exception('Failed to update gig');
      }
    } catch (e) {
      throw Exception('Failed to update gig: $e');
    }
  }

  @override
  Future<List<GigModel>> getProviderGigs(String token) async {
    try {
      final response = await dio.get(
        '/api/provider/gigs',
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        final List data = response.data['data'];
        return data.map((e) => GigModel.fromJson(e)).toList();
      } else {
        throw Exception('Failed to fetch gigs');
      }
    } catch (e) {
      throw Exception('Failed to fetch gigs: $e');
    }
  }

  @override
  Future<void> deleteGig(String token, int id) async {
    try {
      final response = await dio.delete(
        '/api/provider/gigs/$id',
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode != 200) {
        throw Exception('Failed to delete gig');
      }
    } catch (e) {
      throw Exception('Failed to delete gig: $e');
    }
  }

  @override
  Future<GigAnalyticsModel> getGigAnalytics(String token, int id) async {
    try {
      final response = await dio.get(
        '/api/provider/gigs/$id/analytics',
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        return GigAnalyticsModel.fromJson(response.data['data']);
      } else {
        throw Exception('Failed to fetch gig analytics');
      }
    } catch (e) {
      throw Exception('Failed to fetch gig analytics: $e');
    }
  }

  @override
  Future<PaginatedReviewsModel> getGigReviews(String token, int id, int page) async {
    try {
      final response = await dio.get(
        '/api/provider/gigs/$id/reviews',
        queryParameters: {'page': page},
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        return PaginatedReviewsModel.fromJson(response.data['data']);
      } else {
        throw Exception('Failed to fetch gig reviews');
      }
    } catch (e) {
      throw Exception('Failed to fetch gig reviews: $e');
    }
  }

  @override
  Future<GigModel> getGigDetails(String token, int id) async {
    try {
      final response = await dio.get(
        '/api/provider/gigs/$id',
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        return GigModel.fromJson(response.data['data']);
      } else {
        throw Exception('Failed to fetch gig details');
      }
    } catch (e) {
      throw Exception('Failed to fetch gig details: $e');
    }
  }

  @override
  Future<GigModel> updateGigStatus(String token, int id, String status) async {
    try {
      final response = await dio.patch(
        '/api/provider/gigs/$id/status',
        data: {'status': status},
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200) {
        return GigModel.fromJson(response.data['data']);
      } else {
        throw Exception('Failed to update gig status');
      }
    } catch (e) {
      throw Exception('Failed to update gig status: $e');
    }
  }
}
