/// Model untuk data kategori produk
class CategoryModel {
  final int id;
  final String name;

  CategoryModel({required this.id, required this.name});

  /// Membuat CategoryModel dari JSON response API
  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    return CategoryModel(id: json['id'], name: json['name']);
  }
}
