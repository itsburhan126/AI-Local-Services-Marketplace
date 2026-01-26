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
  Future<UserModel> register(String name, String email, String password, String passwordConfirmation) async {
    return await remoteDataSource.register(name, email, password, passwordConfirmation);
  }

  @override
  Future<UserModel> updateProfile(String token, Map<String, dynamic> data) async {
    return await remoteDataSource.updateProfile(token, data);
  }

  @override
  Future<UserModel> updateServiceRule(String token, String serviceRule) async {
    return await remoteDataSource.updateServiceRule(token, serviceRule);
  }

  @override
  Future<UserModel> getProfile(String token) async {
    return await remoteDataSource.getProfile(token);
  }

  @override
  Future<void> updateFcmToken(String token, String fcmToken) async {
    return await remoteDataSource.updateFcmToken(token, fcmToken);
  }
}
