import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/api_constants.dart';
import '../data/models/cart_item_model.dart';

/// Service untuk operasi order/pesanan
class OrderService {
  final Dio _dio = Dio();

  /// Membuat order baru dari cart items
  Future<bool> createOrder(
    List<CartItemModel> items,
    int totalPrice,
    String address,
  ) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('token');

      List<Map<String, dynamic>> orderItems = items.map((item) {
        return {
          'product_id': item.product.id,
          'quantity': item.quantity,
          'price': item.product.price,
          'note': item.note ?? '',
        };
      }).toList();

      int merchantId = items.isNotEmpty ? items.first.product.merchantId : 1;

      final response = await _dio.post(
        "${ApiConstants.baseUrl}/orders",
        data: {
          'merchant_id': merchantId,
          'total_price': totalPrice,
          'delivery_location': address,
          'items': orderItems,
        },
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Accept': 'application/json',
            'ngrok-skip-browser-warning': 'true',
          },
          validateStatus: (status) => status! < 500,
        ),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        return true;
      } else {
        print("Gagal Order: ${response.data}");
        return false;
      }
    } catch (e) {
      print("Checkout Error: $e");
      return false;
    }
  }
}
