import '../../data/models/service_model.dart';
import '../../data/models/category_model.dart';

abstract class ServiceRepository {
  Future<List<ServiceModel>> getProviderServices(String token);
  Future<ServiceModel> createService(String token, Map<String, dynamic> data);
  Future<List<CategoryModel>> getCategories({String? type});
}
