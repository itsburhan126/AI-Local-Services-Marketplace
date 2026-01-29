# Flutter Provider App - Web Migration Guide

This document provides a comprehensive analysis of the `Flutter_Provider` application to facilitate its migration to a web version. It covers the project structure, navigation flow, screen details, and key features.

## 1. Project Overview

*   **Framework:** Flutter
*   **Architecture:** Feature-based (Clean Architecture inspired)
*   **State Management:** Hybrid (Provider + Riverpod)
    *   **Provider:** Used for Auth, Chat, Dashboard, Orders.
    *   **Riverpod:** Used for Freelancer Gigs features.
*   **Navigation:** GoRouter
*   **Networking:** Dio + Pusher (Real-time)
*   **Theme:** Custom `ProviderTheme`

### Directory Structure (`lib/`)
*   `config/`: App configuration (Routes).
*   `core/`: Shared constants, themes, utils.
*   `features/`: Modular features (Auth, Chat, Dashboard, Freelancer, Intro, Profile, Services, Splash, Support).

## 2. Navigation Flow

The app uses `GoRouter` defined in `lib/config/routes/provider_routes.dart`.

### Main Routes
*   `/splash`: Initial splash screen.
*   `/intro` -> `/welcome`: Onboarding flow.
*   `/login` -> `/register`: Authentication.
*   `/mode-selection`: Switch between "Freelancer" and "Local Service" modes.
*   `/`: **Dashboard Page** (Main App Shell).
    *   Controls the Bottom Navigation Bar.
    *   Tabs: Home, Chat, Orders, Gigs/Services, Profile.
*   `/create-gig`: Page to create a new freelancer gig.
*   `/chat-details`: Individual chat conversation.

## 3. Screen Analysis & Web Recommendations

### A. Authentication
*   **Screens:** `LoginPage`, `RegisterPage`, `ProviderModeSelectionPage`.
*   **Web Note:** Ensure forms are centered on large screens with appropriate max-width. "Mode Selection" should be a distinct, visually appealing step.

### B. Dashboard (The Main Shell)
*   **File:** `lib/features/dashboard/presentation/pages/dashboard_page.dart`
*   **Functionality:**
    *   Handles bottom navigation.
    *   Initializes real-time listeners (Pusher) for chat and order requests.
    *   **Logic:** Checks `user.serviceRule` or `user.mode` to toggle between `FreelancerHomeView` and `LocalServiceHomeView`.
*   **Web Note:** Convert Bottom Navigation Bar to a Side Navigation Drawer or Top Navigation Bar for desktop/web layouts.

### C. Home Tab
#### 1. Freelancer Home (`FreelancerHomeView`)
*   **File:** `lib/features/freelancer/presentation/pages/freelancer_home_view.dart`
*   **Features:**
    *   **Header:** User greeting and Notification icon.
    *   **Main Stats Grid:** Level, Success Score, Rating (5.0), Response Rate.
    *   **Performance Metrics:** Orders, Unique Clients, Earnings (Progress bars).
    *   **New Briefs:** Card showing potential job matches (currently static "Nothing here" state).
    *   **Earnings Detailed:** Available for withdrawal, Monthly earnings, Avg. selling price, Active orders, Pending payments.
    *   **To-dos:** Unread messages count, Action items.
    *   **My Gigs Stats:** Impressions, Clicks (Last 7 days).
*   **Web Note:** This is a dashboard-heavy view. Use a Grid Layout (Masonry or CSS Grid) to arrange these cards efficiently on wider screens instead of a single vertical column.

#### 2. Local Service Home (`LocalServiceHomeView`)
*   **File:** `lib/features/dashboard/presentation/pages/dashboard_page.dart` (Inline)
*   **Features:**
    *   **Header:** Greeting, Online Status Toggle.
    *   **Earnings Card:** Total Earnings, Jobs count, Rating, Wallet balance.
    *   **Recent Job Requests:** List of incoming jobs (Empty state shown).
*   **Web Note:** Similar to Freelancer Home, expand the layout. The "Online Toggle" should be prominent.

### D. Chat Tab
*   **Screens:** `ChatPage`, `ChatDetailsPage`.
*   **Features:** List of conversations, real-time messaging, file/image sharing.
*   **Web Note:** On desktop, use a Split-View layout (List on left, Conversation on right) rather than navigating between two separate pages.

### E. Orders/Requests Tab
*   **Screens:** `RequestsPage` (Freelancer) or `OrdersPage`.
*   **Features:** List of pending, active, and completed orders.
*   **Web Note:** Use a detailed Table View for orders on web, with sortable columns (Date, Client, Amount, Status).

### F. Gigs/Services Tab
#### 1. Freelancer Gigs (`GigsPage`)
*   **Features:** List of user's gigs, Create Gig button.
*   **Create Gig Flow (`CreateGigPage`):**
    *   Likely a multi-step form (Title, Category, Pricing, Description, Images).
*   **Web Note:**
    *   **Gigs List:** Grid view of Gig Cards.
    *   **Create Gig:** Use a Stepper UI for the creation process on web.

#### 2. Services (`ServicesPage`)
*   **Features:** Managing local services offered.

### G. Profile Tab
*   **Screens:** `ProfilePage`.
*   **Features:** User details, Wallet access, Settings, Support.
*   **Web Note:** Standard profile layout.

## 4. Key Data Models & Providers
*   **Auth:** `AuthProvider` (User state, Token).
*   **Chat:** `ChatProvider` (Conversations, Messages, Pusher integration).
*   **Orders:** `RequestsProvider` (Order fetching, Status updates).
*   **Gigs:** `GigProvider` (CRUD operations for gigs).
*   **Wallet:** `WalletProvider` (Transactions, Withdrawals).

## 5. Migration Strategy Checklist

1.  **Responsive Layout Wrapper:** Create a `WebLayout` wrapper that implements a Sidebar/TopNav for screens > 600px width.
2.  **Navigation:** Replace `BottomNavigationBar` with `NavigationRail` or `Drawer` for desktop/tablet.
3.  **Chat UI:** Implement split-pane view for `ChatPage` + `ChatDetailsPage`.
4.  **Forms:** Ensure `CreateGigPage` and `Auth` forms have constrained width on large screens.
5.  **Grids:** Convert `ListView` in `FreelancerHomeView` to `SliverGrid` or `Wrap` for dashboard widgets.
6.  **Assets:** Ensure `ApiConstants` points to the correct Web/CORS-enabled API endpoint.
7.  **Dependencies:** Check compatibility of packages like `flutter_animate`, `go_router`, `pusher_client` (or equivalent) for Web.

This guide covers the 0-100% scope of the current Flutter Provider app structure.
