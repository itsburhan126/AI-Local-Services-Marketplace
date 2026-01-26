import 'package:flutter/material.dart';
import '../../domain/repositories/service_repository.dart';
import '../../data/models/service_model.dart';
import '../../data/models/category_model.dart';
import '../../../auth/presentation/providers/auth_provider.dart';

class ServiceProvider extends ChangeNotifier {
  final ServiceRepository serviceRepository;
  final AuthProvider authProvider;

  ServiceProvider({
    required this.serviceRepository,
    required this.authProvider,
  });

  List<ServiceModel> _services = [];
  List<CategoryModel> _categories = [];
  bool _isLoading = false;
  String? _error;

  List<ServiceModel> get services => _services;
  List<CategoryModel> get categories => _categories;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchServices() async {
    final token = authProvider.user?.token;
    if (token == null) return;

    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      _services = await serviceRepository.getProviderServices(token);
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchCategories() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      // Filter by provider mode if available
      final mode = authProvider.user?.mode;
      _categories = await serviceRepository.getCategories(type: mode);
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> createService(Map<String, dynamic> data) async {
    final token = authProvider.user?.token;
    if (token == null) return false;

    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      // Inject provider mode
      final mode = authProvider.user?.mode;
      if (mode != null) {
        data['type'] = mode;
      }

      final service = await serviceRepository.createService(token, data);
      _services.insert(0, service);
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _isLoading = false;
      _error = e.toString().replaceAll('Exception: ', '');
      notifyListeners();
      return false;
    }
  }
}
