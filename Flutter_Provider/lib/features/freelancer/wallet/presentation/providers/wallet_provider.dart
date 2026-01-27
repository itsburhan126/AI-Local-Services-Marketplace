import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import '../../../../auth/presentation/providers/auth_provider.dart';
import '../../data/services/wallet_service.dart';
import '../../data/models/wallet_transaction_model.dart';

class WalletProvider extends ChangeNotifier {
  final WalletService _service;
  AuthProvider? _authProvider;
  bool _isLoading = false;
  String? _error;
  double _walletBalance = 0.0;
  double _pendingBalance = 0.0;
  List<WalletTransactionModel> _transactions = [];

  WalletProvider(Dio dio) : _service = WalletService(dio);

  void update(AuthProvider authProvider) {
    _authProvider = authProvider;
    notifyListeners();
  }

  bool get isLoading => _isLoading;
  String? get error => _error;
  double get walletBalance => _walletBalance;
  double get pendingBalance => _pendingBalance;
  List<WalletTransactionModel> get transactions => _transactions;

  double get withdrawnTotal {
    return _transactions
        .where((t) => t.type == 'debit' && t.referenceType == 'withdrawal')
        .fold(0.0, (sum, t) => sum + t.amount);
  }

  double get earningsToDate {
    return _transactions
        .where((t) => t.type == 'credit')
        .fold(0.0, (sum, t) => sum + t.amount);
  }

  List<WalletTransactionModel> get pendingTransactions {
    return _transactions.where((t) => t.status == 'pending').toList();
  }

  Future<void> fetchWallet() async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final token = _authProvider?.user?.token;
      if (token == null) {
        _error = 'Unauthenticated';
        return;
      }
      final result = await _service.getWallet(token);
      _walletBalance = result['wallet_balance'] as double;
      _pendingBalance = result['pending_balance'] as double;
      _transactions = (result['transactions'] as List).cast<WalletTransactionModel>();
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
