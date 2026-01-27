import 'package:flutter/material.dart';
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../auth/data/models/user_model.dart';
import '../../data/chat_service.dart';
import '../../data/models/chat_conversation_model.dart';
import '../../data/models/chat_message_model.dart';
import 'dart:convert';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
// import 'call_provider.dart';

import 'package:dio/dio.dart';
import '../../../../core/constants/api_constants.dart';

class ChatProvider extends ChangeNotifier {
  final ChatService _chatService = ChatService(Dio());
  
  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }
  
  final FlutterLocalNotificationsPlugin _flutterLocalNotificationsPlugin = FlutterLocalNotificationsPlugin();
  
  List<ChatConversationModel> _conversations = [];
  List<ChatMessageModel> _currentMessages = [];
  bool _isLoading = false;
  bool _isSending = false;
  String? _error;
  
  // Pusher
  PusherChannelsFlutter _pusher = PusherChannelsFlutter.getInstance();
  bool _isConnected = false;
  int? _currentChatUserId;
  UserModel? _currentChatUser;
  int? _subscribedUserId;
  int _maxFileSize = 10485760; // 10MB default
  List<String> _allowedExtensions = [];
  bool _isOtherUserTyping = false;
  ChatMessageModel? _replyToMessage;
  // CallProvider? _callProvider;

  List<ChatConversationModel> get conversations => _conversations;
  List<ChatMessageModel> get currentMessages => _currentMessages;
  UserModel? get currentChatUser => _currentChatUser;
  bool get isLoading => _isLoading;
  bool get isSending => _isSending;
  String? get error => _error;
  bool get isConnected => _isConnected;
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
       final token = await _getToken();
       await _chatService.sendTyping(token, receiverId);
    }
  }

  // Initialize Pusher
  Future<void> initPusher(int userId) async {
    if (_isConnected && _subscribedUserId == userId) return;

    try {
      final token = await _getToken();
      final config = await _chatService.getChatConfig(token);
      final key = config['key'] as String?;
      final cluster = config['cluster'] as String?;
      _maxFileSize = config['max_file_size'] as int? ?? 10485760;

      if (key == null || key.isEmpty) {
        debugPrint("Pusher Configuration Missing");
        return;
      }

      final effectiveCluster = (cluster != null && cluster.isNotEmpty) ? cluster : 'mt1';

      await _pusher.init(
        apiKey: key,
        cluster: effectiveCluster,
        onConnectionStateChange: (change, connectionState) {
          debugPrint("Pusher Connection State: $connectionState");
          _isConnected = connectionState == "CONNECTED";
          notifyListeners();
        },
        onError: (message, code, error) {
          debugPrint("Pusher Error: $message");
        },
        onEvent: (event) {
          if (event.eventName == 'client-typing') {
             try {
               final data = jsonDecode(event.data);
               // Handle both direct integer or string representation
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
             _handlePusherEvent(event.eventName, eventData);
          }
        },
        onAuthorizer: (channelName, socketId, options) async {
           final prefs = await SharedPreferences.getInstance();
           final token = prefs.getString('auth_token');
           
           final dio = Dio();
           final response = await dio.post(
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

      // Subscribe to private channels
      await _pusher.subscribe(channelName: "private-chat.$userId"); 
      // await _pusher.subscribe(channelName: "private-call.$userId");
      await _pusher.connect();
      _subscribedUserId = userId;
    } catch (e) {
      debugPrint("Pusher Init Error: $e");
    }
  }

  Future<void> _handlePusherEvent(String eventName, dynamic data) async {
    debugPrint("Pusher Event: $eventName data: $data");
    
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

      if (eventName == "message.sent" ||
          eventName == "App\\Events\\MessageSent" ||
          eventName == "MessageSent") {
        
        final messageData = data['message'] ?? data; 
        final newMessage = ChatMessageModel.fromJson(messageData);
        
        // If we are currently chatting with the user involved in this message
        if (_currentChatUserId != null) {
          if (newMessage.senderId == _currentChatUserId || newMessage.receiverId == _currentChatUserId) {
             final exists = _currentMessages.any((m) => m.id == newMessage.id);
             if (!exists) {
                _currentMessages.insert(0, newMessage);
                notifyListeners();
                
                // If I am the receiver
                 if (newMessage.receiverId == _subscribedUserId) {
                    // Mark as delivered immediately since we received it via Pusher
                    final token = await _getToken();
                    _chatService.markMessageAsDelivered(token, newMessage.id);
                    
                    // If I am also looking at this specific chat, mark as read
                    if (newMessage.senderId == _currentChatUserId) {
                       markAsRead(newMessage.id);
                    }
                 }
             }
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
    // Don't set loading to true for background refresh to avoid UI flicker
    if (_conversations.isEmpty) {
      _isLoading = true;
      notifyListeners();
    }
    _error = null;

    try {
      final token = await _getToken();
      _conversations = await _chatService.getConversations(token);
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> loadMessages(int userId) async {
    _currentChatUserId = userId;
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final token = await _getToken();
      final data = await _chatService.getMessages(token, userId);
      _currentMessages = data['messages'] as List<ChatMessageModel>;
    } catch (e) {
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> sendMessage(int receiverId, String content, int senderId, {String? attachmentPath, int? replyToId}) async {
    _isSending = true;
    
    // Optimistic Update
    final tempId = DateTime.now().millisecondsSinceEpoch;
    String? attachmentType;
    if (attachmentPath != null) {
       final path = attachmentPath.toLowerCase();
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

    final tempMessage = ChatMessageModel(
      id: tempId,
      senderId: senderId,
      receiverId: receiverId,
      message: content,
      createdAt: DateTime.now(),
      attachment: attachmentPath,
      attachmentType: attachmentType,
    );
    
    _currentMessages.insert(0, tempMessage);
    notifyListeners();

    try {
      final token = await _getToken();
      final message = await _chatService.sendMessage(
        token: token,
        receiverId: receiverId,
        message: content,
        attachmentPath: attachmentPath,
        replyToId: replyToId,
      );
      
      // Replace temp message
      final index = _currentMessages.indexWhere((m) => m.id == tempId);
      if (index != -1) {
        _currentMessages[index] = message;
      } else {
        _currentMessages.insert(0, message);
      }
      notifyListeners();
      
      loadConversations(); // Refresh list
    } catch (e) {
      _error = e.toString();
      _currentMessages.removeWhere((m) => m.id == tempId);
      notifyListeners();
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
      
      final token = await _getToken();
      await _chatService.markMessageAsRead(token, messageId);
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
      
      final token = await _getToken();
      await _chatService.reactToMessage(token, messageId, reaction);
    }
  }
  
  void clearCurrentChat() {
    _currentChatUserId = null;
    _currentChatUser = null;
    _currentMessages = [];
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
        'sender_avatar': message.sender?.profileImage,
      }),
    );
  }

  Future<void> blockUser(int userId) async {
    try {
      final token = await _getToken();
      await _chatService.blockUser(token, userId);
      notifyListeners();
    } catch (e) {
      debugPrint("Error blocking user: $e");
      rethrow;
    }
  }

  Future<void> reportUser(int userId, String reason) async {
    try {
      final token = await _getToken();
      await _chatService.reportUser(token, userId, reason);
    } catch (e) {
      debugPrint("Error reporting user: $e");
      rethrow;
    }
  }

  Future<void> clearChat(int userId) async {
    try {
      final token = await _getToken();
      await _chatService.clearChat(token, userId);
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
