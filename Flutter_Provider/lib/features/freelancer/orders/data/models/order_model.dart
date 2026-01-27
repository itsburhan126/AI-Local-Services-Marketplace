import '../../../../auth/data/models/user_model.dart';
import '../../../gigs/data/models/gig_model.dart';

class OrderModel {
  final int id;
  final int userId;
  final int providerId;
  final int? gigId;
  final int? gigPackageId;
  final String status;
  final DateTime? scheduledAt;
  final double totalAmount;
  final String paymentStatus;
  final String? address;
  final String? notes;
  final String? deliveryNote;
  final List<String>? deliveryFiles;
  final UserModel? user;
  final GigModel? gig;
  final GigPackageModel? package;
  final DateTime createdAt;

  OrderModel({
    required this.id,
    required this.userId,
    required this.providerId,
    this.gigId,
    this.gigPackageId,
    required this.status,
    this.scheduledAt,
    required this.totalAmount,
    required this.paymentStatus,
    this.address,
    this.notes,
    this.deliveryNote,
    this.deliveryFiles,
    this.user,
    this.gig,
    this.package,
    required this.createdAt,
  });

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
      id: int.parse(json['id'].toString()),
      userId: int.parse(json['user_id'].toString()),
      providerId: int.parse(json['provider_id'].toString()),
      gigId: int.tryParse(json['gig_id'].toString()),
      gigPackageId: int.tryParse(json['gig_package_id'].toString()),
      status: json['status'] ?? 'pending',
      scheduledAt: json['scheduled_at'] != null
          ? DateTime.tryParse(json['scheduled_at'])
          : null,
      totalAmount: double.tryParse(json['total_amount'].toString()) ?? 0.0,
      paymentStatus: json['payment_status'] ?? 'pending',
      address: json['address'],
      notes: json['notes'],
      deliveryNote: json['delivery_note'],
      deliveryFiles: json['delivery_files'] != null
          ? List<String>.from(json['delivery_files'])
          : null,
      user: json['user'] != null ? UserModel.fromJson(json['user']) : null,
      gig: json['gig'] != null ? GigModel.fromJson(json['gig']) : null,
      package: json['package'] != null
          ? GigPackageModel.fromJson(json['package'])
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : DateTime.now(),
    );
  }
}
