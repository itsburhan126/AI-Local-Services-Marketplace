import '../../data/models/user_model.dart';

abstract class AuthRepository {
  Future<UserModel> login(String email, String password);
  Future<UserModel> register(
    String name,
    String email,
    String password,
    String passwordConfirmation, {
    String? mode,
  });
  Future<void> updateProviderMode(String token, String mode);
  Future<void> updateFcmToken(String token, String fcmToken);
}
