import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/api_constants.dart';
import '../data/models/product_model.dart';

/// Provider untuk manajemen menu produk merchant
class MerchantProvider with ChangeNotifier {
  final Dio _dio = Dio();
  List<ProductModel> _menuList = [];
  bool _isLoading = false;

  List<ProductModel> get menuList => _menuList;
  bool get isLoading => _isLoading;

  /// Mengambil menu produk berdasarkan merchant ID
  Future<void> getMenuByMerchant(int merchantId) async {
    _isLoading = true;
    notifyListeners();

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('token');

      final response = await _dio.get(
        "${ApiConstants.baseUrl}/products",
        queryParameters: {'merchant_id': merchantId},
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
            'ngrok-skip-browser-warning': 'true',
          },
        ),
      );

      List data = response.data['data'];
      _menuList = data.map((e) => ProductModel.fromJson(e)).toList();
    } catch (e) {
      print("Error get menu: $e");
      _menuList = [];
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
