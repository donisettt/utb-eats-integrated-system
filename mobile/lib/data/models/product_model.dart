/// Model untuk data produk/menu
class ProductModel {
  final int id;
  final int merchantId;
  final String name;
  final String description;
  final int price;
  final String? image;
  final bool isAvailable;
  final int categoryId;

  ProductModel({
    required this.id,
    required this.merchantId,
    required this.name,
    required this.description,
    required this.price,
    this.image,
    required this.isAvailable,
    required this.categoryId,
  });

  /// Membuat ProductModel dari JSON response API
  factory ProductModel.fromJson(Map<String, dynamic> json) {
    return ProductModel(
      id: json['id'],
      merchantId: int.tryParse(json['merchant_id'].toString()) ?? 0,
      name: json['name'],
      description: json['description'] ?? '',
      price: (double.tryParse(json['price'].toString()) ?? 0).toInt(),
      image: json['image'],
      isAvailable: json['is_available'] == 1 || json['is_available'] == true,
      categoryId: int.tryParse(json['category_id'].toString()) ?? 1,
    );
  }
}
