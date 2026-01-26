import 'package:go_router/go_router.dart';
import '../../features/auth/presentation/pages/login_page.dart';
import '../../features/auth/presentation/pages/register_page.dart';
import '../../features/home/presentation/pages/home_page.dart';
import '../../features/home/presentation/pages/service_details_page.dart';
import '../../features/home/presentation/pages/category_page.dart';
import '../../features/search/presentation/pages/search_page.dart';
import '../../features/freelancer/bookings/presentation/pages/bookings_page.dart';
import '../../features/freelancer/bookings/presentation/pages/booking_details_page.dart';
import '../../features/profile/presentation/pages/edit_profile_page.dart';
import '../../features/profile/presentation/pages/notifications_page.dart';
import '../../features/splash/presentation/pages/splash_page.dart';
import '../../features/intro/presentation/pages/intro_page.dart';
import '../../features/auth/presentation/providers/auth_provider.dart';
import '../../features/auth/presentation/pages/ServiceRuleSelectionPage.dart';
import '../../features/home/presentation/pages/InterestSelectionPage.dart';
import '../../features/freelancer/presentation/pages/FreelancerHomePage.dart';
import '../../features/freelancer/presentation/pages/FreelancerGigDetailsPage.dart';
import '../../features/freelancer/presentation/pages/FreelancerProfilePage.dart';
import '../../features/freelancer/presentation/pages/OrderUpgradePage.dart';
import '../../features/freelancer/presentation/pages/OrderReviewPage.dart';
import '../../features/freelancer/presentation/pages/OrderSuccessPage.dart';
import '../../features/freelancer/presentation/pages/FreelancerCategoryPage.dart';
import '../../features/chat/presentation/pages/chat_details_page.dart';
import '../../features/auth/data/models/user_model.dart';

import 'package:flutter/material.dart'; // Added for GlobalKey

class AppRouter {
  final AuthProvider authProvider;
  static final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();

  AppRouter(this.authProvider);

  late final GoRouter router = GoRouter(
    navigatorKey: navigatorKey,
    initialLocation: '/splash',
    refreshListenable: authProvider,
    routes: [
      GoRoute(
        path: '/splash',
        builder: (context, state) => const SplashPage(),
      ),
      GoRoute(
        path: '/intro',
        builder: (context, state) => const IntroPage(),
      ),
      GoRoute(
        path: '/login',
        builder: (context, state) => const LoginPage(),
      ),
      GoRoute(
        path: '/register',
        builder: (context, state) => const RegisterPage(),
      ),
      GoRoute(
        path: '/service-rule-selection',
        builder: (context, state) => const ServiceRuleSelectionPage(),
      ),
      GoRoute(
        path: '/interest-selection',
        builder: (context, state) {
           final rule = state.extra as String?;
           // Map rule to type: 'freelancer' or 'local_service'
           // Assuming rule is exactly 'freelancer' or 'local_service'
           return InterestSelectionPage(type: rule == 'freelancer' ? 'freelancer' : 'local_service');
        },
      ),
      GoRoute(
        path: '/freelancer-home',
        builder: (context, state) => FreelancerHomePage(),
      ),
      GoRoute(
        path: '/',
        builder: (context, state) => const HomePage(),
      ),
      GoRoute(
        path: '/service-details',
        builder: (context, state) {
          final service = state.extra as Map<String, dynamic>?;
          return ServiceDetailsPage(service: service);
        },
      ),
      GoRoute(
        path: '/freelancer-gig-details',
        builder: (context, state) {
          final service = state.extra as Map<String, dynamic>?;
          return FreelancerGigDetailsPage(service: service);
        },
      ),
      GoRoute(
        path: '/freelancer-profile',
        builder: (context, state) {
          final provider = state.extra as Map<String, dynamic>;
          return FreelancerProfilePage(provider: provider);
        },
      ),
      GoRoute(
        path: '/freelancer-category',
        builder: (context, state) {
          final category = state.extra as Map<String, dynamic>;
          return FreelancerCategoryPage(category: category);
        },
      ),
      GoRoute(
        path: '/order-upgrade',
        builder: (context, state) {
          final bookingData = state.extra as Map<String, dynamic>;
          return OrderUpgradePage(bookingData: bookingData);
        },
      ),
      GoRoute(
        path: '/order-review',
        builder: (context, state) {
          final bookingData = state.extra as Map<String, dynamic>;
          return OrderReviewPage(bookingData: bookingData);
        },
      ),
      GoRoute(
        path: '/order-success',
        builder: (context, state) => OrderSuccessPage(),
      ),
      GoRoute(
        path: '/category',
        builder: (context, state) {
          final type = state.uri.queryParameters['type'];
          final category = state.extra as Map<String, dynamic>?;
          return CategoryPage(type: type, category: category);
        },
      ),
      GoRoute(
        path: '/edit-profile',
        builder: (context, state) => const EditProfilePage(),
      ),
      GoRoute(
        path: '/notifications',
        builder: (context, state) => const NotificationsPage(),
      ),
      GoRoute(
        path: '/bookings',
        builder: (context, state) => const BookingsPage(),
      ),
      GoRoute(
        path: '/booking-details',
        builder: (context, state) {
          final booking = state.extra as Map<String, dynamic>?;
          return BookingDetailsPage(booking: booking);
        },
      ),
      GoRoute(
        path: '/search-page',
        builder: (context, state) => const SearchPage(),
      ),
      GoRoute(
        path: '/chat-details',
        builder: (context, state) {
           UserModel otherUser;
           if (state.extra is UserModel) {
             otherUser = state.extra as UserModel;
           } else {
             final extra = state.extra as Map<String, dynamic>;
             otherUser = UserModel(
               id: extra['id'] is int ? extra['id'] : int.tryParse(extra['id'].toString()),
               name: extra['name'],
               profileImage: extra['image'],
             );
           }
           return ChatDetailsPage(otherUser: otherUser);
        },
      ),
    ],
    redirect: (context, state) {
      final isLoggingIn = state.uri.toString() == '/login';
      final isRegistering = state.uri.toString() == '/register';
      final isIntro = state.uri.toString() == '/intro';
      final isSplash = state.uri.toString() == '/splash';
      final isAuthenticated = authProvider.isAuthenticated;

      // If user is authenticated
      if (isAuthenticated) {
        final user = authProvider.user;

        // 1. Enforce Service Rule Selection
        if (user?.serviceRule == null) {
           if (state.uri.toString() != '/service-rule-selection') {
              return '/service-rule-selection';
           }
           return null;
        }

        // 2. Redirect away from Auth Pages
        if (isLoggingIn || isRegistering || isIntro) {
          if (user?.serviceRule == 'freelancer') return '/freelancer-home';
          return '/';
        }

        // 3. Enforce Correct Home Page
        if (state.uri.toString() == '/' && user?.serviceRule == 'freelancer') {
           return '/freelancer-home';
        }
        if (state.uri.toString() == '/freelancer-home' && user?.serviceRule == 'local_service') {
           return '/';
        }
      }

      // If not authenticated, prevent access to protected pages
      // Currently only '/' is protected
      // Note: We allow /splash to handle its own logic or fall through
      if (!isAuthenticated && !isLoggingIn && !isRegistering && !isIntro && !isSplash) {
        return '/intro';
      }

      return null;
    },
  );
}
