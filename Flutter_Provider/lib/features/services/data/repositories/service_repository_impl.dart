import '../../domain/repositories/service_repository.dart';
import '../../data/datasources/service_remote_data_source.dart';
import '../../data/models/service_model.dart';
import '../../data/models/category_model.dart';

class ServiceRepositoryImpl implements ServiceRepository {
  final ServiceRemoteDataSource remoteDataSource;

  ServiceRepositoryImpl({required this.remoteDataSource});

  @override
  Future<List<ServiceModel>> getProviderServices(String token) async {
    return await remoteDataSource.getProviderServices(token);
  }

  @override
  Future<ServiceModel> createService(
    String token,
    Map<String, dynamic> data,
  ) async {
    return await remoteDataSource.createService(token, data);
  }

  @override
  Future<List<CategoryModel>> getCategories({String? type}) async {
    return await remoteDataSource.getCategories(type: type);
  }
}
