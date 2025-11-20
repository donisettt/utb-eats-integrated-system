/// Model untuk data merchant/toko
class MerchantModel {
  final int id;
  final String shopName;
  final String description;
  final String address;
  final String? openingTime;
  final String? closingTime;
  final bool isOpen;
  final String? image;

  MerchantModel({
    required this.id,
    required this.shopName,
    required this.description,
    required this.address,
    this.openingTime,
    this.closingTime,
    required this.isOpen,
    this.image,
  });

  /// Membuat MerchantModel dari JSON response API
  factory MerchantModel.fromJson(Map<String, dynamic> json) {
    return MerchantModel(
      id: json['id'],
      shopName: json['shop_name'] ?? 'Tanpa Nama',
      description: json['description'] ?? '',
      address: json['address'] ?? '',
      openingTime: json['opening_time'],
      closingTime: json['closing_time'],
      isOpen: json['is_open'] == 1 || json['is_open'] == true,
      image: json['image'],
    );
  }
}
