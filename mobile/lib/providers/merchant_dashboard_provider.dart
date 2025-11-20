import 'dart:io';
import 'package:flutter/material.dart';
import '../data/models/order_model.dart';
import '../data/models/product_model.dart';
import '../data/models/category_model.dart';
import '../data/models/merchant_model.dart';
import '../services/merchant_service.dart';

/// Provider untuk manajemen merchant dashboard (order, produk, profil, laporan)
class MerchantDashboardProvider with ChangeNotifier {
  final MerchantService _service = MerchantService();

  List<OrderModel> _pendingOrders = [];
  List<OrderModel> _processOrders = [];
  List<OrderModel> _completedOrders = [];
  List<ProductModel> _merchantProducts = [];
  List<CategoryModel> _categories = [];
  MerchantModel? _merchantProfile;
  int _totalRevenue = 0;
  int _totalOrders = 0;
  List<OrderModel> _recentTransactions = [];
  bool _isLoading = false;

  List<OrderModel> get pendingOrders => _pendingOrders;
  List<OrderModel> get processOrders => _processOrders;
  List<OrderModel> get completedOrders => _completedOrders;
  List<ProductModel> get merchantProducts => _merchantProducts;
  List<CategoryModel> get categories => _categories;
  MerchantModel? get merchantProfile => _merchantProfile;
  int get totalRevenue => _totalRevenue;
  int get totalOrders => _totalOrders;
  List<OrderModel> get recentTransactions => _recentTransactions;
  bool get isLoading => _isLoading;

  /// Memuat semua order berdasarkan status (pending, cooking, completed)
  Future<void> loadAllOrders() async {
    _isLoading = true;
    notifyListeners();

    try {
      final results = await Future.wait([
        _service.getOrders('pending'),
        _service.getOrders('cooking'),
        _service.getOrders('completed'),
      ]);

      _pendingOrders = results[0];
      _processOrders = results[1];
      _completedOrders = results[2];
    } catch (e) {
      print("Error loading orders: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Update status order dan refresh list
  Future<void> updateStatus(int orderId, String status) async {
    bool success = await _service.updateOrderStatus(orderId, status);
    if (success) {
      loadAllOrders();
    }
  }

  /// Memuat daftar produk merchant
  Future<void> loadMerchantProducts() async {
    _isLoading = true;
    notifyListeners();
    try {
      _merchantProducts = await _service.getMerchantProducts();
    } catch (e) {
      print("Error loading products: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Memuat daftar kategori produk
  Future<void> loadCategories() async {
    try {
      _categories = await _service.getCategories();
      notifyListeners();
    } catch (e) {
      print("Error loading categories: $e");
    }
  }

  /// Menambahkan produk baru
  Future<bool> addProduct(
    String name,
    int price,
    String desc,
    int categoryId,
    bool isAvailable,
    File? image,
  ) async {
    _isLoading = true;
    notifyListeners();

    bool success = await _service.addProduct(
      name,
      price,
      desc,
      categoryId,
      isAvailable,
      image,
    );

    if (success) {
      await loadMerchantProducts();
    }

    _isLoading = false;
    notifyListeners();
    return success;
  }

  /// Update produk yang sudah ada
  Future<bool> updateProduct(
    int id,
    String name,
    int price,
    String desc,
    int categoryId,
    bool isAvailable,
    File? image,
  ) async {
    _isLoading = true;
    notifyListeners();

    bool success = await _service.updateProduct(
      id,
      name,
      price,
      desc,
      categoryId,
      isAvailable,
      image,
    );

    if (success) {
      await loadMerchantProducts();
    }

    _isLoading = false;
    notifyListeners();
    return success;
  }

  /// Menghapus produk
  Future<bool> deleteProduct(int id) async {
    _isLoading = true;
    notifyListeners();

    bool success = await _service.deleteProduct(id);

    if (success) {
      await loadMerchantProducts();
    }

    _isLoading = false;
    notifyListeners();
    return success;
  }

  /// Memuat profil merchant/toko
  Future<void> loadShopProfile() async {
    _isLoading = true;
    notifyListeners();

    try {
      _merchantProfile = await _service.getProfile();
    } catch (e) {
      print("Error loading profile: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Update profil merchant/toko
  Future<bool> updateShopProfile(
    String name,
    String address,
    String desc,
    String open,
    String close,
    File? img,
  ) async {
    _isLoading = true;
    notifyListeners();

    bool success = await _service.updateProfile(
      shopName: name,
      address: address,
      description: desc,
      openTime: open,
      closeTime: close,
      imageFile: img,
    );

    if (success) {
      await loadShopProfile();
    }

    _isLoading = false;
    notifyListeners();
    return success;
  }

  /// Memuat laporan keuangan (mencoba dari API, fallback ke perhitungan manual)
  Future<void> loadReport() async {
    _isLoading = true;
    notifyListeners();

    try {
      final data = await _service.getReport();

      if (data.isNotEmpty && data['total_revenue'] != null) {
        _totalRevenue = int.tryParse(data['total_revenue'].toString()) ?? 0;
        _totalOrders = int.tryParse(data['total_orders'].toString()) ?? 0;

        if (data['recent_transactions'] != null) {
          List transactions = data['recent_transactions'];
          _recentTransactions = transactions
              .map((e) => OrderModel.fromJson(e))
              .toList();
        }

        if (_totalRevenue == 0 && _totalOrders > 0) {
          await _calculateReportManually();
        }
      } else {
        await _calculateReportManually();
      }
    } catch (e) {
      print('Error loading report from API: $e');
      await _calculateReportManually();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  /// Menghitung laporan secara manual dari order yang completed
  Future<void> _calculateReportManually() async {
    try {
      final allCompletedOrders = await _service.getOrders('completed');

      _recentTransactions = allCompletedOrders.where((order) {
        final status = order.status.toLowerCase();
        return status == 'completed' ||
            status == 'delivered' ||
            status == 'finished' ||
            status == 'selesai' ||
            status == 'success';
      }).toList();

      _totalRevenue = 0;
      for (var order in _recentTransactions) {
        _totalRevenue += order.totalPrice;
      }

      _totalOrders = _recentTransactions.length;

      _recentTransactions.sort((a, b) {
        try {
          return DateTime.parse(b.createdAt).compareTo(DateTime.parse(a.createdAt));
        } catch (e) {
          return 0;
        }
      });
    } catch (e) {
      print('Error calculating report manually: $e');
      _resetReportData();
    }
  }

  /// Reset data laporan ke nilai default
  void _resetReportData() {
    _totalRevenue = 0;
    _totalOrders = 0;
    _recentTransactions = [];
  }

  /// Force refresh laporan dengan perhitungan manual
  Future<void> forceRefreshReport() async {
    _isLoading = true;
    notifyListeners();

    await _calculateReportManually();

    _isLoading = false;
    notifyListeners();
  }
}
