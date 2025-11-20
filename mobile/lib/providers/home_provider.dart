import 'package:flutter/material.dart';
import '../core/api_constants.dart';
import '../data/models/banner_model.dart';
import '../data/models/merchant_model.dart';
import '../services/home_service.dart';
import '../data/models/product_model.dart';

/// Provider untuk manajemen data home screen (banner, merchant, search)
class HomeProvider with ChangeNotifier {
  final HomeService _service = HomeService();

  List<BannerModel> _banners = [];
  List<MerchantModel> _merchants = [];
  bool _isLoading = true;

  List<BannerModel> get banners => _banners;
  List<MerchantModel> get merchants => _merchants;
  bool get isLoading => _isLoading;

  /// Memuat data awal untuk home screen (banner dan merchant)
  Future<void> getHomeData() async {
    _isLoading = true;
    notifyListeners();

    try {
      final results = await Future.wait([
        _service.getBanners(),
        _service.getMerchants(),
      ]);

      _banners = results[0] as List<BannerModel>;
      _merchants = results[1] as List<MerchantModel>;
    } catch (e) {
      print("Error loading home data: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  List<ProductModel> _searchResults = [];
  List<ProductModel> get searchResults => _searchResults;

  bool _isSearching = false;
  bool get isSearching => _isSearching;

  /// Mencari produk berdasarkan keyword
  Future<void> searchProducts(String keyword) async {
    _isSearching = true;
    _searchResults = [];
    notifyListeners();

    try {
      final options = await _service.getHeaders();
      final response = await _service.dio.get(
        ApiConstants.searchProducts,
        queryParameters: {'keyword': keyword},
        options: options,
      );

      List data = response.data['data'];
      _searchResults = data.map((e) => ProductModel.fromJson(e)).toList();
    } catch (e) {
      print("Error searching: $e");
    } finally {
      _isSearching = false;
      notifyListeners();
    }
  }
}