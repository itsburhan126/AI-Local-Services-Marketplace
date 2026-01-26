import 'package:flutter/material.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../../../../core/constants/api_constants.dart';
import '../../../auth/data/models/user_model.dart';
import '../../data/chat_service.dart';
import '../../data/models/chat_conversation_model.dart';
import '../../data/models/chat_message_model.dart';
import 'package:flutter_provider/core/utils/event_bus.dart';
// import 'call_provider.dart';

class ChatProvider extends ChangeNotifier {
  final ChatService _chatService = ChatService();
  final FlutterLocalNotificationsPlugin _flutterLocalNotificationsPlugin = FlutterLocalNotificationsPlugin();
  final PusherChannelsFlutter _pusher = PusherChannelsFlutter.getInstance();
  final Dio _dio = Dio();
  
  bool _isLoading = false;
  bool _isSending = false;
  bool _isConnected = false;
  List<ChatConversationModel> _conversations = [];
  List<ChatMessageModel> _currentMessages = [];
  UserModel? _currentChatUser;
  int? _currentChatUserId;
  String? _error;
  int _maxFileSize = 10485760; // 10MB default
  List<String> _allowedExtensions = [];
  bool _isOtherUserTyping = false;
  ChatMessageModel? _replyToMessage;
  int? _subscribedUserId;
  // CallProvider? _callProvider;

  bool get isLoading => _isLoading;
  bool get isSending => _isSending;
  bool get isConnected => _isConnected;
  List<ChatConversationModel> get conversations => _conversations;
  List<ChatMessageModel> get currentMessages => _currentMessages;
  UserModel? get currentChatUser => _currentChatUser;
  String? get error => _error;
  int get maxFileSize => _maxFileSize;
  List<String> get allowedExtensions => _allowedExtensions;
  bool get isOtherUserTyping => _isOtherUserTyping;
  ChatMessageModel? get replyToMessage => _replyToMessage;

  // void setCallProvider(CallProvider provider) {
  //   _callProvider = provider;
  // }

  void setReplyTo(ChatMessageModel? message) {
    _replyToMessage = message;
    notifyListeners();
  }

  Future<void> sendTypingEvent(int receiverId) async {
    if (_isConnected) {
       await _chatService.sendTyping(receiverId);
    }
  }

  Future<void> initPusher(int userId) async {
    try {
      final config = await _chatService.getChatConfig();
      final key = config['key'] as String?;
      final cluster = config['cluster'] as String?;
      _maxFileSize = config['max_file_size'] as int? ?? 10485760;

      if (key == null || key.isEmpty) {
        debugPrint("Pusher key is missing");
        return;
      }

      await _pusher.init(
        apiKey: key,
        cluster: (cluster != null && cluster.isNotEmpty) ? cluster : 'mt1',
        onConnectionStateChange: (change, connectionState) {
          _isConnected = connectionState == "CONNECTED";
          debugPrint("Pusher Connection State: $connectionState");
          notifyListeners();
        },
        onError: (message, code, error) {
          debugPrint("Pusher Error: $message code: $code error: $error");
        },
        onSubscriptionSucceeded: (channelName, data) {
          debugPrint("Subscribed to $channelName");
        },
        onEvent: (event) {
          debugPrint("Pusher Event: ${event.eventName} Data: ${event.data}");
          
          // Emit to global bus
          GlobalEventBus.emitPusherEvent(event);

          if (event.eventName == 'client-typing') {
             try {
               final data = jsonDecode(event.data);
               final senderId = int.tryParse(data['sender_id'].toString());
               
               if (_currentChatUserId != null && senderId == _currentChatUserId) {
                 _isOtherUserTyping = true;
                 notifyListeners();
                 Future.delayed(const Duration(seconds: 3), () {
                   _isOtherUserTyping = false;
                   notifyListeners();
                 });
               }
             } catch (e) {
               debugPrint("Error parsing typing event: $e");
             }
          } else {
             dynamic eventData = event.data;
             try {
                if (event.data is String) {
                   eventData = jsonDecode(event.data);
                }
             } catch (e) {
                debugPrint("Error decoding event data: $e");
             }
             _handleNewMessage(event.eventName, eventData);
          }
        },
        onAuthorizer: (channelName, socketId, options) async {
          final prefs = await SharedPreferences.getInstance();
          final token = prefs.getString('auth_token');
          final response = await _dio.post(
            '${ApiConstants.baseUrl}/api/broadcasting/auth',
            data: {
              'socket_id': socketId,
              'channel_name': channelName,
            },
            options: Options(
              headers: {
                'Authorization': 'Bearer $token',
                'Accept': 'application/json',
              }
            ),
          );
          return response.data;
        },
      );

      await _pusher.subscribe(channelName: "private-chat.$userId");
      await _pusher.subscribe(channelName: "private-provider.$userId");
      await _pusher.connect();
      _subscribedUserId = userId;
    } catch (e) {
      debugPrint("Pusher Init Error: $e");
    }
  }

  void _handleNewMessage(String eventName, dynamic data) {
    debugPrint("Pusher Event: $eventName Data: $data");
    
    try {
      // Call Events
      // if (eventName == 'incoming.call' || eventName == '.incoming.call') {
      //    _callProvider?.handleIncomingCall(data);
      //    return;
      // }
      // if (eventName == 'call.accepted' || eventName == '.call.accepted') {
      //    _callProvider?.handleCallAccepted(data);
      //    return;
      // }
      // if (eventName == 'call.rejected' || eventName == '.call.rejected') {
      //    _callProvider?.handleCallRejected(data);
      //    return;
      // }
      // if (eventName == 'call.ended' || eventName == '.call.ended') {
      //    _callProvider?.handleCallEnded();
      //    return;
      // }
      
      if (eventName == 'message.sent' || 
          eventName == "App\\Events\\MessageSent" ||
          eventName == "MessageSent") {
        
         final messageData = data['message'] ?? data;
         final newMessage = ChatMessageModel.fromJson(messageData);

         if (_currentChatUserId != null) {
            if (newMessage.senderId == _currentChatUserId || newMessage.receiverId == _currentChatUserId) {
               final exists = _currentMessages.any((m) => m.id == newMessage.id);
               if (!exists) {
                  _currentMessages.insert(0, newMessage);
                  notifyListeners();
                  
                  // If I am the receiver
                  if (newMessage.receiverId == _subscribedUserId) {
                     // Mark as delivered immediately since we received it via Pusher
                     _chatService.markMessageAsDelivered(newMessage.id);
                     
                     // If I am also looking at this specific chat, mark as read
                     if (newMessage.senderId == _currentChatUserId) {
                        markAsRead(newMessage.id);
                     }
                  }
               }
            } else if (newMessage.receiverId == _subscribedUserId) {
               // User is in a different chat, show notification
               _showNotification(newMessage);
            }
         } else {
            // User is not in any chat, show notification if I am the receiver
            if (newMessage.receiverId == _subscribedUserId) {
               _showNotification(newMessage);
            }
         }
         
         loadConversations();
         
      } else if (eventName == "message.delivered" || 
                 eventName == "App\\Events\\MessageDelivered" ||
                 eventName == "MessageDelivered") {
         
         final messageId = data['id'];
         final deliveredAt = data['delivered_at'];
         
         final index = _currentMessages.indexWhere((m) => m.id == messageId);
         if (index != -1) {
           _currentMessages[index] = _currentMessages[index].copyWith(
             status: 'delivered',
             deliveredAt: DateTime.tryParse(deliveredAt),
           );
           notifyListeners();
         }
         
      } else if (eventName == "message.read" || 
                 eventName == "App\\Events\\MessageRead" ||
                 eventName == "MessageRead") {
         
         final messageId = data['id'];
         final readAt = data['read_at'];
         
         final index = _currentMessages.indexWhere((m) => m.id == messageId);
         if (index != -1) {
           _currentMessages[index] = _currentMessages[index].copyWith(
             status: 'read',
             readAt: DateTime.tryParse(readAt),
           );
           notifyListeners();
         }
      }
    } catch (e) {
      debugPrint("Error parsing Pusher event: $e");
    }
  }

  Future<void> loadConversations() async {
    if (_conversations.isEmpty) {
      _isLoading = true;
      notifyListeners();
    }
    try {
      _conversations = await _chatService.getConversations();
    } catch (e) {
      debugPrint('Error loading conversations: $e');
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> loadMessages(int userId) async {
    _currentChatUserId = userId;
    _isLoading = true;
    notifyListeners();
    try {
      final data = await _chatService.getMessages(userId);
      _currentChatUser = data['user'] as UserModel;
      _currentMessages = data['messages'] as List<ChatMessageModel>;
    } catch (e) {
      debugPrint('Error loading messages: $e');
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> sendMessage(int receiverId, String message, int senderId, {String? attachmentPath, int? replyToId}) async {
    _isSending = true;
    
    // Optimistic Update: Create a temporary message
    final tempId = DateTime.now().millisecondsSinceEpoch; // Use a large number or negative
    final tempMessage = ChatMessageModel(
      id: tempId, 
      senderId: senderId,
      receiverId: receiverId,
      message: message,
      createdAt: DateTime.now(),
      attachment: attachmentPath,
      // replyTo: replyToId != null ? ... : null, // If we had the message object
    );
    
    _currentMessages.insert(0, tempMessage);
    notifyListeners();

    try {
      final sentMessage = await _chatService.sendMessage(
        receiverId: receiverId,
        message: message,
        attachmentPath: attachmentPath,
        replyToId: replyToId,
      );
      
      // Replace temp message with real one
      final index = _currentMessages.indexWhere((m) => m.id == tempId);
      if (index != -1) {
        _currentMessages[index] = sentMessage;
      } else {
         // Should not happen, but safe fallback
        _currentMessages.insert(0, sentMessage);
      }
      notifyListeners();
      
      loadConversations();
    } catch (e) {
      debugPrint('Error sending message: $e');
      _error = e.toString();
      
      // Remove temp message on failure or mark as failed
      _currentMessages.removeWhere((m) => m.id == tempId);
      notifyListeners();
      
      rethrow;
    } finally {
      _isSending = false;
      notifyListeners();
    }
  }
  
  Future<void> markAsRead(int messageId) async {
    // Optimistic update
    final index = _currentMessages.indexWhere((m) => m.id == messageId);
    if (index != -1 && _currentMessages[index].readAt == null) {
      // Create a new copy with readAt set
      final updatedMessage = ChatMessageModel(
        id: _currentMessages[index].id,
        senderId: _currentMessages[index].senderId,
        receiverId: _currentMessages[index].receiverId,
        message: _currentMessages[index].message,
        createdAt: _currentMessages[index].createdAt,
        attachment: _currentMessages[index].attachment,
        replyTo: _currentMessages[index].replyTo,
        sender: _currentMessages[index].sender,
        reaction: _currentMessages[index].reaction,
        readAt: DateTime.now(),
      );
      _currentMessages[index] = updatedMessage;
      notifyListeners();
      
      await _chatService.markMessageAsRead(messageId);
    }
  }

  Future<void> reactToMessage(int messageId, String reaction) async {
    // Optimistic update
    final index = _currentMessages.indexWhere((m) => m.id == messageId);
    if (index != -1) {
      final updatedMessage = ChatMessageModel(
        id: _currentMessages[index].id,
        senderId: _currentMessages[index].senderId,
        receiverId: _currentMessages[index].receiverId,
        message: _currentMessages[index].message,
        createdAt: _currentMessages[index].createdAt,
        attachment: _currentMessages[index].attachment,
        replyTo: _currentMessages[index].replyTo,
        sender: _currentMessages[index].sender,
        reaction: reaction,
        readAt: _currentMessages[index].readAt,
      );
      _currentMessages[index] = updatedMessage;
      notifyListeners();
      
      await _chatService.reactToMessage(messageId, reaction);
    }
  }
  
  void clearCurrentChat() {
    _currentChatUserId = null;
    _currentMessages = [];
    _currentChatUser = null;
    notifyListeners();
  }

  Future<void> _showNotification(ChatMessageModel message) async {
    const AndroidNotificationDetails androidPlatformChannelSpecifics =
        AndroidNotificationDetails(
      'message_channel',
      'Messages',
      channelDescription: 'Chat Message Notifications',
      importance: Importance.high,
      priority: Priority.high,
    );
    const NotificationDetails platformChannelSpecifics =
        NotificationDetails(android: androidPlatformChannelSpecifics);
        
    await _flutterLocalNotificationsPlugin.show(
      message.id,
      message.sender?.name ?? 'New Message',
      message.message ?? 'You received a new message',
      platformChannelSpecifics,
      payload: jsonEncode({
        'type': 'message',
        'sender_id': message.senderId,
        'sender_name': message.sender?.name,
        'sender_avatar': message.sender?.profilePhotoUrl,
      }),
    );
  }

  Future<void> blockUser(int userId) async {
    try {
      await _chatService.blockUser(userId);
      notifyListeners();
    } catch (e) {
      debugPrint("Error blocking user: $e");
      rethrow;
    }
  }

  Future<void> reportUser(int userId, String reason) async {
    try {
      await _chatService.reportUser(userId, reason);
    } catch (e) {
      debugPrint("Error reporting user: $e");
      rethrow;
    }
  }

  Future<void> clearChat(int userId) async {
    try {
      await _chatService.clearChat(userId);
      _currentMessages.clear();
      notifyListeners();
    } catch (e) {
      debugPrint("Error clearing chat: $e");
      rethrow;
    }
  }

  @override
  void dispose() {
    _pusher.disconnect();
    super.dispose();
  }
}
