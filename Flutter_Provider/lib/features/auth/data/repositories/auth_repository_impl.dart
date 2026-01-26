import '../../domain/repositories/auth_repository.dart';
import '../../data/datasources/auth_remote_data_source.dart';
import '../../data/models/user_model.dart';

class AuthRepositoryImpl implements AuthRepository {
  final AuthRemoteDataSource remoteDataSource;

  AuthRepositoryImpl({required this.remoteDataSource});

  @override
  Future<UserModel> login(String email, String password) async {
    return await remoteDataSource.login(email, password);
  }

  @override
  Future<UserModel> register(
    String name,
    String email,
    String password,
    String passwordConfirmation, {
    String? mode,
  }) async {
    return await remoteDataSource.register(
      name,
      email,
      password,
      passwordConfirmation,
      mode: mode,
    );
  }

  @override
  Future<void> updateProviderMode(String token, String mode) async {
    return await remoteDataSource.updateProviderMode(token, mode);
  }

  @override
  Future<void> updateFcmToken(String token, String fcmToken) async {
    return await remoteDataSource.updateFcmToken(token, fcmToken);
  }
}
