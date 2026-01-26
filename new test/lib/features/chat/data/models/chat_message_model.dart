import '../../../auth/data/models/user_model.dart';

class ChatMessageModel {
  final int id;
  final int senderId;
  final int receiverId;
  final String message;
  final String? attachment;
  final DateTime? readAt;
  final DateTime? deliveredAt;
  final DateTime createdAt;
  final ChatMessageModel? replyTo;
  final UserModel? sender;
  final String? reaction;
  
  final String status; // 'sending', 'sent', 'delivered', 'read', 'failed'

  ChatMessageModel({
    required this.id,
    required this.senderId,
    required this.receiverId,
    required this.message,
    this.attachment,
    this.readAt,
    this.deliveredAt,
    required this.createdAt,
    this.replyTo,
    this.sender,
    this.reaction,
    this.status = 'sent',
  });

  factory ChatMessageModel.fromJson(Map<String, dynamic> json) {
    final readAt = json['read_at'] != null ? DateTime.tryParse(json['read_at'].toString()) : null;
    final deliveredAt = json['delivered_at'] != null ? DateTime.tryParse(json['delivered_at'].toString()) : null;
    
    String status = 'sent';
    if (readAt != null) {
      status = 'read';
    } else if (deliveredAt != null) {
      status = 'delivered';
    }

    return ChatMessageModel(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      senderId: json['sender_id'] is int ? json['sender_id'] : int.parse(json['sender_id'].toString()),
      receiverId: json['receiver_id'] is int ? json['receiver_id'] : int.parse(json['receiver_id'].toString()),
      message: json['message'] ?? '',
      attachment: json['attachment'],
      readAt: readAt,
      deliveredAt: deliveredAt,
      createdAt: DateTime.parse(json['created_at']),
      replyTo: json['reply_to'] != null ? ChatMessageModel.fromJson(json['reply_to']) : null,
      sender: json['sender'] != null ? UserModel.fromJson(json['sender']) : null,
      reaction: json['reaction'],
      status: status,
    );
  }

  ChatMessageModel copyWith({
    int? id,
    String? status,
    DateTime? deliveredAt,
    DateTime? readAt,
  }) {
    return ChatMessageModel(
      id: id ?? this.id,
      senderId: senderId,
      receiverId: receiverId,
      message: message,
      attachment: attachment,
      readAt: readAt ?? this.readAt,
      deliveredAt: deliveredAt ?? this.deliveredAt,
      createdAt: createdAt,
      replyTo: replyTo,
      sender: sender,
      reaction: reaction,
      status: status ?? this.status,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'sender_id': senderId,
      'receiver_id': receiverId,
      'message': message,
      'attachment': attachment,
      'read_at': readAt?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
      'reply_to': replyTo?.toJson(),
      'sender': sender?.toJson(),
      'reaction': reaction,
    };
  }
}
