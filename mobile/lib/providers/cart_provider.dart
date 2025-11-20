import 'package:flutter/material.dart';
import '../data/models/cart_item_model.dart';
import '../data/models/product_model.dart';
import '../services/order_service.dart';

/// Provider untuk manajemen cart/keranjang belanja
class CartProvider with ChangeNotifier {
  final OrderService _orderService = OrderService();
  final List<CartItemModel> _items = [];

  List<CartItemModel> get items => _items;

  /// Menghitung total harga semua item di cart
  int get totalAmount {
    int total = 0;
    for (var item in _items) {
      total += item.totalPrice;
    }
    return total;
  }

  /// Menghitung total jumlah item di cart
  int get totalItems {
    int count = 0;
    for (var item in _items) {
      count += item.quantity;
    }
    return count;
  }

  /// Menambahkan produk ke cart
  void addItem(ProductModel product) {
    final existingIndex = _items.indexWhere(
      (item) => item.product.id == product.id,
    );

    if (existingIndex >= 0) {
      _items[existingIndex].quantity += 1;
    } else {
      _items.add(CartItemModel(product: product));
    }
    notifyListeners();
  }

  /// Mengurangi quantity item di cart
  void removeSingleItem(int productId) {
    final existingIndex = _items.indexWhere(
      (item) => item.product.id == productId,
    );
    if (existingIndex >= 0) {
      if (_items[existingIndex].quantity > 1) {
        _items[existingIndex].quantity -= 1;
      } else {
        _items.removeAt(existingIndex);
      }
      notifyListeners();
    }
  }

  /// Menghapus item dari cart
  void removeItem(int productId) {
    _items.removeWhere((item) => item.product.id == productId);
    notifyListeners();
  }

  /// Update catatan untuk item di cart
  void updateNote(int productId, String newNote) {
    final index = _items.indexWhere((item) => item.product.id == productId);
    if (index >= 0) {
      _items[index].note = newNote;
    }
  }

  /// Mengosongkan cart
  void clear() {
    _items.clear();
    notifyListeners();
  }

  /// Melakukan checkout dengan alamat pengiriman
  Future<bool> checkout(String address) async {
    if (_items.isEmpty) return false;

    bool success = await _orderService.createOrder(
      _items,
      totalAmount,
      address,
    );

    if (success) {
      clear();
    }
    return success;
  }
}
