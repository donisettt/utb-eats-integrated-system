/// Model untuk data banner
class BannerModel {
  final int id;
  final String title;
  final String imagePath;

  BannerModel({required this.id, required this.title, required this.imagePath});

  /// Membuat BannerModel dari JSON response API
  factory BannerModel.fromJson(Map<String, dynamic> json) {
    return BannerModel(
      id: json['id'],
      title: json['title'] ?? '',
      imagePath: json['image_path'] ?? '',
    );
  }
}