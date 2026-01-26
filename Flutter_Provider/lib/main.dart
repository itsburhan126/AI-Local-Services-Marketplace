import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart' as riverpod;
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'config/routes/provider_routes.dart';
import 'core/theme/provider_theme.dart';
import 'core/constants/api_constants.dart';
import 'features/auth/data/datasources/auth_remote_data_source.dart';
import 'features/auth/data/repositories/auth_repository_impl.dart';
import 'features/auth/domain/repositories/auth_repository.dart';
import 'features/auth/presentation/providers/auth_provider.dart';
import 'features/services/data/datasources/service_remote_data_source.dart';
import 'features/services/data/repositories/service_repository_impl.dart';
import 'features/services/domain/repositories/service_repository.dart';
import 'features/services/presentation/providers/service_provider.dart';
import 'features/freelancer/gigs/presentation/providers/gig_provider.dart';
import 'features/chat/presentation/providers/chat_provider.dart';
import 'features/freelancer/orders/presentation/providers/requests_provider.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'dart:convert';

// Top-level for background handling
final FlutterLocalNotificationsPlugin flutterLocalNotificationsPlugin =
    FlutterLocalNotificationsPlugin();

@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  debugPrint("Handling a background message: ${message.messageId}");

  // Prevent duplicate notifications: if the message has a notification payload, 
  // the system will display it, so we should NOT show a local one.
  if (message.notification != null) {
    return;
  }

  if (message.data.isNotEmpty) {
      // Check for chat message
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
            message.data['sender_name'] ?? message.data['title'] ?? 'New Message',
            message.data['message'] ?? message.data['body'] ?? 'You have a new message',
            platformChannelSpecifics,
            payload: 'chat_message',
          );
      } else if (message.data['type'] == 'new_order') {
          const AndroidNotificationDetails androidPlatformChannelSpecifics =
              AndroidNotificationDetails(
            'order_channel',
            'Orders',
            channelDescription: 'New Order Notifications',
            importance: Importance.high,
            priority: Priority.high,
          );
          
          const NotificationDetails platformChannelSpecifics =
              NotificationDetails(android: androidPlatformChannelSpecifics);

          await flutterLocalNotificationsPlugin.show(
            message.hashCode,
            message.data['title'] ?? 'New Order',
            message.data['body'] ?? 'You have received a new order',
            platformChannelSpecifics,
            payload: 'new_order',
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
      debugPrint('Notification clicked with payload: ${response.payload}');
      if (response.payload == 'new_order') {
         ProviderRouter.router.go('/?tab=requests');
      } else if (response.payload == 'chat_message') {
         ProviderRouter.router.go('/?tab=chat');
      }
    },
  );

  // Create Notification Channels
  const AndroidNotificationChannel messageChannel = AndroidNotificationChannel(
    'message_channel',
    'Messages',
    description: 'Chat Message Notifications',
    importance: Importance.high,
  );
  
  const AndroidNotificationChannel orderChannel = AndroidNotificationChannel(
    'order_channel',
    'Orders',
    description: 'New Order Notifications',
    importance: Importance.high,
  );

  final plugin = flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
      AndroidFlutterLocalNotificationsPlugin>();
      
  if (plugin != null) {
      await plugin.createNotificationChannel(messageChannel);
      await plugin.createNotificationChannel(orderChannel);
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
  
  // Handle foreground messages
  FirebaseMessaging.onMessage.listen((RemoteMessage message) {
     debugPrint('Got a message whilst in the foreground!');
     debugPrint('Message data: ${message.data}');
     
     // If notification is null, show local notification manually
     // If notification is not null, it might show automatically due to setForegroundNotificationPresentationOptions
     // But to be safe and consistent with "message channel", we can show it manually if needed.
     // However, simpler is to just reuse the background handler logic which handles data-only or notifications
     
     // For this app, let's explicitly show local notification if it's a new order or message
     // to ensure heads-up behavior.
     
     if (message.data['type'] == 'new_order') {
        flutterLocalNotificationsPlugin.show(
            message.hashCode,
            message.notification?.title ?? message.data['title'] ?? 'New Order',
            message.notification?.body ?? message.data['body'] ?? 'You have received a new order',
            NotificationDetails(
                android: AndroidNotificationDetails(
                    'order_channel',
                    'Orders',
                    channelDescription: 'New Order Notifications',
                    importance: Importance.high,
                    priority: Priority.high,
                ),
            ),
            payload: 'new_order',
        );
     } else if (message.data['type'] == 'message' || message.data['type'] == 'chat_message') {
        flutterLocalNotificationsPlugin.show(
            message.hashCode,
            message.notification?.title ?? message.data['sender_name'] ?? 'New Message',
            message.notification?.body ?? message.data['message'] ?? 'You have a new message',
            NotificationDetails(
                android: AndroidNotificationDetails(
                    'message_channel',
                    'Messages',
                    channelDescription: 'Chat Message Notifications',
                    importance: Importance.high,
                    priority: Priority.high,
                ),
            ),
            payload: 'chat_message',
        );
     }
  });

  // Handle Notification Click (Background -> App Open)
  FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
      debugPrint('A new onMessageOpenedApp event was published!');
      if (message.data['type'] == 'new_order') {
          ProviderRouter.router.go('/?tab=requests');
      } else if (message.data['type'] == 'chat_message' || message.data['type'] == 'message') {
          ProviderRouter.router.go('/?tab=chat');
      }
  });

  // Handle Notification Click (Terminated -> App Open)
  final initialMessage = await FirebaseMessaging.instance.getInitialMessage();
  if (initialMessage != null) {
      debugPrint('App launched from notification: ${initialMessage.data}');
      // Delay slightly to ensure router is ready if needed, though main is async
      Future.delayed(const Duration(milliseconds: 500), () {
          if (initialMessage.data['type'] == 'new_order') {
              ProviderRouter.router.go('/?tab=requests');
          } else if (initialMessage.data['type'] == 'chat_message' || initialMessage.data['type'] == 'message') {
              ProviderRouter.router.go('/?tab=chat');
          }
      });
  }
  
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark, // Android: Dark icons
      statusBarBrightness: Brightness.light, // iOS: Dark icons
      systemNavigationBarColor:
          ProviderTheme.backgroundColor, // Navigation bar color
      systemNavigationBarIconBrightness:
          Brightness.dark, // Navigation bar icons
      systemNavigationBarDividerColor: Colors.transparent,
    ),
  );

  final sharedPreferences = await SharedPreferences.getInstance();

  // Dio Configuration
  final dio = Dio(
    BaseOptions(
      baseUrl: ApiConstants.baseUrl,
      connectTimeout: const Duration(seconds: 30),
      receiveTimeout: const Duration(seconds: 30),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ),
  );

  // Add logging in debug mode
  if (kDebugMode) {
    dio.interceptors.add(
      LogInterceptor(
        request: true,
        requestHeader: true,
        requestBody: true,
        responseHeader: true,
        responseBody: true,
        error: true,
      ),
    );
  }

  runApp(
    riverpod.ProviderScope(
      overrides: [
        sharedPreferencesProvider.overrideWithValue(sharedPreferences),
      ],
      child: MultiProvider(
        providers: [
          Provider<SharedPreferences>.value(value: sharedPreferences),
          // Auth
          Provider<AuthRemoteDataSource>(
            create: (_) => AuthRemoteDataSourceImpl(dio: dio),
          ),
          Provider<AuthRepository>(
            create: (context) => AuthRepositoryImpl(
              remoteDataSource: context.read<AuthRemoteDataSource>(),
            ),
          ),
          ChangeNotifierProvider<AuthProvider>(
            create: (context) => AuthProvider(
              authRepository: context.read<AuthRepository>(),
              sharedPreferences: sharedPreferences,
            ),
          ),

          // Service Providers
          Provider<ServiceRemoteDataSource>(
            create: (_) => ServiceRemoteDataSourceImpl(dio: dio),
          ),
          Provider<ServiceRepository>(
            create: (context) => ServiceRepositoryImpl(
              remoteDataSource: context.read<ServiceRemoteDataSource>(),
            ),
          ),
          ChangeNotifierProxyProvider<AuthProvider, ServiceProvider>(
            create: (context) => ServiceProvider(
              serviceRepository: context.read<ServiceRepository>(),
              authProvider: context.read<AuthProvider>(),
            ),
            update: (context, authProvider, previous) => ServiceProvider(
              serviceRepository: context.read<ServiceRepository>(),
              authProvider: authProvider,
            ),
          ),
          ChangeNotifierProvider(create: (_) => ChatProvider()),
          ChangeNotifierProxyProvider<AuthProvider, RequestsProvider>(
            create: (_) => RequestsProvider(dio),
            update: (_, auth, prev) => (prev ?? RequestsProvider(dio))..update(auth),
          ),
        ],
        child: const ProviderApp(),
      ),
    ),
  );
}

class ProviderApp extends StatefulWidget {
  const ProviderApp({super.key});

  @override
  State<ProviderApp> createState() => _ProviderAppState();
}

class _ProviderAppState extends State<ProviderApp> {
  @override
  void initState() {
    super.initState();
    // setupInteractedMessage(); // Handled in main()
    
    // Listen for foreground messages - Handled in main()
    /*
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
       // ...
    });
    */
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
              // Handle other navigation
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
    
    // Check for Order
    if (message.data['type'] == 'new_order' || message.data['order_id'] != null) {
         // Use a slight delay to ensure router is ready if cold start
         Future.delayed(const Duration(milliseconds: 500), () {
             ProviderRouter.router.go('/?tab=requests');
         });
    }
    // Check for Chat
    else if (message.data['type'] == 'message' || message.data['message_id'] != null) {
        // Navigate to chat list or details (logic can be added)
        ProviderRouter.router.go('/?tab=chat'); // Assuming index 1 is chat
    }
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      title: 'Provider Portal',
      debugShowCheckedModeBanner: false,
      theme: ProviderTheme.lightTheme,
      darkTheme: ProviderTheme.darkTheme,
      themeMode: ThemeMode.light,
      routerConfig: ProviderRouter.router,
    );
  }
}


