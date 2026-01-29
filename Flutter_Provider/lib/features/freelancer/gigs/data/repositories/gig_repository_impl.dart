import 'dart:io';
import '../../domain/repositories/gig_repository.dart';
import '../../data/datasources/gig_remote_data_source.dart';
import 'package:flutter_provider/features/freelancer/gigs/data/models/gig_analytics_model.dart';
import '../../data/models/gig_model.dart';
import '../../data/models/tag_model.dart';
import '../../data/models/paginated_reviews_model.dart';
import '../../../../services/data/models/category_model.dart';
import '../../../../services/data/models/service_type_model.dart';
import 'package:shared_preferences/shared_preferences.dart';

class GigRepositoryImpl implements GigRepository {
  final GigRemoteDataSource remoteDataSource;
  final SharedPreferences sharedPreferences;

  GigRepositoryImpl({
    required this.remoteDataSource,
    required this.sharedPreferences,
  });

  Future<String> _getToken() async {
    final token = sharedPreferences.getString('auth_token');
    if (token == null) throw Exception('Authentication token not found');
    return token;
  }

  @override
  Future<GigModel> createGig({
    required GigModel gig,
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  }) async {
    final token = await _getToken();
    return remoteDataSource.createGig(
      token,
      gig,
      thumbnail: thumbnail,
      images: images,
      video: video,
      documents: documents,
    );
  }

  @override
  Future<List<GigModel>> getProviderGigs() async {
    final token = await _getToken();
    return await remoteDataSource.getProviderGigs(token);
  }

  @override
  Future<GigAnalyticsModel> getGigAnalytics(int id) async {
    final token = await _getToken();
    return await remoteDataSource.getGigAnalytics(token, id);
  }

  @override
  Future<PaginatedReviewsModel> getGigReviews(int id, int page) async {
    final token = await _getToken();
    return await remoteDataSource.getGigReviews(token, id, page);
  }

  @override
  Future<GigModel> getGigDetails(int id) async {
    final token = await _getToken();
    return await remoteDataSource.getGigDetails(token, id);
  }

  @override
  Future<void> deleteGig(int id) async {
    final token = await _getToken();
    return await remoteDataSource.deleteGig(token, id);
  }

  @override
  Future<GigModel> updateGig({
    required int id,
    required GigModel gig,
    File? thumbnail,
    List<File>? newImages,
    File? newVideo,
    List<File>? newDocuments,
  }) async {
    final token = await _getToken();
    return await remoteDataSource.updateGig(
      token,
      gig,
      thumbnail: thumbnail,
      images: newImages,
      video: newVideo,
      documents: newDocuments,
    );
  }

  @override
  Future<List<ServiceTypeModel>> getServiceTypes() async {
    return await remoteDataSource.getServiceTypes();
  }

  @override
  Future<List<CategoryModel>> getCategories({int? parentId}) async {
    return await remoteDataSource.getCategories(parentId: parentId);
  }

  @override
  Future<List<TagModel>> getTags({String? query}) async {
    return await remoteDataSource.getTags(query: query);
  }

  @override
  Future<GigModel> updateGigStatus(int id, String status) async {
    final token = await _getToken();
    return await remoteDataSource.updateGigStatus(token, id, status);
  }
}
