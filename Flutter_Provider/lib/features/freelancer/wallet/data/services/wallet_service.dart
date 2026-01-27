import 'package:dio/dio.dart';
import '../models/wallet_transaction_model.dart';

class WalletService {
  final Dio _dio;
  WalletService(this._dio);

  Future<Map<String, dynamic>> getWallet(String token, {int page = 1}) async {
    final response = await _dio.get(
      '/api/freelancer/wallet',
      queryParameters: {'page': page},
      options: Options(headers: {'Authorization': 'Bearer $token'}),
    );
    if (response.statusCode == 200 &&
        (response.data['status'] == true || response.data['status'] == 'success')) {
      final data = response.data['data'];
      final txList = (data['transactions']['data'] as List?) ?? [];
      final transactions =
          txList.map((e) => WalletTransactionModel.fromJson(e)).toList();
      return {
        'wallet_balance':
            double.tryParse(data['wallet_balance'].toString()) ?? 0.0,
        'pending_balance':
            double.tryParse(data['pending_balance'].toString()) ?? 0.0,
        'transactions': transactions,
        'meta': data['transactions'],
      };
    }
    return {
      'wallet_balance': 0.0,
      'pending_balance': 0.0,
      'transactions': <WalletTransactionModel>[],
      'meta': null,
    };
  }
}
