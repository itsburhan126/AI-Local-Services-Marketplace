import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import '../../../../../../core/constants/api_constants.dart';
import '../models/order_model.dart';

class RequestsService {
  final Dio _dio;

  RequestsService(this._dio);

  Future<List<OrderModel>> getOrders(String token, {String? status}) async {
    try {
      final response = await _dio.get(
        '${ApiConstants.baseUrl}/api/freelancer/orders',
        queryParameters: status != null ? {'status': status} : null,
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      if (response.statusCode == 200 && response.data['status'] == 'success') {
        final List data = response.data['data'];
        return data.map((e) => OrderModel.fromJson(e)).toList();
      }
      return [];
    } catch (e) {
      debugPrint('[RequestsService] getOrders error: $e');
      return [];
    }
  }

  Future<bool> updateOrderStatus(String token, int id, String status) async {
    try {
      final response = await _dio.patch(
        '${ApiConstants.baseUrl}/api/freelancer/orders/$id/status',
        data: {'status': status},
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );

      return response.statusCode == 200 &&
          response.data['status'] == 'success';
    } catch (e) {
      debugPrint('[RequestsService] updateOrderStatus error: $e');
      return false;
    }
  }
}
