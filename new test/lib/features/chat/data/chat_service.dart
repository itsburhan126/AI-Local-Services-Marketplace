import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';
import '../../auth/data/models/user_model.dart';
import 'models/chat_conversation_model.dart';
import 'models/chat_message_model.dart';

class ChatService {
  final Dio _dio = Dio();

  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  Future<List<ChatConversationModel>> getConversations() async {
    try {
      final token = await _getToken();
      final response = await _dio.get(
        '${ApiConstants.baseUrl}${ApiConstants.chatConversationsEndpoint}',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200) {
        final List<dynamic> data = (response.data['data'] as List?) ?? const [];
        return data.map((e) => ChatConversationModel.fromJson(e)).toList();
      }
      throw Exception('Failed to load conversations');
    } catch (e) {
      throw Exception('Failed to load conversations: $e');
    }
  }

  Future<Map<String, dynamic>> getMessages(int userId) async {
    try {
      final token = await _getToken();
      final response = await _dio.get(
        '${ApiConstants.baseUrl}${ApiConstants.chatMessagesEndpoint}/$userId',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200) {
        final userJson = response.data['user'];
        final messagesJson = response.data['messages'] as List;

        return {
          'user': UserModel.fromJson(userJson),
          'messages': messagesJson.map((e) => ChatMessageModel.fromJson(e)).toList(),
        };
      }
      throw Exception('Failed to load messages');
    } catch (e) {
      throw Exception('Failed to load messages: $e');
    }
  }

  Future<Map<String, dynamic>> getChatConfig() async {
    try {
      final token = await _getToken();
      final response = await _dio.get(
        '${ApiConstants.baseUrl}${ApiConstants.chatConfigEndpoint}',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200) {
        return {
          'key': response.data['pusher_app_key']?.toString() ?? '',
          'cluster': response.data['pusher_app_cluster']?.toString() ?? '',
          'max_file_size': response.data['max_file_size'] ?? 10485760, // Default 10MB
          'allowed_extensions': response.data['allowed_extensions'] ?? ['jpg', 'png', 'jpeg', 'mp4', 'pdf', 'doc', 'docx', 'mp3'],
        };
      }
      return {'key': '', 'cluster': '', 'max_file_size': 10485760};
    } catch (e) {
      print('Failed to get chat config: $e');
      return {'key': '', 'cluster': '', 'max_file_size': 10485760};
    }
  }

  Future<ChatMessageModel> sendMessage({
    required int receiverId,
    String? message,
    String? attachmentPath,
    int? replyToId,
  }) async {
    try {
      final token = await _getToken();
      
      final Map<String, dynamic> map = {
        'receiver_id': receiverId,
        'message': message,
      };
      
      if (replyToId != null) {
        map['reply_to_id'] = replyToId;
      }

      if (attachmentPath != null) {
        map['attachment'] = await MultipartFile.fromFile(attachmentPath);
      }

      FormData formData = FormData.fromMap(map);

      final response = await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.chatSendEndpoint}',
        data: formData,
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final payload = response.data;
        final messageJson = payload is Map && payload['message'] != null ? payload['message'] : payload;
        return ChatMessageModel.fromJson(messageJson);
      }
      throw Exception('Failed to send message');
    } catch (e) {
      throw Exception('Failed to send message: $e');
    }
  }

  Future<void> markMessageAsRead(int messageId) async {
    try {
      final token = await _getToken();
      await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.chatReadEndpoint}',
        data: {'message_id': messageId},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      // Ignore errors for read receipts
      print('Failed to mark message as read: $e');
    }
  }

  Future<void> reactToMessage(int messageId, String reaction) async {
    try {
      final token = await _getToken();
      await _dio.post(
        '${ApiConstants.baseUrl}/api/chat/messages/$messageId/react',
        data: {'reaction': reaction},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      throw Exception('Failed to react to message: $e');
    }
  }

  Future<void> sendTyping(int receiverId) async {
    try {
      final token = await _getToken();
      await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.chatTypingEndpoint}',
        data: {'receiver_id': receiverId},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      print('Failed to send typing event: $e');
    }
  }

  Future<void> markMessageAsDelivered(int messageId) async {
    try {
      final token = await _getToken();
      await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.chatDeliveredEndpoint}',
        data: {'message_id': messageId},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      print('Failed to mark message as delivered: $e');
    }
  }

  Future<void> blockUser(int userId) async {
    try {
      final token = await _getToken();
      await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.blockUserEndpoint}',
        data: {'user_id': userId},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      throw Exception('Failed to block user: $e');
    }
  }

  Future<void> reportUser(int userId, String reason) async {
    try {
      final token = await _getToken();
      await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.reportUserEndpoint}',
        data: {'user_id': userId, 'reason': reason},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      throw Exception('Failed to report user: $e');
    }
  }

  Future<void> clearChat(int userId) async {
    try {
      final token = await _getToken();
      await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.clearChatEndpoint}',
        data: {'user_id': userId},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      throw Exception('Failed to clear chat: $e');
    }
  }
}
