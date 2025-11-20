import 'dart:io';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/api_constants.dart';
import '../data/models/order_model.dart';
import '../data/models/product_model.dart';
import '../data/models/category_model.dart';
import '../data/models/merchant_model.dart';

/// Service untuk operasi merchant dashboard (order, produk, profil, laporan)
class MerchantService {
  final Dio _dio = Dio();

  /// Mendapatkan headers dengan authorization token
  Future<Options> _getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    return Options(
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
        'ngrok-skip-browser-warning': 'true',
      },
    );
  }

  /// Mengambil daftar order berdasarkan status
  Future<List<OrderModel>> getOrders(String status) async {
    try {
      final options = await _getHeaders();
      final response = await _dio.get(
        ApiConstants.merchantOrders,
        queryParameters: {'status': status},
        options: options,
      );

      List data = response.data['data'];
      return data.map((e) => OrderModel.fromJson(e)).toList();
    } catch (e) {
      print("Error get merchant orders: $e");
      return [];
    }
  }

  /// Update status order
  Future<bool> updateOrderStatus(int orderId, String newStatus) async {
    try {
      final options = await _getHeaders();
      await _dio.post(
        "${ApiConstants.merchantOrders}/$orderId/status",
        data: {'status': newStatus},
        options: options,
      );
      return true;
    } catch (e) {
      print("Error update status: $e");
      return false;
    }
  }

  /// Mengambil daftar kategori produk
  Future<List<CategoryModel>> getCategories() async {
    try {
      final options = await _getHeaders();
      final response = await _dio.get(
        "${ApiConstants.baseUrl}/categories",
        options: options,
      );

      List data = response.data['data'];
      return data.map((e) => CategoryModel.fromJson(e)).toList();
    } catch (e) {
      return [];
    }
  }

  /// Mengambil daftar produk merchant
  Future<List<ProductModel>> getMerchantProducts() async {
    try {
      final options = await _getHeaders();
      final response = await _dio.get(
        "${ApiConstants.baseUrl}/merchant/products",
        options: options,
      );

      List data = response.data['data'];
      return data.map((e) => ProductModel.fromJson(e)).toList();
    } catch (e) {
      print("Error get products: $e");
      return [];
    }
  }

  /// Menambahkan produk baru
  Future<bool> addProduct(
    String name,
    int price,
    String desc,
    int categoryId,
    bool isAvailable,
    File? imageFile,
  ) async {
    try {
      final options = await _getHeaders();

      FormData formData = FormData.fromMap({
        'name': name,
        'price': price,
        'description': desc,
        'category_id': categoryId,
        'is_available': isAvailable ? 1 : 0,
        if (imageFile != null)
          'image': await MultipartFile.fromFile(
            imageFile.path,
            filename: 'product.jpg',
          ),
      });

      await _dio.post(
        "${ApiConstants.baseUrl}/merchant/products",
        data: formData,
        options: options,
      );
      return true;
    } catch (e) {
      print("Add Product Error: $e");
      return false;
    }
  }

  /// Update produk yang sudah ada
  Future<bool> updateProduct(
    int id,
    String name,
    int price,
    String desc,
    int categoryId,
    bool isAvailable,
    File? imageFile,
  ) async {
    try {
      final options = await _getHeaders();

      FormData formData = FormData.fromMap({
        '_method': 'PUT',
        'name': name,
        'price': price,
        'description': desc,
        'category_id': categoryId,
        'is_available': isAvailable ? 1 : 0,
        if (imageFile != null)
          'image': await MultipartFile.fromFile(
            imageFile.path,
            filename: 'product.jpg',
          ),
      });

      await _dio.post(
        "${ApiConstants.baseUrl}/merchant/products/$id",
        data: formData,
        options: options,
      );
      return true;
    } catch (e) {
      print("Update Product Error: $e");
      return false;
    }
  }

  /// Menghapus produk
  Future<bool> deleteProduct(int id) async {
    try {
      final options = await _getHeaders();
      await _dio.delete(
        "${ApiConstants.baseUrl}/merchant/products/$id",
        options: options,
      );
      return true;
    } catch (e) {
      print("Delete Product Error: $e");
      return false;
    }
  }

  /// Update profil merchant/toko
  Future<bool> updateProfile({
    required String shopName,
    required String address,
    required String description,
    required String openTime,
    required String closeTime,
    File? imageFile,
  }) async {
    try {
      final options = await _getHeaders();

      FormData formData = FormData.fromMap({
        'shop_name': shopName,
        'address': address,
        'description': description,
        'opening_time': openTime,
        'closing_time': closeTime,
        if (imageFile != null)
          'image': await MultipartFile.fromFile(
            imageFile.path,
            filename: 'shop_logo.jpg',
          ),
      });

      final response = await _dio.post(
        "${ApiConstants.baseUrl}/merchant/update",
        data: formData,
        options: options,
      );

      return response.statusCode == 200;
    } catch (e) {
      print("Update Merchant Profile Error: $e");
      return false;
    }
  }

  /// Mengambil profil merchant
  Future<MerchantModel?> getProfile() async {
    try {
      final options = await _getHeaders();
      final response = await _dio.get(
        ApiConstants.merchantProfile,
        options: options,
      );

      return MerchantModel.fromJson(response.data['data']);
    } catch (e) {
      print("Error get profile: $e");
      return null;
    }
  }

  /// Mengambil laporan keuangan merchant
  Future<Map<String, dynamic>> getReport() async {
    try {
      final options = await _getHeaders();
      final response = await _dio.get(
        "${ApiConstants.baseUrl}/merchant/report",
        options: options,
      );
      return response.data['data'];
    } catch (e) {
      print("Error get report: $e");
      return {};
    }
  }
}
