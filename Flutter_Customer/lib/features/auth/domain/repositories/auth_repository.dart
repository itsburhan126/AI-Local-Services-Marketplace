import '../../data/models/user_model.dart';

abstract class AuthRepository {
  Future<UserModel> login(String email, String password);
  Future<UserModel> register(String name, String email, String password, String passwordConfirmation);
  Future<UserModel> updateProfile(String token, Map<String, dynamic> data);
  Future<UserModel> updateServiceRule(String token, String serviceRule);
  Future<UserModel> getProfile(String token);
  Future<void> updateFcmToken(String token, String fcmToken);
}
