import 'package:dio/dio.dart';
import '../models/service_model.dart';
import '../models/category_model.dart';

abstract class ServiceRemoteDataSource {
  Future<List<ServiceModel>> getProviderServices(String token);
  Future<ServiceModel> createService(String token, Map<String, dynamic> data);
  Future<List<CategoryModel>> getCategories({String? type});
}

class ServiceRemoteDataSourceImpl implements ServiceRemoteDataSource {
  final Dio dio;

  ServiceRemoteDataSourceImpl({required this.dio});

  @override
  Future<List<ServiceModel>> getProviderServices(String token) async {
    try {
      final response = await dio.get(
        '/api/provider/services',
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      if (response.statusCode == 200) {
        final List data = response.data['data'];
        return data.map((e) => ServiceModel.fromJson(e)).toList();
      } else {
        throw Exception('Failed to fetch services');
      }
    } catch (e) {
      throw Exception('Failed to fetch services: $e');
    }
  }

  @override
  Future<ServiceModel> createService(
    String token,
    Map<String, dynamic> data,
  ) async {
    try {
      final response = await dio.post(
        '/api/provider/services',
        data: data,
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      if (response.statusCode == 201) {
        return ServiceModel.fromJson(response.data['data']);
      } else {
        throw Exception('Failed to create service: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      if (e.response != null && e.response!.data != null) {
        throw Exception(
          e.response!.data['message'] ?? 'Failed to create service',
        );
      }
      throw Exception(
        'Failed to create service. Please check your connection.',
      );
    }
  }

  @override
  Future<List<CategoryModel>> getCategories({String? type}) async {
    try {
      final response = await dio.get(
        '/api/categories',
        queryParameters: type != null ? {'type': type} : null,
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
}
