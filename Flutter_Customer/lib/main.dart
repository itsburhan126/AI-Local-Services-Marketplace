import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'config/routes/app_router.dart';
import 'core/theme/app_theme.dart';
import 'features/auth/data/datasources/auth_remote_data_source.dart';
import 'features/auth/data/repositories/auth_repository_impl.dart';
import 'features/auth/presentation/providers/auth_provider.dart';
import 'features/home/presentation/providers/home_provider.dart';
import 'features/freelancer/bookings/presentation/providers/booking_provider.dart';
import 'features/chat/presentation/providers/chat_provider.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'dart:convert';

import 'package:go_router/go_router.dart';

// Top-level for background handling
final FlutterLocalNotificationsPlugin flutterLocalNotificationsPlugin =
    FlutterLocalNotificationsPlugin();

@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  debugPrint("Handling a background message: ${message.messageId}");

  // Show local notification for background messages (especially data-only)
  // If the payload contains 'notification' key, the OS handles it automatically.
  // But if it's data-only (common for calls/custom), we must show it manually.
  
  // Prevent duplicate notifications: if the message has a notification payload, 
  // the system will display it, so we should NOT show a local one.
  if (message.notification != null) {
    return;
  }
  
  if (message.data.isNotEmpty) {
      // Check for chat message (if data-only)
      if (message.data['type'] == 'message' || message.data['type'] == 'chat_message' || message.data['message_id'] != null) {
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

          await flutterLocalNotificationsPlugin.show(
            message.hashCode,
            message.data['sender_name'] ?? message.data['title'] ?? message.data['name'] ?? 'New Message',
            message.data['message'] ?? message.data['body'] ?? message.data['message_body'] ?? 'You have a new message',
            platformChannelSpecifics,
            payload: jsonEncode(message.data), // Pass data as payload
          );
      }
  }
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp();
  
  // Initialize Local Notifications
  const AndroidInitializationSettings initializationSettingsAndroid =
      AndroidInitializationSettings('@mipmap/ic_launcher');

  const InitializationSettings initializationSettings = InitializationSettings(
    android: initializationSettingsAndroid,
  );

  await flutterLocalNotificationsPlugin.initialize(
    initializationSettings,
    onDidReceiveNotificationResponse: (NotificationResponse response) async {
      // Handle notification tap from foreground/background (via local notification)
      debugPrint('Notification clicked with payload: ${response.payload}');
      if (response.payload != null) {
        try {
          final data = jsonDecode(response.payload!);
          _handleNavigation(data);
        } catch (e) {
          debugPrint("Error parsing payload: $e");
        }
      }
    },
  );

  // Create Notification Channels (Android)
  const AndroidNotificationChannel messageChannel = AndroidNotificationChannel(
    'message_channel', // id
    'Messages', // title
    description: 'Chat Message Notifications', // description
    importance: Importance.high,
  );

  final plugin = flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
      AndroidFlutterLocalNotificationsPlugin>();
      
  if (plugin != null) {
      await plugin.createNotificationChannel(messageChannel);
  }

  // Request notification permissions
  final messaging = FirebaseMessaging.instance;
  await messaging.requestPermission(
    alert: true,
    badge: true,
    sound: true,
  );
  
  // Set foreground notification presentation options
  await FirebaseMessaging.instance.setForegroundNotificationPresentationOptions(
    alert: true,
    badge: true,
    sound: true,
  );

  FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);

  final sharedPreferences = await SharedPreferences.getInstance();
  final dio = Dio();

  // Initialize dependencies
  final authRepository = AuthRepositoryImpl(
    remoteDataSource: AuthRemoteDataSourceImpl(dio: dio),
  );
  
  final authProvider = AuthProvider(
    authRepository: authRepository,
    sharedPreferences: sharedPreferences,
  );

  final appRouter = AppRouter(authProvider);

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider.value(value: authProvider),
        ChangeNotifierProvider(create: (_) => HomeProvider()),
        ChangeNotifierProxyProvider<AuthProvider, BookingProvider>(
          create: (_) => BookingProvider(dio),
          update: (_, auth, booking) => booking!..update(auth),
        ),
        ChangeNotifierProvider(create: (_) => ChatProvider()),
      ],
      child: CustomerApp(appRouter: appRouter),
    ),
  );
}

class CustomerApp extends StatefulWidget {
  final AppRouter appRouter;

  const CustomerApp({
    super.key,
    required this.appRouter,
  });

  @override
  State<CustomerApp> createState() => _CustomerAppState();
}

class _CustomerAppState extends State<CustomerApp> {
  @override
  void initState() {
    super.initState();
    setupInteractedMessage();
    
    // Listen for foreground messages
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      debugPrint("Got a message whilst in the foreground!");
      debugPrint('Message data: ${message.data}');

      // Handle Notification (Notification Payload or Data Payload)
      RemoteNotification? notification = message.notification;
      
      // If notification payload exists
      if (notification != null) {
        flutterLocalNotificationsPlugin.show(
            notification.hashCode,
            notification.title,
            notification.body,
            const NotificationDetails(
              android: AndroidNotificationDetails(
                'message_channel',
                'Messages',
                channelDescription: 'Chat Message Notifications',
                importance: Importance.high,
                priority: Priority.high,
              ),
            ),
            payload: jsonEncode(message.data),
        );
      } 
      // If data-only message (e.g. chat message)
      else if (message.data.isNotEmpty && (message.data['type'] == 'message' || message.data['message_id'] != null)) {
         flutterLocalNotificationsPlugin.show(
            message.hashCode,
            message.data['sender_name'] ?? 'New Message',
            message.data['message_body'] ?? 'You have a new message',
            const NotificationDetails(
              android: AndroidNotificationDetails(
                'message_channel',
                'Messages',
                channelDescription: 'Chat Message Notifications',
                importance: Importance.high,
                priority: Priority.high,
              ),
            ),
            payload: jsonEncode(message.data),
         );
      }
    });
  }

  Future<void> setupInteractedMessage() async {
    // Get any messages which caused the application to open from
    // a terminated state.
    RemoteMessage? initialMessage =
        await FirebaseMessaging.instance.getInitialMessage();

    if (initialMessage != null) {
      _handleMessage(initialMessage);
    }
    
    // Check if app was launched by a Local Notification tap
    final NotificationAppLaunchDetails? notificationAppLaunchDetails =
        await flutterLocalNotificationsPlugin.getNotificationAppLaunchDetails();
        
    if (notificationAppLaunchDetails?.didNotificationLaunchApp ?? false) {
       final payload = notificationAppLaunchDetails!.notificationResponse?.payload;
       if (payload != null) {
         try {
           final data = jsonDecode(payload);
           
           if (data['type'] != 'incoming_call' && data['channel_name'] == null) {
             _handleNavigation(data);
           }
         } catch (e) {
           debugPrint("Error parsing launch payload: $e");
         }
       }
    }

    // Also handle any interaction when the app is in the background via a
    // Stream listener
    FirebaseMessaging.onMessageOpenedApp.listen(_handleMessage);
  }

  void _handleMessage(RemoteMessage message) {
    debugPrint("Handling background message interaction: ${message.data}");
    
    // Check if it is a call notification
    if (message.data['type'] == 'incoming_call' || message.data['channel_name'] != null) {
       // Call functionality removed
    } else {
       _handleNavigation(message.data);
    }
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      title: 'AI Local Services',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      themeMode: ThemeMode.system,
      routerConfig: widget.appRouter.router,
    );
  }
}

void _handleNavigation(Map<String, dynamic> data) {
  debugPrint("Attempting navigation with data: $data");
  if (data['type'] == 'chat_message' || data['type'] == 'message') {
     final senderId = data['sender_id'];
     final senderName = data['sender_name'] ?? data['title'] ?? 'User';
     
     if (senderId != null) {
       // Use a slight delay to ensure the app is ready if called from initial state
       Future.delayed(const Duration(milliseconds: 500), () {
          final context = AppRouter.navigatorKey.currentContext;
          if (context != null) {
            GoRouter.of(context).push('/chat-details', extra: {
              'id': senderId,
              'name': senderName,
              'image': null,
            });
          } else {
            debugPrint("Navigation context is null");
          }
       });
     }
  }
}
