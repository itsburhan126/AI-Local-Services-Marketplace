import 'package:intl/intl.dart';

class WalletTransactionModel {
  final int id;
  final double amount;
  final String type;
  final String? description;
  final int? referenceId;
  final String? referenceType;
  final String status;
  final DateTime? availableAt;
  final DateTime createdAt;

  WalletTransactionModel({
    required this.id,
    required this.amount,
    required this.type,
    this.description,
    this.referenceId,
    this.referenceType,
    required this.status,
    this.availableAt,
    required this.createdAt,
  });

  factory WalletTransactionModel.fromJson(Map<String, dynamic> json) {
    return WalletTransactionModel(
      id: int.parse(json['id'].toString()),
      amount: double.tryParse(json['amount'].toString()) ?? 0.0,
      type: json['type'] ?? 'credit',
      description: json['description'],
      referenceId: json['reference_id'] != null ? int.tryParse(json['reference_id'].toString()) : null,
      referenceType: json['reference_type'],
      status: json['status'] ?? 'completed',
      availableAt: json['available_at'] != null ? DateTime.tryParse(json['available_at'].toString()) : null,
      createdAt: json['created_at'] != null ? DateTime.tryParse(json['created_at'].toString()) ?? DateTime.now() : DateTime.now(),
    );
  }

  String formattedAvailableAt() {
    if (availableAt == null) return '';
    return DateFormat('MMM d, yyyy').format(availableAt!);
  }
}
