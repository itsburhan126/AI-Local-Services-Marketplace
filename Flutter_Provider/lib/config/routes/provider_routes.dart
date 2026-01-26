import 'package:go_router/go_router.dart';
import '../../features/splash/presentation/pages/splash_page.dart';
import '../../features/intro/presentation/pages/intro_page.dart';
import '../../features/intro/presentation/pages/welcome_page.dart';
import '../../features/auth/presentation/pages/login_page.dart';
import '../../features/auth/presentation/pages/register_page.dart';
import '../../features/dashboard/presentation/pages/dashboard_page.dart';
import '../../features/freelancer/gigs/presentation/pages/create_gig_page.dart';
import '../../features/chat/presentation/pages/chat_details_page.dart';
import '../../features/auth/data/models/user_model.dart';

import '../../features/auth/presentation/pages/provider_mode_selection_page.dart';

import 'package:flutter/material.dart';

class ProviderRouter {
  static final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();
  
  static final GoRouter router = GoRouter(
    navigatorKey: navigatorKey,
    initialLocation: '/splash',
    routes: [
      GoRoute(path: '/splash', builder: (context, state) => const SplashPage()),
      GoRoute(path: '/intro', builder: (context, state) => const IntroPage()),
      GoRoute(
        path: '/welcome',
        builder: (context, state) => const WelcomePage(),
      ),
      GoRoute(path: '/login', builder: (context, state) => const LoginPage()),
      GoRoute(
        path: '/register',
        builder: (context, state) => const RegisterPage(),
      ),
      GoRoute(
        path: '/mode-selection',
        builder: (context, state) => const ProviderModeSelectionPage(),
      ),
      GoRoute(
        path: '/create-gig',
        builder: (context, state) => const CreateGigPage(),
      ),
      GoRoute(
        path: '/chat-details',
        name: 'chat-details',
        builder: (context, state) {
          final user = state.extra as UserModel;
          return ChatDetailsPage(otherUser: user);
        },
      ),
      GoRoute(
        path: '/bookings',
        builder: (context, state) => const DashboardPage(initialIndex: 2),
      ),
      GoRoute(
        path: '/',
        builder: (context, state) {
          final tab = state.uri.queryParameters['tab'];
          int index = 0;
          if (tab == 'requests') index = 2;
          else if (tab == 'chat') index = 1;
          return DashboardPage(initialIndex: index);
        },
      ),
    ],
  );
}
