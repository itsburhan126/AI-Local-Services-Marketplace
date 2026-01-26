import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import '../../domain/repositories/auth_repository.dart';
import '../../data/models/user_model.dart';

class AuthProvider extends ChangeNotifier {
  final AuthRepository authRepository;
  final SharedPreferences sharedPreferences;
  final FlutterSecureStorage secureStorage = const FlutterSecureStorage();

  AuthProvider({
    required this.authRepository,
    required this.sharedPreferences,
  }) {
    _loadUser();
  }

  UserModel? _user;
  bool _isLoading = false;
  String? _error;

  UserModel? get user => _user;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isAuthenticated => _user != null && _user!.token != null;

  void setUser(UserModel user) {
    debugPrint(
      'AuthProvider: setUser called. Mode: ${user.mode}, ServiceRule: ${user.serviceRule}',
    );
    _user = user;
    notifyListeners();
  }

  Future<void> _loadUser() async {
    debugPrint('AuthProvider: _loadUser started');
    final token = await secureStorage.read(key: 'auth_token');
    if (token != null) {
      // FIX: Prevent overwriting if we already have a full user (e.g. set by SplashPage)
      if (_user != null && _user!.id != null) {
        debugPrint(
          'AuthProvider: _loadUser skipped overwriting because full user exists.',
        );
        return;
      }

      debugPrint('AuthProvider: _loadUser found token. Setting dummy user.');
      // In a real app, you might validate the token or fetch user profile here
      final savedMode = sharedPreferences.getString('user_mode');
      final savedServiceRule = sharedPreferences.getString('user_service_rule');

      _user = UserModel(
        token: token,
        mode: savedMode,
        serviceRule: savedServiceRule,
      );
      updateFcmToken();
      notifyListeners();
    } else {
      debugPrint('AuthProvider: _loadUser no token found.');
    }
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final user = await authRepository.login(email, password);
      _user = user;
      if (user.token != null) {
        await secureStorage.write(key: 'auth_token', value: user.token!);
        // Keep SharedPreferences for non-sensitive data if needed
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

  Future<bool> register(
    String name,
    String email,
    String password,
    String passwordConfirmation, {
    String? mode,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final user = await authRepository.register(
        name,
        email,
        password,
        passwordConfirmation,
        mode: mode,
      );
      _user = user;
      if (user.token != null) {
        await secureStorage.write(key: 'auth_token', value: user.token!);
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
    await secureStorage.delete(key: 'auth_token');
    await sharedPreferences.remove('auth_token');
    notifyListeners();
  }

  Future<bool> updateProviderMode(String mode) async {
    if (_user == null || _user!.token == null) return false;

    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      await authRepository.updateProviderMode(_user!.token!, mode);

      // Update local user model
      _user = UserModel(
        id: _user!.id,
        name: _user!.name,
        email: _user!.email,
        role: _user!.role,
        token: _user!.token,
        mode: mode,
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
}
