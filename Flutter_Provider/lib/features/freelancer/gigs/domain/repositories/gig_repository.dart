import 'dart:io';
import '../../data/models/gig_analytics_model.dart';
import '../../data/models/gig_model.dart';
import '../../data/models/tag_model.dart';
import '../../data/models/paginated_reviews_model.dart';
import '../../../../services/data/models/category_model.dart';
import '../../../../services/data/models/service_type_model.dart';

abstract class GigRepository {
  Future<GigModel> createGig({
    required GigModel gig,
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  });

  Future<List<GigModel>> getProviderGigs();
  Future<GigAnalyticsModel> getGigAnalytics(int id);
  Future<PaginatedReviewsModel> getGigReviews(int id, int page);
  Future<GigModel> getGigDetails(int id);
  Future<void> deleteGig(int id);
  Future<GigModel> updateGig({
    required int id,
    required GigModel gig,
    File? thumbnail,
    List<File>? newImages,
    File? newVideo,
    List<File>? newDocuments,
  });

  Future<List<ServiceTypeModel>> getServiceTypes();
  Future<List<CategoryModel>> getCategories();
  Future<List<TagModel>> getTags({String? query});
  Future<GigModel> updateGigStatus(int id, String status);
}
