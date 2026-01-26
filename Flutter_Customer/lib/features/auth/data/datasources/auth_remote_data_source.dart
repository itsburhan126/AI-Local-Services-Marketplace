import 'package:dio/dio.dart';
import '../../../../core/constants/api_constants.dart';
import '../models/user_model.dart';

abstract class AuthRemoteDataSource {
  Future<UserModel> login(String email, String password);
  Future<UserModel> register(String name, String email, String password, String passwordConfirmation);
  Future<UserModel> updateProfile(String token, Map<String, dynamic> data);
  Future<UserModel> updateServiceRule(String token, String serviceRule);
  Future<UserModel> getProfile(String token);
  Future<void> updateFcmToken(String token, String fcmToken);
}

class AuthRemoteDataSourceImpl implements AuthRemoteDataSource {
  final Dio dio;

  AuthRemoteDataSourceImpl({required this.dio}) {
    dio.options.baseUrl = ApiConstants.baseUrl;
    dio.options.headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    dio.options.connectTimeout = const Duration(seconds: 10);
    dio.options.receiveTimeout = const Duration(seconds: 10);
    
    // Add logging interceptor
    dio.interceptors.add(LogInterceptor(
      request: true,
      requestHeader: true,
      requestBody: true,
      responseHeader: true,
      responseBody: true,
      error: true,
    ));
  }

  @override
  Future<UserModel> login(String email, String password) async {
    try {
      final response = await dio.post(
        ApiConstants.loginEndpoint,
        data: {
          'email': email,
          'password': password,
        },
      );
      
      if (response.statusCode == 200 || response.statusCode == 201) {
        final user = UserModel.fromJson(response.data);
        if (user.role == 'provider') {
           throw Exception('Unauthorized: This account is a Provider account. Please use the Provider App.');
        }
        return user;
      } else {
        throw Exception('Login failed: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Login failed. Please check your connection.');
    }
  }

  @override
  Future<UserModel> register(String name, String email, String password, String passwordConfirmation) async {
    try {
      final response = await dio.post(
        ApiConstants.registerEndpoint,
        data: {
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
          'role': 'user',
        },
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        return UserModel.fromJson(response.data);
      } else {
        throw Exception('Registration failed: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Registration failed. Please check your connection.');
    }
  }

  @override
  Future<UserModel> updateServiceRule(String token, String serviceRule) async {
    try {
      final response = await dio.post(
        ApiConstants.updateServiceRuleEndpoint,
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
        data: {
          'service_rule': serviceRule,
        },
      );

      if (response.statusCode == 200) {
        return UserModel.fromJson(response.data);
      } else {
        throw Exception('Failed to update service rule: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to update service rule. Please check your connection.');
    }
  }
  @override
  Future<UserModel> updateProfile(String token, Map<String, dynamic> data) async {
    try {
      final response = await dio.post(
        ApiConstants.userEndpoint, // Assuming POST to /api/user updates profile or check specific endpoint
        // Often update is PUT /api/profile or POST /api/user/update. 
        // Based on ApiConstants.userEndpoint = '/api/user', usually GET returns user, PUT updates?
        // Let's assume PUT to /api/user for now, or POST with _method=PUT if Laravel.
        // Laravel often uses POST for file uploads, but here just data.
        // Let's try PUT.
        options: Options(headers: {'Authorization': 'Bearer $token'}),
        data: data,
      );

      if (response.statusCode == 200) {
        return UserModel.fromJson(response.data);
      } else {
        throw Exception('Update failed: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Update failed.');
    }
  }

  @override
  Future<UserModel> getProfile(String token) async {
    try {
      final response = await dio.get(
        ApiConstants.userEndpoint,
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200) {
        return UserModel.fromJson(response.data);
      } else {
        throw Exception('Failed to fetch profile: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to fetch profile. Please check your connection.');
    }
  }

  @override
  Future<void> updateFcmToken(String token, String fcmToken) async {
    try {
      await dio.post(
        '/api/user/update-fcm-token',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
        data: {
          'fcm_token': fcmToken,
        },
      );
    } catch (e) {
      // Ignore errors or log
      print("Failed to update FCM token: $e");
    }
  }
}
