import '../../../auth/data/models/user_model.dart';

class ChatMessageModel {
  final int id;
  final int senderId;
  final int receiverId;
  final String message;
  final String? attachment;
  final String? attachmentType;
  final DateTime? readAt;
  final DateTime? deliveredAt;
  final DateTime createdAt;
  final ChatMessageModel? replyTo;
  final UserModel? sender;
  final String? reaction;
  
  // Local status for optimistic UI
  final String status; // 'sending', 'sent', 'delivered', 'read', 'failed'

  ChatMessageModel({
    required this.id,
    required this.senderId,
    required this.receiverId,
    required this.message,
    this.attachment,
    this.attachmentType,
    this.readAt,
    this.deliveredAt,
    required this.createdAt,
    this.replyTo,
    this.sender,
    this.reaction,
    this.status = 'sent', // Default to sent for fetched messages
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

    String? attachmentType = json['attachment_type'];
    if (attachmentType == null && json['attachment'] != null) {
       // Fallback for old messages or if server didn't send type
       final path = json['attachment'].toString().toLowerCase();
       if (path.endsWith('.jpg') || path.endsWith('.jpeg') || path.endsWith('.png') || path.endsWith('.gif')) {
         attachmentType = 'image';
       } else if (path.endsWith('.mp3') || path.endsWith('.m4a') || path.endsWith('.wav')) {
         attachmentType = 'audio';
       } else if (path.endsWith('.mp4') || path.endsWith('.mov')) {
         attachmentType = 'video';
       } else {
         attachmentType = 'file';
       }
    }

    return ChatMessageModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      senderId: json['sender_id'] is int ? json['sender_id'] : int.tryParse(json['sender_id'].toString()) ?? 0,
      receiverId: json['receiver_id'] is int ? json['receiver_id'] : int.tryParse(json['receiver_id'].toString()) ?? 0,
      message: json['message'] ?? '',
      attachment: json['attachment'],
      attachmentType: attachmentType,
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
      attachmentType: attachmentType,
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
      'attachment_type': attachmentType,
      'read_at': readAt?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
      'reply_to': replyTo?.toJson(),
      'sender': sender?.toJson(),
      'reaction': reaction,
    };
  }
}
