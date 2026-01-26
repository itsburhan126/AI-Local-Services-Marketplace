class ApiConstants {
  // Use 10.0.2.2 for Android Emulator, but for physical device use local IP
  // IPv4 Address from ipconfig: 192.168.1.120
  static const String baseUrl = 'http://192.168.1.120:8000';
  static const String loginEndpoint = '/api/login';
  static const String registerEndpoint = '/api/register';
  static const String userEndpoint = '/api/user';
  static const String updateServiceRuleEndpoint = '/api/user/service-rule';
  static const String categoriesEndpoint = '/api/categories';
  static const String servicesEndpoint = '/api/services';
  static const String bannersEndpoint = '/api/banners';
  // Deprecated bookingsEndpoint
  static const String bookingsEndpoint = '/api/bookings';
  static const String ordersEndpoint = '/api/freelancer/customer/orders';
  
  // Chat Endpoints
  static const String chatConversationsEndpoint = '/api/chat/conversations';
  static const String chatMessagesEndpoint = '/api/chat/messages'; // Append /{id}
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
