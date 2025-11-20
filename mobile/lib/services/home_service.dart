import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/api_constants.dart';
import '../data/models/banner_model.dart';
import '../data/models/merchant_model.dart';

/// Service untuk operasi home screen (banner, merchant)
class HomeService {
  final Dio dio = Dio();

  /// Mendapatkan headers dengan authorization token
  Future<Options> getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    return Options(headers: {
      'Authorization': 'Bearer $token',
      'Accept': 'application/json',
      'ngrok-skip-browser-warning': 'true',
    });
  }

  /// Mengambil daftar banner
  Future<List<BannerModel>> getBanners() async {
    try {
      final options = await getHeaders();
      final response = await dio.get(ApiConstants.banners, options: options);
      List data = response.data['data'];
      return data.map((e) => BannerModel.fromJson(e)).toList();
    } catch (e) {
      return [];
    }
  }

  /// Mengambil daftar merchant
  Future<List<MerchantModel>> getMerchants() async {
    try {
      final options = await getHeaders();
      final response = await dio.get(ApiConstants.merchants, options: options);
      List data = response.data['data'];
      return data.map((e) => MerchantModel.fromJson(e)).toList();
    } catch (e) {
      return [];
    }
  }
}