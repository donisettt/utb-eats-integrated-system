/// Model untuk data order/pesanan
class OrderModel {
  final int id;
  final String status;
  final int totalPrice;
  final String createdAt;
  final String merchantName;
  final String? merchantImage;
  final String userName;
  final String deliveryLocation;

  OrderModel({
    required this.id,
    required this.status,
    required this.totalPrice,
    required this.createdAt,
    required this.merchantName,
    this.merchantImage,
    required this.userName,
    required this.deliveryLocation,
  });

  /// Membuat OrderModel dari JSON response API
  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
      id: json['id'],
      status: json['status'],
      totalPrice: int.tryParse(json['total_price'].toString().split('.')[0]) ?? 0,
      createdAt: json['created_at'],
      merchantName: json['merchant'] != null ? json['merchant']['shop_name'] : 'Unknown',
      merchantImage: json['merchant'] != null ? json['merchant']['image'] : null,
      userName: json['user'] != null ? json['user']['name'] : 'Pelanggan',
      deliveryLocation: json['delivery_location'] ?? 'Ambil di tempat',
    );
  }
}