# AI Local Services Marketplace - Project Documentation

## 1. Project Overview
**AI Local Services Marketplace** is a comprehensive, hybrid platform that bridges the gap between traditional local service bookings (e.g., plumbing, cleaning) and digital freelance gigs (e.g., graphic design, coding). It functions as a dual-sided marketplace connecting **Customers** with **Providers/Freelancers**.

The system is built with a **Laravel** backend API and two separate **Flutter** mobile applications (Customer & Provider).

### Key Differentiators
- **Hybrid Model**: Supports both "Services" (Appointment-based, often location-dependent) and "Gigs" (Productized services, digital delivery).
- **AI Integration**: Features AI-assisted tools (referenced in codebase as `ai_options_sheet`) likely for content generation or chat assistance.
- **Real-time Interaction**: Integrated Chat and Notifications using Pusher and Firebase.

---

## 2. Technology Stack

### Backend (Laravel_API)
- **Framework**: Laravel 12.0 (PHP ^8.2)
- **Authentication**: Laravel Sanctum
- **Real-time**: Pusher PHP Server
- **PDF Generation**: Barryvdh/Laravel-DomPDF
- **Database**: MySQL (implied by Eloquent models)
- **Key Models**: `User`, `Service`, `Gig`, `Booking`, `Order`, `FlashSale`, `SubscriptionPlan`.

### Mobile Apps (Flutter)
- **Framework**: Flutter (Dart SDK ^3.10.4)
- **State Management**: Provider ^6.1.5 & Riverpod ^3.1.0 (Hybrid approach)
- **Routing**: GoRouter
- **Networking**: Dio
- **Real-time**: Pusher Channels Flutter, Firebase Messaging
- **UI Components**: Shimmer, Flutter Animate, Google Fonts, SVG support.

---

## 3. System Architecture & Features

### A. The Backend (Laravel_API)
The brain of the operation. It manages users, content, logic, and payments.

**Core Modules (in `app/Models`):**
1.  **User Management**: `User`, `Role`, `ProviderProfile`.
2.  **Service Marketplace**:
    -   `Service`: A standard service offering (e.g., "House Cleaning").
    -   `ServicePackage`, `ServiceExtra`: Pricing tiers and add-ons.
    -   `Booking`: The transaction record for services.
3.  **Gig Marketplace (Freelance)**:
    -   `Gig`: A freelance job listing.
    -   `GigPackage`, `GigFaq`: Details for the gig.
    -   `GigOrder`: The transaction record for gigs.
4.  **Marketing & Monetization**:
    -   `FlashSale`, `Coupon`, `Promotion`.
    -   `SubscriptionPlan`, `ProviderSubscription`: Monetization for the platform owner.
    -   `Banner`, `FreelancerBanner`: Advertising spaces.
5.  **Financials**:
    -   `Withdrawal`: For providers to cash out earnings.

### B. Customer App (Flutter_Customer)
Targeted at end-users looking to hire help.

**Key Features (in `lib/features`):**
-   **Auth**: Login, Register, Password Recovery.
-   **Home**: Dynamic homepage with Flash Sales, Interests (`spark_interest_section`), and Testimonials.
-   **Service Discovery**: Search, Categories, Service Details.
-   **Freelancer Section**: Dedicated flow for browsing and booking Gigs (`FreelancerGigDetailsPage`).
-   **Chat**: Real-time messaging with providers (`chat_page`).
-   **AI**: AI Options Sheet (likely for smart suggestions).
-   **Profile**: Order history, Notifications, Settings.

### C. Provider App (Flutter_Provider)
Targeted at workers and freelancers.

**Key Features (in `lib/features`):**
-   **Dashboard**: Overview of earnings, orders, and performance.
-   **Mode Selection**: `provider_mode_selection_page` suggests users can operate as a Local Provider, a Freelancer, or both.
-   **Gig Management**: Create/Edit Gigs, manage FAQ and Tags.
-   **Service Management**: Create/Edit Services.
-   **Order Management**: View and process incoming orders (`requests_page`).
-   **Chat**: Communicate with customers.

---

## 4. Directory Structure Guide

### `Laravel_API/`
-   `app/Models/`: The database schema definitions.
-   `app/Http/Controllers/`: API logic endpoints.
-   `routes/api.php`: API Route definitions.
-   `database/`: Migrations and Seeders.

### `Flutter_Customer/` & `Flutter_Provider/`
Both apps follow a **Feature-First Architecture**:
-   `lib/core/`: Shared utilities (Constants, Theme, Network Clients).
-   `lib/features/`: Self-contained modules.
    -   `feature_name/data/`: API calls, Models, Repositories.
    -   `feature_name/domain/`: Interfaces (Entities, Repository definitions).
    -   `feature_name/presentation/`: UI (Pages, Widgets) and State (Providers).

---

## 5. Development & Setup

### Prerequisites
-   PHP 8.2+
-   Composer
-   Flutter SDK
-   MySQL Database

### Setup Steps
1.  **Backend**:
    ```bash
    cd Laravel_API
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --seed
    php artisan serve
    ```
2.  **Mobile Apps**:
    ```bash
    cd Flutter_Customer # or Flutter_Provider
    flutter pub get
    flutter run
    ```

---

## 6. Current Status Assessment (0-100%)
Based on the code analysis:

-   **Backend Structure (90%)**: The data models are extremely comprehensive, covering complex scenarios like flash sales, subscriptions, and hybrid service types.
-   **Mobile App Structure (85%)**: The feature folders are well-organized. Key flows (Auth, Chat, Booking, Home) are present.
-   **Integration**: The presence of `api_constants.dart` (implied) and Dio setups suggests the connection logic is in place.
-   **UI/UX**: The use of `shimmer`, `flutter_animate`, and `svg` indicates a polished frontend.

**Missing/To-Do (Potential):**
-   Payment Gateway integration specifics (Stripe/PayPal logic needs to be verified in Controllers).
-   Advanced AI logic (currently just UI placeholders like `ai_options_sheet`).
