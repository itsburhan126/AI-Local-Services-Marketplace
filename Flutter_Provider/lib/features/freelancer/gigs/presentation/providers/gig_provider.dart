import 'dart:io';
import 'dart:async';
import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_provider/features/freelancer/gigs/data/models/gig_analytics_model.dart';
import '../../data/models/gig_model.dart';
import '../../data/models/tag_model.dart';
import '../../../../services/data/models/category_model.dart';
import '../../../../services/data/models/service_type_model.dart';
import '../../domain/repositories/gig_repository.dart';
import '../../data/repositories/gig_repository_impl.dart';
import '../../data/datasources/gig_remote_data_source.dart';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

final gigRemoteDataSourceProvider = Provider<GigRemoteDataSource>((ref) {
  final dio = Dio();
  if (kDebugMode) {
    dio.interceptors.add(
      LogInterceptor(
        request: true,
        requestHeader: true,
        requestBody: true,
        responseHeader: true,
        responseBody: true,
        error: true,
      ),
    );
  }
  return GigRemoteDataSourceImpl(dio: dio);
});

final gigRepositoryProvider = Provider<GigRepository>((ref) {
  return GigRepositoryImpl(
    remoteDataSource: ref.watch(gigRemoteDataSourceProvider),
    sharedPreferences: ref.watch(sharedPreferencesProvider),
  );
});

final sharedPreferencesProvider = Provider<SharedPreferences>((ref) {
  throw UnimplementedError();
});

final serviceTypesProvider = FutureProvider<List<ServiceTypeModel>>((
  ref,
) async {
  final repository = ref.watch(gigRepositoryProvider);
  return repository.getServiceTypes();
});

final categoriesProvider = FutureProvider.family<List<CategoryModel>, int?>((ref, parentId) async {
  final repository = ref.watch(gigRepositoryProvider);
  return repository.getCategories(parentId: parentId);
});

final tagsProvider = FutureProvider.family<List<TagModel>, String?>((
  ref,
  query,
) async {
  final repository = ref.watch(gigRepositoryProvider);
  return repository.getTags(query: query);
});

final providerGigsProvider = FutureProvider<List<GigModel>>((ref) async {
  final repository = ref.watch(gigRepositoryProvider);
  return repository.getProviderGigs().timeout(
    const Duration(seconds: 15),
    onTimeout: () => throw TimeoutException(
      'Connection timed out. Please check your internet connection.',
    ),
  );
});

final gigAnalyticsProvider = FutureProvider.autoDispose.family<GigAnalyticsModel, int>((ref, id) async {
  final repository = ref.watch(gigRepositoryProvider);
  return repository.getGigAnalytics(id);
});

final gigDetailsProvider = FutureProvider.autoDispose.family<GigModel, int>((ref, id) async {
  final repository = ref.watch(gigRepositoryProvider);
  return repository.getGigDetails(id);
});

final gigControllerProvider = AsyncNotifierProvider<GigController, void>(() {
  return GigController();
});

class GigController extends AsyncNotifier<void> {
  @override
  FutureOr<void> build() {
    // Initial state is null (void)
    return null;
  }

  Future<void> createGig({
    required GigModel gig,
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  }) async {
    state = const AsyncValue.loading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(gigRepositoryProvider);
      await repository.createGig(
        gig: gig,
        thumbnail: thumbnail,
        images: images,
        video: video,
        documents: documents,
      );
      // Refresh the gigs list after successful creation
      ref.invalidate(providerGigsProvider);
    });
  }

  Future<void> updateGig({
    required int id,
    required GigModel gig,
    File? thumbnail,
    List<File>? images,
    File? video,
    List<File>? documents,
  }) async {
    state = const AsyncValue.loading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(gigRepositoryProvider);
      await repository.updateGig(
        id: id,
        gig: gig,
        thumbnail: thumbnail,
        newImages: images,
        newVideo: video,
        newDocuments: documents,
      );
      // Refresh the gigs list after successful update
      ref.invalidate(providerGigsProvider);
    });
  }

  Future<void> deleteGig(int id) async {
    state = const AsyncValue.loading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(gigRepositoryProvider);
      await repository.deleteGig(id);
      ref.invalidate(providerGigsProvider);
    });
  }

  Future<void> updateGigStatus(int id, String status) async {
    state = const AsyncValue.loading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(gigRepositoryProvider);
      await repository.updateGigStatus(id, status);
      ref.invalidate(providerGigsProvider);
      ref.invalidate(gigDetailsProvider(id));
    });
  }
}
