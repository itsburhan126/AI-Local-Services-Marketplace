import 'package:dio/dio.dart';
import '../../../../core/constants/api_constants.dart';
import '../models/user_model.dart';

abstract class AuthRemoteDataSource {
  Future<UserModel> login(String email, String password);
  Future<UserModel> register(
    String name,
    String email,
    String password,
    String passwordConfirmation, {
    String? mode,
  });
  Future<UserModel> getUserProfile(String token);
  Future<void> updateProviderMode(String token, String mode);
  Future<void> updateFcmToken(String token, String fcmToken);
}

class AuthRemoteDataSourceImpl implements AuthRemoteDataSource {
  final Dio dio;

  AuthRemoteDataSourceImpl({required this.dio});

  @override
  Future<UserModel> login(String email, String password) async {
    try {
      final response = await dio.post(
        ApiConstants.loginEndpoint,
        data: {'email': email, 'password': password},
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final user = UserModel.fromJson(response.data);
        if (user.role != 'provider') {
          throw Exception(
            'Unauthorized: This account is not a provider account.',
          );
        }
        return user;
      } else {
        throw Exception('Login failed: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      if (e.response != null && e.response!.data != null) {
        throw Exception(e.response!.data['message'] ?? 'Login failed');
      }
      throw Exception('Login failed. Please check your connection.');
    } catch (e) {
      throw Exception(e.toString().replaceAll('Exception: ', ''));
    }
  }

  @override
  Future<UserModel> register(
    String name,
    String email,
    String password,
    String passwordConfirmation, {
    String? mode,
  }) async {
    try {
      final response = await dio.post(
        ApiConstants.registerEndpoint,
        data: {
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
          'role': 'provider',
          if (mode != null) 'service_rule': mode,
        },
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final user = UserModel.fromJson(response.data);
        if (user.role != 'provider') {
          throw Exception(
            'Registration Error: Failed to create provider account. Role assigned: ${user.role}',
          );
        }
        return user;
      } else {
        throw Exception('Registration failed: ${response.statusMessage}');
      }
    } on DioException catch (e) {
      if (e.response != null && e.response!.data != null) {
        throw Exception(e.response!.data['message'] ?? 'Registration failed');
      }
      throw Exception('Registration failed. Please check your connection.');
    }
  }

  @override
  Future<UserModel> getUserProfile(String token) async {
    try {
      final response = await dio.get(
        ApiConstants.userEndpoint,
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      if (response.statusCode == 200) {
        final user = UserModel.fromJson(response.data);
        // Inject the token used for the request if the response didn't return one (common in profile fetch)
        if (user.token == null) {
          return user.copyWith(token: token);
        }
        return user;
      } else {
        throw DioException(
          requestOptions: response.requestOptions,
          response: response,
          type: DioExceptionType.badResponse,
          error: 'Failed to load user profile: ${response.statusMessage}',
        );
      }
    } on DioException {
      rethrow;
    }
  }

  @override
  Future<void> updateProviderMode(String token, String mode) async {
    try {
      final response = await dio.post(
        '/api/provider/mode',
        data: {'mode': mode},
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      if (response.statusCode != 200) {
        throw Exception(
          'Failed to update provider mode: ${response.statusMessage}',
        );
      }
    } on DioException catch (e) {
      if (e.response != null && e.response!.data != null) {
        throw Exception(
          e.response!.data['message'] ?? 'Failed to update provider mode',
        );
      }
      throw Exception(
        'Failed to update provider mode. Please check your connection.',
      );
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
