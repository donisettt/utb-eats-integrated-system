/// API constants untuk semua endpoint dan base URL
class ApiConstants {
  static const String baseUrl = 'https://uncondemnable-ariana-undependable.ngrok-free.dev/api';
  static const String baseImage = 'https://uncondemnable-ariana-undependable.ngrok-free.dev/storage/';

  static const String login = '$baseUrl/login';
  static const String register = '$baseUrl/register';
  static const String banners = '$baseUrl/banners';
  static const String merchants = '$baseUrl/merchants';
  static const String searchProducts = '$baseUrl/products/search';
  static const String orders = '$baseUrl/orders';
  static const String merchantOrders = '$baseUrl/merchant/orders';
  static const String merchantProfile = '$baseUrl/merchant/profile';
}