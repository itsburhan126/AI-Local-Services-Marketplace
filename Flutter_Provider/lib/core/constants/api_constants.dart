class ApiConstants {
  static const String baseUrl = 'http://192.168.1.120:8000';
  static const String loginEndpoint = '/api/login';
  static const String registerEndpoint = '/api/register';
  static const String userEndpoint = '/api/user';
  
  // Chat
  static const String chatConversationsEndpoint = '/api/chat/conversations';
  static const String chatMessagesEndpoint = '/api/chat/messages';
  static const String chatConfigEndpoint = '/api/chat/config';
  static const String chatSendEndpoint = '/api/chat/send';
  static const String chatTypingEndpoint = '/api/chat/typing';
  static const String chatDeliveredEndpoint = '/api/chat/delivered';
  static const String chatReadEndpoint = '/api/chat/read';
  static const String blockUserEndpoint = '/api/chat/block';
  static const String reportUserEndpoint = '/api/chat/report';
  static const String clearChatEndpoint = '/api/chat/clear';

  // Call
  static const String callConfigEndpoint = '/api/call/config';
  static const String callInitiateEndpoint = '/api/call/initiate';
  static const String callAcceptEndpoint = '/api/call/accept';
  static const String callRejectEndpoint = '/api/call/reject';
  static const String callEndEndpoint = '/api/call/end';
}
