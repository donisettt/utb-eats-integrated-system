import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../services/auth_service.dart';

/// Provider untuk manajemen autentikasi (login, register)
class AuthProvider with ChangeNotifier {
  final AuthService _authService = AuthService();

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  /// Melakukan login dan menyimpan data user ke SharedPreferences
  Future<bool> login(
    String email,
    String password,
    BuildContext context,
  ) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _authService.login(email, password);
      final prefs = await SharedPreferences.getInstance();

      if (response.containsKey('access_token')) {
        await prefs.setString('token', response['access_token']);
      }

      if (response.containsKey('user')) {
        final user = response['user'];

        await prefs.setString('user_name', user['name']);
        await prefs.setString('user_email', user['email']);
        await prefs.setString('user_role', user['role']);

        if (user['phone'] != null) {
          await prefs.setString('user_phone', user['phone']);
        } else {
          await prefs.remove('user_phone');
        }

        if (user['profile_photo_path'] != null) {
          await prefs.setString('user_photo', user['profile_photo_path']);
        } else {
          await prefs.remove('user_photo');
        }
      }

      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      print("Error Login: $e");
      _isLoading = false;
      notifyListeners();

      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Gagal: $e"), backgroundColor: Colors.red),
        );
      }
      return false;
    }
  }

  /// Melakukan registrasi user baru dan menyimpan data ke SharedPreferences
  Future<bool> register(
    String name,
    String email,
    String password,
    String phone,
    BuildContext context,
  ) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _authService.register(
        name,
        email,
        password,
        phone,
      );

      final prefs = await SharedPreferences.getInstance();
      if (response.containsKey('access_token')) {
        await prefs.setString('token', response['access_token']);
      }
      if (response.containsKey('user')) {
        final user = response['user'];
        await prefs.setString('user_name', user['name']);
        await prefs.setString('user_email', user['email']);
        await prefs.setString('user_role', user['role']);
        await prefs.setString('user_phone', user['phone']);
      }

      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Gagal: $e"), backgroundColor: Colors.red),
        );
      }
      return false;
    }
  }
}
