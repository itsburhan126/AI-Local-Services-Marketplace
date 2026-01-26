import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import '../../domain/repositories/auth_repository.dart';
import '../../data/models/user_model.dart';

class AuthProvider extends ChangeNotifier {
  final AuthRepository authRepository;
  final SharedPreferences sharedPreferences;

  AuthProvider({required this.authRepository, required this.sharedPreferences}) {
    _loadUser();
  }

  Future<void> updateFcmToken() async {
    if (_user?.token == null) return;
    try {
      final messaging = FirebaseMessaging.instance;
      NotificationSettings settings = await messaging.requestPermission(
        alert: true,
        badge: true,
        sound: true,
      );

      if (settings.authorizationStatus == AuthorizationStatus.authorized) {
        String? token = await messaging.getToken();
        if (token != null) {
          debugPrint("FCM Token: $token");
          // Call repository to save token
          await authRepository.updateFcmToken(_user!.token!, token);
        }
      }
    } catch (e) {
      debugPrint("Error updating FCM token: $e");
    }
  }

  UserModel? _user;
  bool _isLoading = false;
  String? _error;

  UserModel? get user => _user;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isAuthenticated => _user != null && _user!.token != null;

  Future<void> _loadUser() async {
    final token = sharedPreferences.getString('auth_token');
    final serviceRule = sharedPreferences.getString('service_rule');
    if (token != null) {
      _user = UserModel(
        token: token,
        serviceRule: serviceRule,
      );
      // Don't notify yet, wait for profile
      await fetchUserProfile();
      updateFcmToken(); // Update FCM token on load
    }
  }

  Future<void> fetchUserProfile() async {
    if (_user?.token == null) return;

    try {
      final userProfile = await authRepository.getProfile(_user!.token!);
      // Merge token into profile user model as profile response might not have it
      // Also preserve serviceRule if API returns null but we have it locally
      String? rule = userProfile.serviceRule;
      if (rule == null) {
        rule = _user?.serviceRule ?? sharedPreferences.getString('service_rule');
      }

      _user = UserModel(
        id: userProfile.id,
        name: userProfile.name,
        email: userProfile.email,
        role: userProfile.role,
        serviceRule: rule,
        token: _user!.token,
      );
      
      if (rule != null) {
        await sharedPreferences.setString('service_rule', rule);
      }
      
    } catch (e) {
      debugPrint('Failed to fetch profile: $e');
      // If unauthorized, maybe logout? For now keep local token but maybe invalid
      if (e.toString().contains('Unauthorized') || e.toString().contains('401')) {
        await logout();
      }
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final user = await authRepository.login(email, password);
      _user = user;
      if (user.token != null) {
        await sharedPreferences.setString('auth_token', user.token!);
        updateFcmToken(); // Update FCM token on login
      }
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

  Future<bool> register(String name, String email, String password, String passwordConfirmation) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      var user = await authRepository.register(name, email, password, passwordConfirmation);
      
      // If registration successful but no token returned, try to auto-login
      if (user.token == null) {
        try {
          user = await authRepository.login(email, password);
        } catch (e) {
          // Auto-login failed, proceed with registered user (will likely redirect to login/intro)
          debugPrint('Auto-login failed after registration: $e');
        }
      }

      _user = user;
      if (user.token != null) {
        await sharedPreferences.setString('auth_token', user.token!);
        updateFcmToken();
      }
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

  Future<void> logout() async {
    _user = null;
    await sharedPreferences.remove('auth_token');
    await sharedPreferences.remove('service_rule');
    notifyListeners();
  }

  Future<bool> updateProfile(Map<String, dynamic> data) async {
    if (_user?.token == null) return false;

    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final updatedUser = await authRepository.updateProfile(_user!.token!, data);
      
      // Preserve token if not returned
      _user = UserModel(
        id: updatedUser.id,
        name: updatedUser.name,
        email: updatedUser.email,
        role: updatedUser.role,
        token: _user!.token,
      );
      
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

  Future<bool> updateServiceRule(String serviceRule) async {
    if (_user?.token == null) return false;

    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final updatedUser = await authRepository.updateServiceRule(_user!.token!, serviceRule);
      
      _user = UserModel(
        id: updatedUser.id,
        name: updatedUser.name,
        email: updatedUser.email,
        role: updatedUser.role,
        serviceRule: updatedUser.serviceRule,
        token: _user!.token,
      );
      
      if (updatedUser.serviceRule != null) {
        await sharedPreferences.setString('service_rule', updatedUser.serviceRule!);
      }
      
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
