import 'package:dio/dio.dart';
import '../../../core/constants/api_constants.dart';
import '../../auth/data/models/user_model.dart';
import 'models/chat_conversation_model.dart';
import 'models/chat_message_model.dart';

class ChatService {
  final Dio _dio;

  ChatService(this._dio);

  Future<List<ChatConversationModel>> getConversations(String? token) async {
    try {
      final response = await _dio.get(
        '${ApiConstants.baseUrl}${ApiConstants.chatConversationsEndpoint}',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200) {
        final data = response.data['data'] as List;
        return data.map((e) => ChatConversationModel.fromJson(e)).toList();
      }
      return [];
    } catch (e) {
      throw Exception('Failed to load conversations: $e');
    }
  }

  Future<Map<String, dynamic>> getMessages(String? token, int userId) async {
    try {
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

  Future<Map<String, dynamic>> getChatConfig(String? token) async {
    try {
      final response = await _dio.get(
        '${ApiConstants.baseUrl}/api/chat/config',
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );

      if (response.statusCode == 200) {
        return {
          'key': response.data['pusher_app_key']?.toString() ?? '',
          'cluster': response.data['pusher_app_cluster']?.toString() ?? '',
          'max_file_size': response.data['max_file_size'] ?? 10485760,
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
    required String? token,
    required int receiverId,
    String? message,
    String? attachmentPath,
    int? replyToId,
  }) async {
    try {
      FormData formData = FormData.fromMap({
        'receiver_id': receiverId,
        'message': message,
        'reply_to_id': replyToId,
      });

      if (attachmentPath != null) {
        formData.files.add(MapEntry(
          'attachment',
          await MultipartFile.fromFile(attachmentPath),
        ));
      }

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

  Future<void> markMessageAsRead(String? token, int messageId) async {
    try {
      await _dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.chatReadEndpoint}',
        data: {'message_id': messageId},
        options: Options(headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        }),
      );
    } catch (e) {
      // Ignore errors for read receipts to avoid disrupting UX
      print('Failed to mark message as read: $e');
    }
  }

  Future<void> reactToMessage(String? token, int messageId, String reaction) async {
    try {
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

  Future<void> sendTyping(String? token, int receiverId) async {
    try {
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

  Future<void> markMessageAsDelivered(String? token, int messageId) async {
    try {
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

  Future<void> blockUser(String? token, int userId) async {
    try {
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
}
