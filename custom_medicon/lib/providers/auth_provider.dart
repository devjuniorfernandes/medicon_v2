import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../services/auth_service.dart';
import '../services/api_service.dart';
import 'package:firebase_messaging/firebase_messaging.dart';

class AuthProvider with ChangeNotifier {
  final AuthService _authService = AuthService();

  User? _user;
  String? _token;
  bool _isLoading = false;

  User? get user => _user;
  String? get token => _token;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _token != null;

  AuthProvider() {
    _loadUserFromStorage();
  }

  Future<void> _loadUserFromStorage() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');

    if (_token != null) {
      try {
        _user = await _authService.getUser(_token!);
      } catch (e) {
        // Token might be invalid or expired
        _token = null;
        await prefs.remove('auth_token');
      }
    }
    notifyListeners();
  }

  Future<void> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();

    try {
      final data = await _authService.login(email, password);
      _token = data['token'];
      _user = User.fromJson(data['user']);

      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', _token!);

      await _setupFcmToken();

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      rethrow;
    }
  }

  Future<void> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    _isLoading = true;
    notifyListeners();

    try {
      final data = await _authService.register(
        name,
        email,
        password,
        passwordConfirmation,
      );
      _token = data['token'];
      _user = User.fromJson(data['user']);

      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', _token!);

      await _setupFcmToken();

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      rethrow;
    }
  }

  Future<void> _setupFcmToken() async {
    try {
      final String? fcmToken = await FirebaseMessaging.instance.getToken();
      if (fcmToken != null && _token != null) {
        // Use ApiService to send the token with auth header
        final apiService = ApiService();
        await apiService.sendFcmToken(_token!, fcmToken);
      }
    } catch (e) {
      print('Could not setup FCM token: $e');
    }
  }

  Future<void> logout() async {
    _user = null;
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    notifyListeners();
  }

  /// Update the current user's avatar URL and notify listeners.
  /// This creates a new User instance preserving other fields.
  void updateAvatarUrl(String url) {
    if (_user == null) return;
    _user = User(
      id: _user!.id,
      name: _user!.name,
      email: _user!.email,
      role: _user!.role,
      avatarUrl: url,
    );
    notifyListeners();
  }
}
