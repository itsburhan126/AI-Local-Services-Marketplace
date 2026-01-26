import '../../../auth/data/models/user_model.dart';

class ChatConversationModel {
  final UserModel user;
  final int unreadCount;
  final DateTime? lastMessageAt;
  final String? lastMessageContent;

  ChatConversationModel({
    required this.user,
    this.unreadCount = 0,
    this.lastMessageAt,
    this.lastMessageContent,
  });

  factory ChatConversationModel.fromJson(Map<String, dynamic> json) {
    return ChatConversationModel(
      user: UserModel.fromJson(json),
      unreadCount: int.tryParse((json['unread_count'] ?? 0).toString()) ?? 0,
      lastMessageAt: json['last_message_at'] != null
          ? DateTime.tryParse(json['last_message_at'].toString())
          : null,
      lastMessageContent: json['last_message_content']?.toString(),
    );
  }
}
