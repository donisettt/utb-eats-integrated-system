import 'product_model.dart';

/// Model untuk item dalam keranjang belanja
class CartItemModel {
  final ProductModel product;
  int quantity;
  String? note;

  CartItemModel({
    required this.product,
    this.quantity = 1,
    this.note,
  });

  /// Menghitung total harga item (harga produk × quantity)
  int get totalPrice => product.price * quantity;
}
