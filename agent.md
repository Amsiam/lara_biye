# Agent Context: Engineer's Matrimony Platform

## Project Overview

**Name:** Engineer's Matrimony
**Type:** Matrimony/Matchmaking Platform for Engineers
**Framework:** Laravel 12.0
**PHP Version:** ^8.2
**Current Branch:** siam (main branch: main)

This is a comprehensive matrimony platform with profile management, connection system, payment integration, and notifications.

---

## Technology Stack

### Backend
- **Laravel:** 12.0
- **Database:** MySQL (production), SQLite (development)
- **Session/Cache/Queue:** Database driver
- **Payment Gateway:** bKash (via `theihasan/laravel-bkash`)
- **Admin Panel:** Filament 4.0

### Frontend
- **CSS Framework:** Tailwind CSS v4 with Vite
- **Components:** Livewire Volt v1.7 (Livewire 3)
- **UI Kit:** Livewire Flux v2, Flowbite
- **JavaScript:** Alpine.js, Axios, ES Modules
- **Build Tool:** Vite 6.0
- **Fonts:** Inter, Marko One

### Development Tools
- `laravel/tinker` - REPL
- `laravel/pint` - Code formatting
- `pestphp/pest` - Testing
- `barryvdh/laravel-debugbar` - Debugging

---

## Project Structure

```
lara_biye/
├── app/
│   ├── Models/                  # 20 models (User + 19 profile/system models)
│   ├── Http/
│   │   ├── Controllers/         # 4 controllers (minimal, Volt handles most logic)
│   │   └── Middleware/          # CheckConnection middleware
│   ├── Livewire/                # Livewire components
│   ├── Mail/                    # InvoiceMail mailable
│   ├── Providers/
│   │   └── Filament/           # Filament admin panel provider
│   │       └── AdminPanelProvider.php
│   └── View/Components/         # Blade components
├── database/
│   ├── migrations/              # 30 migrations (includes packages, purchases)
│   └── seeders/                 # PackageSeeder
├── resources/
│   ├── views/
│   │   ├── livewire/           # 34 Volt components (includes payment/connection history)
│   │   ├── emails/             # Invoice email template
│   │   └── components/         # Blade components
│   ├── css/                     # Tailwind + custom styles
│   └── js/
├── routes/
│   ├── web.php                  # Main application routes
│   └── auth.php                 # Authentication routes
├── config/                      # Laravel configs + bkash.php
├── storage/app/public/photos/   # User uploaded images
└── public/
```

---

## Database Architecture

### Core Models & Relationships

**User Model (Central Hub):**
- Implements `MustVerifyEmail`
- **HasOne:** basicInfo, education, location, physicalAttribute, language, hobbiesAndInterest, personalAttitude, lifeStyle, familyInformation, spiritualAndSocialBackground, residencyInformation, partnerExpectation, presentAddress, connection, galary
- **HasMany:** siblingInfo, visitedProfiles
- **BelongsToMany:** connectedUsers (via `connected` pivot table)

### Profile Models (18 models)

| Model | Key Fields | Purpose |
|-------|-----------|---------|
| **BasicInfo** | name, dob, gender, religion, blood_group, profile_image, NID, student_id, university, is_nid_verified | Core identity |
| **PhysicalAttribute** | eye_color, hair, complexion, body_type, disability | Physical traits |
| **EducationCareer** | education, employment, occupation, annual_income | Professional info |
| **Language** | language preferences | Communication |
| **Location** | country, division, district, upazilla, union | Present address |
| **HobbiesAndInterest** | hobbies, interests | Personal interests |
| **PersonalAttitude** | attitude, behavior | Personality |
| **LifeStyle** | lifestyle choices | Living preferences |
| **FamilyInformation** | father_name, mother_name, siblings | Family details |
| **SpiritualAndSocialBackground** | spiritual/social info | Background |
| **ResidencyInformation** | permanent address details | Residency |
| **PartnerExpectation** | 27 fields for ideal partner criteria | Match preferences |
| **SiblingsInfo** | Multiple sibling records | Family structure |
| **Galary** | Gallery images | Photo gallery |
| **PresentAddress** | Current address | Location |
| **Connection** | connection_count | Available connections |
| **VisitedProfile** | visited_user_id, count | Profile visit tracking |
| **Notification** | message, is_read, sender_id | Notifications |

### System Models (4 models)

| Model | Key Fields | Purpose |
|-------|-----------|---------|
| **Package** | name, description, connections, price, is_popular, is_active | Connection packages for purchase |
| **Purchase** | user_id, package_id, amount, transaction_id, payment_id, invoice_number, payment_method, status, payment_stage, connections_purchased, connections_applied, connections_applied_at, is_refunded, refunded_at, error_message, payment_initiated_at, payment_completed_at, payment_response | Payment/purchase records with comprehensive stage tracking |
| **Setting** | key, value, type, group, description | Configurable application settings managed via Filament admin panel |
| **Faq** | question, answer | Frequently asked questions managed via Filament |

### Database Schema Pattern

- **Privacy Control:** Most tables have `is_shown` boolean for visibility control
- **Soft Identity Verification:** `is_nid_verified` field for NID validation
- **Atomic Design:** Each profile section is a separate table (allows independent CRUD)

---

## Core Features & Business Logic

### 1. Authentication System

**Flow:**
- Email/password registration with verification required
- Password reset with token-based flow
- Bcrypt hashing (12 rounds)
- Session-based authentication
- Middleware: `guest`, `auth`, `verified`, `signed`

**Files:**
- Routes: `routes/auth.php`
- Components: `resources/views/livewire/auth/*.blade.php`

### 2. Profile Management

**Structure:**
- Comprehensive profile with 15+ editable sections
- Each section independently editable
- Privacy controls per section (show to: all, free users, premium, none)
- Profile image upload (stored in `storage/app/public/photos`)

**Pattern:**
```php
// Conditional visibility
if (auth()->user()?->id == $user->id) {
    // Show own profile - all sections
} elseif (auth()->user()?->isConnected($user->id)) {
    // Show connected user - visible sections only
} else {
    // Show limited public info
}
```

**Files:**
- Components: `resources/views/livewire/profile/*.blade.php`
- Models: `app/Models/*.php`

### 3. Connection System

**State Machine:**
```
User A sends request → PENDING
                    ↓
         ┌──────────┴──────────┐
         ↓                     ↓
     ACCEPTED              REJECTED
```

**Smart Logic:**
```php
// If mutual interest, auto-accept
if (userB->isConnectionPending(userA->id)) {
    // Auto-accept both directions
}
```

**Connection Purchase:**
- Payment via bKash: 1 purchase = 3 connections
- Connection cost: 1 connection = view 1 profile
- Tracked in `connections.connection_count`

**Files:**
- Model: `app/Models/User.php` (connection methods)
- Component: `resources/views/livewire/connections.blade.php`
- Middleware: `app/Http/Middleware/CheckConnection.php`

### 4. Profile Access Control

**CheckConnection Middleware Logic:**
1. First visit to a profile = free (10 total free views)
2. Track visits in `visited_profiles` table
3. After free views exhausted: 1 connection = 1 view
4. Redirect with error if no connections remaining

**Files:**
- Middleware: `app/Http/Middleware/CheckConnection.php`
- Applied to: `routes/web.php` profile route

### 5. Search & Discovery

**Features:**
- Filter by: gender, age range (18-25, 26-35, 36-45), marital status
- Real-time filtering with `wire:model.live`
- Computed properties for lazy loading

**Files:**
- Component: `resources/views/livewire/search.blade.php`

### 6. Notifications System

**Triggers:**
- Connection request sent
- Connection request accepted

**Features:**
- Read/unread tracking
- Mark individual as read
- Mark all as read
- Navbar dropdown display

**Files:**
- Model: `app/Models/Notification.php`
- Routes: `/markAsRead`, `/markAsRead/{notification}`

### 7. Payment Integration (bKash) with Stage Tracking

**Payment Stages:**
- `initiated` - Payment process started, purchase record created
- `pending` - bKash payment URL generated, awaiting user action
- `completed` - Payment successful, transaction verified
- `failed` - Payment failed or error occurred
- `refunded` - Payment refunded to user
- `cancelled` - Payment cancelled by user

**Flow:**
```
User initiates payment
    ↓
PaymentController creates Purchase record (stage: initiated)
    ↓
Create bKash transaction → Update stage to pending
    ↓
Redirect to bKash
    ↓
User completes payment
    ↓
bKash callback to /bkash/callback
    ↓
BkashController verifies payment
    ↓
If status === 'Completed':
  - Mark payment_stage as 'completed'
  - Apply connections via applyConnections() method
  - Send invoice email
  ↓
Else: Mark as 'failed' with error message
    ↓
Redirect to /bkash/success or /bkash/failed
```

**Purchase Model Methods:**
```php
// Safe connection application
public function applyConnections(): bool
  - Checks if already applied (prevents duplicates)
  - Checks if payment is completed
  - Uses getAttribute/setAttribute to avoid DB connection conflicts
  - Adds connections to user's account
  - Marks connections_applied = true

// Mark as completed
public function markAsCompleted(string $transactionId): bool

// Mark as failed
public function markAsFailed(string $errorMessage): bool

// Query scopes
public function scopeCompleted($query)
public function scopePending($query)
public function scopeFailed($query)
public function scopeRefunded($query)
public function scopeConnectionsApplied($query)
```

**Important Note:**
The `connection` field requires special handling to avoid conflicts with Laravel's Eloquent `connection` property:
```php
// CORRECT - Use getAttribute/setAttribute
$currentConnections = (int) ($userConnection->getAttribute('connection') ?? 0);
$userConnection->setAttribute('connection', $currentConnections + $newConnections);

// INCORRECT - Direct access causes "Database connection [X] not configured" error
$userConnection->connection = $currentConnections + $newConnections;
```

**Configuration:**
```env
BKASH_SANDBOX=false  # Set true for testing
BKASH_APP_KEY=...
BKASH_APP_SECRET=...
BKASH_USERNAME=...
BKASH_PASSWORD=...
```

**Files:**
- Controller: `app/Http/Controllers/PaymentController.php`
- Controller: `app/Http/Controllers/Vendor/Bkash/BkashController.php`
- Model: `app/Models/Purchase.php` (with stage tracking methods)
- Model: `app/Models/Connection.php` (with integer casting)
- Migration: `database/migrations/YYYY_MM_DD_add_payment_tracking_fields_to_purchases_table.php`
- Config: `config/bkash.php`
- Routes: `/payment/{provider}`, `/bkash/callback`, `/bkash/success`, `/bkash/failed`

---

## Routing Structure

### Main Routes (`routes/web.php`)

```php
GET  /                      → welcome (public landing)
GET  /search                → search with filters
GET  /dashboard             → redirect to /profile
GET  /profile/{profileId}   → view profile (CheckConnection middleware)
GET  /settings              → redirect to /settings/profile
GET  /settings/profile      → edit profile
GET  /settings/password     → change password
GET  /settings/appearance   → appearance settings
GET  /your-connections      → manage connections
GET  /payment/{provider}    → initiate payment
GET  /bkash/callback        → payment callback
GET  /bkash/success         → payment success
GET  /bkash/failed          → payment failure
GET  /markAsRead            → mark all notifications read
GET  /markAsRead/{id}       → mark single notification read
POST /logout                → logout
```

### Auth Routes (`routes/auth.php`)

```php
GET  /login                     → login form
GET  /register                  → registration form
GET  /forgot-password           → forgot password
GET  /reset-password/{token}    → reset password
GET  /verify-email              → verification notice
GET  /verify-email/{id}/{hash}  → verification handler (signed)
GET  /confirm-password          → confirm password
POST /logout                    → logout
```

**Middleware Groups:**
- `guest` - Auth pages (redirect if authenticated)
- `auth` + `verified` - Protected pages
- `signed` - Email verification links
- `throttle:6,1` - Rate limiting on verification

---

## Livewire/Volt Architecture

### Component Pattern

```php
<?php
use function Livewire\Volt\{state, computed};

// State declaration
state(['field1', 'field2']);

// Computed property (cached)
$user = computed(function () {
    return User::with('relations')->find($this->id);
});

// Actions
$save = function () {
    $this->validate();
    // Save logic
};
?>

<div>
    <!-- Blade template with wire: directives -->
</div>
```

### Key Patterns

1. **Computed Properties:** Lazy-loaded, cached
2. **File Uploads:** `WithFileUploads` trait
3. **Form Validation:** `rules()` function
4. **Real-time Updates:** `wire:model.live`
5. **Confirmation Dialogs:** `wire:confirm`

### Volt Components (31 total)

**Auth (6):** login, register, forgot-password, reset-password, verify-email, confirm-password
**Profile (16):** basic_info, education, family, hobby, introduction, language, lifestyle, parmanent, partner, personal_attitude, physical_attr, present_address, residency, spiritual, upload-profile, astronomic
**Core (5):** welcome, search, profile, connections, logout
**Settings (4):** profile, password, appearance, delete-user-form

### Blade Components (Custom)

**select-input.blade.php** - Reusable dropdown component with:
- Icon support
- Consistent styling
- Hover/focus states
- Wire model binding
- Custom pink theme

---

## Coding Standards & Patterns

### 1. Model Relationships

**Convention:**
```php
// User.php
public function basicInfo(): HasOne {
    return $this->hasOne(BasicInfo::class);
}

// Related model
public function user(): BelongsTo {
    return $this->belongsTo(User::class);
}
```

### 2. Privacy Controls

**Pattern:**
```php
// In migrations
$table->boolean('is_shown')->default(true);

// In views
@if($section->is_shown || auth()->id() == $user->id)
    <!-- Show content -->
@endif
```

### 3. File Uploads

**Pattern:**
```php
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

#[Validate('image|max:2048')]
public $photo;

public function save() {
    $path = $this->photo->store('photos', 'public');
    // Save $path to database
}
```

### 4. Connection Methods (User Model)

**Key Methods:**
```php
// Send connection request
public function sendConnectionRequest(User $user)

// Check if connected (ACCEPTED)
public function isConnected($userId): bool

// Check if pending
public function isConnectionPending($userId): bool

// Check if user sent me request
public function hasSentConnectionRequest($userId): bool

// Relationships
public function connectedUsers()   // Users I sent to
public function rConnectedUsers()  // Users who sent to me
```

### 5. Validation Rules

**Common Patterns:**
```php
'email' => 'required|email|unique:users'
'password' => 'required|min:8|confirmed'
'photo' => 'nullable|image|max:2048'
'age' => 'nullable|integer|min:18|max:100'
```

### 6. Naming Conventions

**Files:**
- Models: `PascalCase.php` (e.g., `BasicInfo.php`)
- Migrations: `YYYY_MM_DD_HHMMSS_create_table_name_table.php`
- Components: `kebab-case.blade.php` (e.g., `basic-info.blade.php`)
- Controllers: `PascalCase.php` with `Controller` suffix

**Database:**
- Tables: `snake_case` plural (e.g., `basic_infos`)
- Columns: `snake_case` (e.g., `user_id`, `is_shown`)
- Pivot tables: Alphabetical order (e.g., `connected`)

**Variables:**
- PHP: `$camelCase`
- Blade: `$camelCase`
- CSS classes: Tailwind utilities

---

## Frontend Architecture

### Layout Structure

**Main Layouts:**
```
components/layouts/
├── app.blade.php        # Main app (navbar, sidebar, content)
├── auth.blade.php       # Auth pages
└── auth/
    ├── card.blade.php   # Card-based auth
    ├── simple.blade.php # Simple auth
    └── split.blade.php  # Split-screen auth
```

### Custom Theme Colors

```css
--color-maroon: #490B22
--color-custom-pink: #E33183
--color-custom-red: #490b22
```

### Responsive Design

- Mobile-first approach
- Breakpoints: `md:` (768px), `lg:` (1024px)
- Hamburger menu for mobile navigation

### JavaScript Features

- **Alpine.js:** Interactive components
- **Livewire:** Wire directives (`wire:model`, `wire:click`, `wire:submit`)
- **Axios:** HTTP requests
- **Auto-dismiss errors:** 3-second timeout

---

## Configuration Files

### Environment Variables

**Required:**
```env
APP_NAME=Laravel
APP_ENV=local|production
APP_DEBUG=true|false
APP_KEY=base64:...
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

BKASH_SANDBOX=false
BKASH_APP_KEY=...
BKASH_APP_SECRET=...
BKASH_USERNAME=...
BKASH_PASSWORD=...

MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@example.com
```

### Key Configs

- `config/app.php` - Application settings
- `config/auth.php` - Auth guards/providers
- `config/database.php` - DB connections
- `config/livewire.php` - Livewire settings
- `config/bkash.php` - Payment gateway
- `config/session.php` - Session storage (database)
- `config/cache.php` - Cache storage (database)

---

## Security Considerations

### Implemented

- Email verification requirement
- CSRF protection (Laravel built-in)
- Password hashing (Bcrypt, 12 rounds)
- Signed URLs for email verification
- Rate limiting (6 requests/minute on verification)
- Session storage in database
- POST-only logout route

### Best Practices

- Always check authentication: `auth()->check()`, `auth()->id()`
- Always validate input: `$this->validate()`
- Always use signed URLs for sensitive actions
- Always check ownership before editing: `$user->id === auth()->id()`
- Always check connection status before showing private data

### Potential Improvements

- Add rate limiting on connection requests
- Implement activity logging
- Add two-factor authentication
- Implement image moderation for uploads
- Add spam detection for profiles

---

## Development Workflow

### Common Commands

```bash
# Development
composer dev              # Run serve + queue + pail + npm dev
php artisan serve         # Start dev server
php artisan queue:listen  # Process queued jobs
php artisan pail          # Tail logs

# Database
php artisan migrate       # Run migrations
php artisan migrate:fresh # Fresh migration (destructive)
php artisan tinker        # Laravel REPL

# Frontend
npm run dev               # Vite dev server
npm run build             # Production build

# Code Quality
./vendor/bin/pint         # Format code (Laravel Pint)
./vendor/bin/pest         # Run tests
```

### Development Setup

1. Clone repository
2. `composer install`
3. `npm install`
4. Copy `.env.example` to `.env`
5. `php artisan key:generate`
6. Configure database in `.env`
7. `php artisan migrate`
8. `php artisan storage:link`
9. `npm run dev` (in one terminal)
10. `php artisan serve` (in another terminal)

---

## Filament 4 Admin Panel

### Overview
The application includes **Filament 4.0** - a modern admin panel built on top of Laravel and Livewire. This provides a powerful, customizable interface for managing the application's data and users.

### Configuration

**Panel Provider:** `app/Providers/Filament/AdminPanelProvider.php`

```php
- Panel ID: 'admin'
- Path: '/admin'
- Login: Required (uses existing User authentication)
- Primary Color: Pink (matches brand color #ec4899)
- Default Panel: Yes
```

**Features:**
- Auto-discovers Resources in `app/Filament/Resources`
- Auto-discovers Pages in `app/Filament/Pages`
- Auto-discovers Widgets in `app/Filament/Widgets`
- Includes default Dashboard page
- Default widgets removed - using custom widgets only

### Dashboard Widgets

**StatsOverview Widget** (`app/Filament/Widgets/StatsOverview.php`)
Displays 9 key metrics with trends and mini charts:
1. **Total Users** - Total non-admin users with daily trend
2. **Total Revenue** - Cumulative revenue from completed purchases
3. **Completed Purchases** - Total successful transactions
4. **Pending Payments** - Payments awaiting completion
5. **Active Packages** - Currently available packages
6. **Connections Distributed** - Total profile views available
7. **Verified Profiles** - Percentage with profile_verified_at
8. **Unverified Profiles** - Clickable link to unverified profiles list
9. **Today's Revenue** - Sales made today

**UserGrowthChart Widget** (`app/Filament/Widgets/UserGrowthChart.php`)
- Line chart showing daily new user registrations
- Blue gradient styling
- Filter dropdown: 7/14/30/90 days
- Sort order: 2 (displays alongside revenue chart)

**RevenueChart Widget** (`app/Filament/Widgets/RevenueChart.php`)
- Line chart showing daily revenue from completed purchases
- Pink gradient styling (matches brand)
- Filter dropdown: 7/14/30/90 days
- Sort order: 3

**LatestPurchases Widget** (`app/Filament/Widgets/LatestPurchases.php`)
- Table showing last 10 purchases
- Columns: Date, Customer, Package, Amount, Connections, Stage, Applied status, Transaction ID
- Payment stage badges with color coding
- Sort order: 4
- Full width display

### Access
- **URL:** `http://localhost/admin` (or your-domain.com/admin)
- **Authentication:** Uses the same User model as the main application
- **Middleware:** Full Laravel middleware stack including authentication

### Creating Resources

To create a Filament resource for managing data:

```bash
# Create a resource for a model
php artisan make:filament-resource ModelName

# Create with pages
php artisan make:filament-resource ModelName --generate

# Create with soft deletes support
php artisan make:filament-resource ModelName --soft-deletes
```

**Example - Creating a Package resource:**
```bash
php artisan make:filament-resource Package --generate
```

This will create:
- `app/Filament/Resources/PackageResource.php` - Main resource class
- `app/Filament/Resources/PackageResource/Pages/` - CRUD pages
  - `ListPackages.php`
  - `CreatePackage.php`
  - `EditPackage.php`

### Customization

**Colors:**
Currently set to Amber. To change:
```php
// In AdminPanelProvider.php
->colors([
    'primary' => Color::Rose, // Or any other color
])
```

**Adding Custom Pages:**
```bash
php artisan make:filament-page PageName
```

**Adding Widgets:**
```bash
php artisan make:filament-widget WidgetName
```

### Implemented Resources

**1. UserResource** (`app/Filament/Resources/UserResource.php`)
- Complete user management interface
- Tabs: All, Profile Verified, Profile Not Verified, Admins
- Filters:
  - Email verification status (ternary filter)
  - Profile verification status (ternary filter)
  - Admin status (ternary filter)
- Form fields: Name, email, password, is_admin, email_verified_at, profile_verified_at
- Table columns: Name, email, verification status, admin status, created date
- Actions: View, Edit, Delete
- Bulk actions: Delete selected

**2. PackageResource** (`app/Filament/Resources/PackageResource.php`)
- Package management interface
- Form fields: Name, description, connections, price, is_popular, is_active
- Table columns: Name, connections, price, popular badge, active status
- Filters: Active packages, popular packages
- Actions: Create, Edit, Delete
- Bulk toggle: Activate/deactivate packages

**3. PurchaseResource** (`app/Filament/Resources/PurchaseResource.php`)
- Purchase monitoring and manual intervention
- Form fields: User, package, amount, payment_method, payment_stage, transaction_id, connections_purchased, connections_applied, is_refunded
- Table columns: Invoice number, user, package, amount, connections, payment stage, connections applied, transaction ID, date
- Filters:
  - Payment stage (completed, pending, failed, refunded, initiated, cancelled)
  - Connections applied (yes/no)
  - Refunded status (yes/no)
- **Manual Admin Actions:**
  - **Apply Connections** - Manually apply connections for completed payments
    - Only visible if payment is completed and connections not yet applied
    - Triggers `applyConnections()` method
    - Shows confirmation dialog
    - Sends success/warning notification
  - **Mark as Completed** - Manually mark payment as completed
    - Form with optional transaction ID input
    - Updates payment_stage to 'completed'
    - Sets payment_completed_at timestamp
    - Does NOT auto-apply connections (use separate action)
  - **Mark as Failed** - Manually mark payment as failed
    - Form with required error message input
    - Updates payment_stage to 'failed'
    - Stores error_message
- Use case: System failures, payment reconciliation, manual verification

**4. SettingResource** (`app/Filament/Resources/SettingResource.php`)
- Application settings management
- Form fields: Key, value, type (text/number/textarea), group, description
- Table columns: Key, value, type, group
- Filters: Group (stats, contact, social)
- Default groups: Statistics, Contact Information, Social Media
- Auto-cache clearing on save/delete

**5. FaqResource** (`app/Filament/Resources/Faqs/FaqResource.php`)
- FAQ management
- Form fields: Question, answer (rich text editor)
- Table columns: Question, answer preview
- Actions: Create, Edit, Delete

### Additional Resources to Consider

1. **Connection Moderation**
   - Monitor connection requests
   - Handle reported users
   - View connection analytics

2. **Profile Verification Queue**
   - Verify NID, student ID
   - Approve/reject profiles
   - Batch verification actions

### Integration Notes

- Filament uses the same authentication system as the main app
- Admin users are regular Users with potential role/permission checks
- No separate admin table needed
- Can add policies and authorization via Laravel's built-in Gate system

### Documentation
- **Official Docs:** https://filamentphp.com/docs/4.x
- **Version:** 4.0 (Latest stable)
- **Built on:** Laravel 12 + Livewire 3

---

## Settings System

### Overview
A configurable settings system that allows administrators to manage application-wide settings through the Filament admin panel. Settings are cached for performance and automatically clear when updated.

### Model Structure

**Setting Model (`app/Models/Setting.php`):**
```php
protected $fillable = ['key', 'value', 'type', 'group', 'description'];

// Static methods for easy access
Setting::get('key', 'default_value')
Setting::set('key', 'value', 'type', 'group', 'description')
```

### Auto-Cache Clearing

The Setting model includes automatic cache invalidation when settings are updated:
```php
protected static function booted(): void {
    static::saved(function (Setting $setting) {
        Cache::forget("setting.{$setting->key}");
    });
    static::deleted(function (Setting $setting) {
        Cache::forget("setting.{$setting->key}");
    });
}
```

### Filament Resource

**Location:** `app/Filament/Resources/SettingResource.php`

**Features:**
- Text, number, textarea input types
- Group organization (stats, contact, social)
- Description field for admin reference
- Navigation group: "System Settings"

### Default Settings (SettingsSeeder)

**Statistics:**
- `stats.total_reviews` - Total number of reviews (1200)
- `stats.review_average` - Average rating (4.7)
- `stats.total_marriages` - Total successful marriages (1600)

**Contact Information:**
- `contact.address` - Physical address
- `contact.phone` - Contact phone number
- `contact.email` - Contact email
- `contact.whatsapp` - WhatsApp number

**Social Media:**
- `social.facebook` - Facebook page URL
- `social.instagram` - Instagram profile URL

### Usage in Views

Settings are used throughout the application with caching:
```php
// In welcome.blade.php
Setting::get('stats.total_marriages', '1600')
Setting::get('contact.phone', '+8809611489040')
Setting::get('social.facebook', 'https://www.facebook.com/...')
```

### Files
- Model: `app/Models/Setting.php`
- Migration: `database/migrations/YYYY_MM_DD_create_settings_table.php`
- Seeder: `database/seeders/SettingsSeeder.php`
- Filament Resource: `app/Filament/Resources/SettingResource.php`

---

## UI/UX Design System

### Custom Components

**Select Input Component (`resources/views/components/select-input.blade.php`):**
- Reusable dropdown with icon support
- Consistent styling across the application
- Hover and focus states
- Custom pink theme integration

**Usage:**
```php
<x-select-input
    wireModel="field_name"
    placeholder="Select Option"
    :options="['value' => 'Label', ...]"
    :icon="'<svg>...</svg>'"
/>
```

### Profile Components Design Pattern

All profile components follow a consistent modern design:

**Card Structure:**
```php
- Border: border-gray-200 rounded-xl
- Shadow: shadow-md hover:shadow-lg
- Header: bg-custom-red p-4
- Title: font-bold text-white text-lg
- Content: p-6 bg-white grid md:grid-cols-2 gap-6
```

**Button Styles:**
- Edit: `bg-custom-pink hover:bg-pink-600`
- Save: `bg-green-500 hover:bg-green-600`
- Show/Hide: `bg-custom-pink` with eye emojis (👁️ Hide / 👁️‍🗨️ Show)
- All buttons: `px-4 py-2 rounded-lg font-semibold transform hover:scale-105`

**Input Styling:**
```css
- Default: border-2 border-gray-200 rounded-lg
- Focus: focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20
- Padding: p-3
- Transitions: transition-all duration-300
```

**Label/Value Pattern:**
```php
- Labels: text-gray-600 text-xs font-semibold uppercase mb-2
- Values: text-gray-900 font-medium
- Empty state: "-"
```

### Authentication Pages Design

**Login Page (`resources/views/livewire/auth/login.blade.php`):**
- Split layout: Image (2/5) + Form (3/5)
- Gradient background on image side
- Modern card design with shadow-2xl
- Labeled inputs with proper accessibility
- Enhanced button with hover scale effect
- Responsive: Stack vertically on mobile

**Registration Page (`resources/views/livewire/auth/register.blade.php`):**
- Three organized sections:
  1. Personal Information
  2. Verification Details (NID, Student ID, University)
  3. Account Credentials
- Custom select components for Gender and Religion
- Scrollable form with max-height
- Section headers with border dividers
- 2-column grid on desktop, single column on mobile

**Both Login and Register Pages:**
- "Go Back Home" button at the bottom for easy navigation to home page
- CAPTCHA verification with reload functionality

### Error Pages Design

Custom error pages that maintain brand consistency while providing helpful user guidance.

**Design Pattern (All Error Pages):**
```php
- Full viewport height with centered content
- Gradient background: from-custom-pink/10 to-custom-red/10
- White card with rounded-2xl borders and shadow-2xl
- Responsive padding (p-4 on mobile, p-8 to p-12 on card)
- Large error code display (text-9xl to text-[12rem])
- SVG icon illustrations (48x48, semi-transparent brand colors)
- Action buttons with hover effects and transitions
- Contact support link in footer section
```

**404 - Page Not Found (`resources/views/errors/404.blade.php`):**
- Pink error code (text-custom-pink)
- Exclamation circle icon
- "Go Back Home" primary button
- "View My Profile" secondary button (authenticated users only)

**403 - Access Forbidden (`resources/views/errors/403.blade.php`):**
- Red error code (text-custom-red)
- Lock icon
- Displays custom exception message if available
- "Go Back Home" and "Login" buttons (or "View My Profile" if authenticated)

**500 - Server Error (`resources/views/errors/500.blade.php`):**
- Red error code (text-custom-red)
- Warning triangle icon
- User-friendly message about temporary issues
- "Try Again" button with JavaScript reload
- "Go Back Home" button

**419 - Page Expired (`resources/views/errors/419.blade.php`):**
- Pink error code (text-custom-pink)
- Clock icon
- Explains CSRF token expiration
- "Refresh Page" primary button
- "Go Back Home" secondary button

### Responsive Design

**Breakpoints:**
- Mobile: Base styles (1 column)
- Tablet (md: 768px): 2 columns
- Desktop (lg: 1024px): 3-4 columns

**Search Page:**
- Filters: 1 → 2 → 4 columns
- Profile cards: 1 → 2 → 3 columns
- Advanced filters: Collapsible with smooth animation

**Profile Components:**
- All use `md:grid-cols-2` pattern
- Show/Hide toggles for privacy
- Edit mode with inline validation

---

## Admin User System

### Admin Access Control

**Admin Identification:**
- Field: `is_admin` (boolean) in `users` table
- Method: `canAccessPanel(Panel $panel): bool`
- Returns: `$this->is_admin`

### Admin Profile Exclusion

Admin users are completely hidden from regular users across the entire application:

**Search Results (`search.blade.php`):**
```php
User::query()->where('is_admin', false)
```

**Profile Pages (`profile.blade.php`):**
```php
User::where('is_admin', false)->findOrFail($profileId)
// Returns 404 if trying to view admin profile
```

**Welcome Page Statistics (`welcome.blade.php`):**
```php
// All three statistics exclude admins
User::where('is_admin', false)->whereHas('basicInfo')->count()
```

**Connection History (`connection-history.blade.php`):**
```php
User::where('is_admin', false)->with('basicInfo')->find($userId)
```

### Benefits

1. **Privacy:** Admins don't appear as matchmaking candidates
2. **Security:** Admin profiles cannot be viewed via direct URLs
3. **Accuracy:** Statistics only count real users
4. **Clean UX:** Users only see relevant profiles

### Files
- User Model: `app/Models/User.php` (canAccessPanel method)
- Migration: Includes `is_admin` boolean field
- Applied in: search.blade.php, profile.blade.php, welcome.blade.php, connection-history.blade.php

---

## Recent Development Focus

Based on recent commits and updates:

### Phase 1: Core Systems (Completed)
1. **Package System** - Connection packages with pricing tiers
2. **Payment History** - Track all purchases and transactions
3. **Connection History** - View all accepted connections
4. **Invoice Emails** - Automated email invoices after purchase
5. **Connections Management** - State handling and profile display
6. **Notification System** - CRUD operations and UI integration
7. **Enhanced Registration** - Added NID, student ID, university fields
8. **Identity Verification** - NID verification system

### Phase 2: Settings & Configuration (Completed)
9. **Settings System** - Configurable application settings via Filament admin panel
   - Statistics (total reviews, review average, total marriages)
   - Contact information (address, phone, email, WhatsApp)
   - Social media links (Facebook, Instagram)
   - Auto-cache clearing on updates
   - Integration in welcome page

### Phase 3: UI/UX Modernization (Completed)
10. **Profile Components Redesign** - All 13 profile components updated with:
    - Consistent modern card design
    - Improved button styling (Edit, Save, Show/Hide)
    - Better input focus states
    - Responsive grid layouts
    - Enhanced visual hierarchy

11. **Authentication Pages Redesign** - Modern login and registration pages:
    - Split layout with gradient backgrounds
    - Labeled inputs with accessibility
    - Custom select components with CAPTCHA verification
    - Organized registration sections
    - Mobile-responsive design

12. **Custom Components** - Created reusable UI components:
    - Select input component with icon support
    - Consistent styling system
    - Hover and focus states

13. **Profile Data Management** - Enhanced basic info component:
    - Added editable NID field
    - Added editable Student ID field
    - Added editable University field
    - Validation rules included

### Phase 4: Admin & Security (Completed)
14. **Admin Profile Exclusion** - Complete isolation of admin users:
    - Hidden from search results
    - Cannot view admin profiles directly (404)
    - Excluded from statistics
    - Not shown in connection history
    - Maintained admin panel access

### Phase 5: Payment Tracking & Admin Controls (Latest - Completed)
15. **Comprehensive Payment Stage Tracking:**
    - Multi-stage payment lifecycle (initiated, pending, completed, failed, refunded, cancelled)
    - Purchase model with stage constants and query scopes
    - Timestamp tracking (payment_initiated_at, payment_completed_at)
    - Error message storage for failed payments
    - Refund tracking (is_refunded, refunded_at, refund_transaction_id, refund_amount)
    - Connection application tracking (connections_applied, connections_applied_at)
    - Safe applyConnections() method with duplicate prevention

16. **Admin Manual Payment Controls:**
    - PurchaseResource with comprehensive filters (stage, connections applied, refunded)
    - Three manual intervention actions:
      - Apply Connections (for completed payments without connections)
      - Mark as Completed (with optional transaction ID)
      - Mark as Failed (with required error message)
    - Use case: System failures, payment reconciliation, manual verification

17. **Dashboard Analytics Widgets:**
    - **StatsOverview** - 9 key metrics with trends and mini charts
      - Total Users, Total Revenue, Completed Purchases
      - Pending Payments, Active Packages, Connections Distributed
      - Verified/Unverified Profiles, Today's Revenue
    - **UserGrowthChart** - Line chart with 7/14/30/90 day filters
    - **RevenueChart** - Line chart with 7/14/30/90 day filters
    - **LatestPurchases** - Table widget showing last 10 purchases
    - Removed default Filament widgets (AccountWidget, FilamentInfoWidget)

18. **Brand Consistency:**
    - Admin panel primary color changed from Amber to Pink (#ec4899)
    - Matches brand's custom-pink color
    - Applied across all admin UI elements

19. **Critical Bug Fixes:**
    - Fixed "Database connection [X] not configured" error
    - Root cause: `connection` field conflicting with Eloquent's reserved `connection` property
    - Solution: Use getAttribute/setAttribute instead of direct property access
    - Applied fix to Purchase model and CheckConnection middleware
    - Added integer casting to Connection model

### Phase 6: Content Management (Completed)
20. **FAQ System:**
    - FaqResource for managing frequently asked questions
    - Rich text editor for answers
    - About page with Livewire component

21. **Custom Error Pages:**
    - Professional error pages matching brand design
    - **404.blade.php** - Page Not Found
      - Large pink error code, friendly message
      - "Go Back Home" and "View My Profile" buttons
      - Contact support link
    - **403.blade.php** - Access Forbidden
      - Red error code with lock icon
      - Shows custom exception message
      - Login button for guests, profile button for authenticated users
    - **500.blade.php** - Server Error
      - Red error code with warning icon
      - User-friendly message about server issues
      - "Try Again" button with page reload functionality
    - **419.blade.php** - Page Expired (CSRF Token)
      - Pink error code with clock icon
      - Explains session expiration
      - "Refresh Page" button
    - All error pages feature:
      - Gradient backgrounds (from-custom-pink/10 to-custom-red/10)
      - Rounded cards with shadows
      - Responsive layouts
      - Hover effects and transitions
      - SVG icons for visual appeal
      - Brand-consistent pink/red color scheme

### Files Updated in Latest Development
- **Profile Components:** 13 files in `resources/views/livewire/profile/`
  - family.blade.php, parmanent.blade.php, lifestyle.blade.php
  - personal_attitude.blade.php, hobby.blade.php, language.blade.php
  - education.blade.php, present_address.blade.php
  - introduction.blade.php, basic_info.blade.php
  - physical_attr.blade.php, spiritual.blade.php, partner.blade.php

- **Authentication:** 2 files
  - login.blade.php (with "Go Back Home" button)
  - register.blade.php (with "Go Back Home" button)

- **Error Pages:** 4 files in `resources/views/errors/`
  - 404.blade.php (Page Not Found)
  - 403.blade.php (Access Forbidden)
  - 500.blade.php (Server Error)
  - 419.blade.php (Page Expired/CSRF)

- **System Files:** 5 files
  - search.blade.php (admin exclusion)
  - profile.blade.php (admin exclusion)
  - welcome.blade.php (admin exclusion, settings integration)
  - connection-history.blade.php (admin exclusion)
  - Setting.php model (auto-cache clearing)

- **Components:** 1 file
  - select-input.blade.php (custom dropdown component)

---

## Common Tasks & How to Implement

### Adding a New Profile Section

1. **Create migration:**
   ```bash
   php artisan make:migration create_new_section_table
   ```

2. **Define schema:**
   ```php
   Schema::create('new_section', function (Blueprint $table) {
       $table->id();
       $table->foreignId('user_id')->constrained()->cascadeOnDelete();
       $table->boolean('is_shown')->default(true);
       // Add fields
       $table->timestamps();
   });
   ```

3. **Create model:**
   ```php
   class NewSection extends Model {
       protected $fillable = ['user_id', 'field1', 'field2'];
       public function user() {
           return $this->belongsTo(User::class);
       }
   }
   ```

4. **Add relationship to User:**
   ```php
   public function newSection(): HasOne {
       return $this->hasOne(NewSection::class);
   }
   ```

5. **Create Volt component:**
   ```bash
   # Create resources/views/livewire/profile/new-section.blade.php
   ```

6. **Add to profile view** - Include in profile.blade.php

### Adding a New Route

1. **Define route in `routes/web.php`:**
   ```php
   Route::get('/new-route', function () {
       return view('livewire.new-component');
   })->middleware(['auth', 'verified']);
   ```

2. **Create Volt component:**
   ```bash
   # Create resources/views/livewire/new-component.blade.php
   ```

### Modifying Connection Logic

**Location:** `app/Models/User.php`

**Key methods to modify:**
- `sendConnectionRequest()`
- `isConnected()`
- `isConnectionPending()`

### Customizing Payment Flow

**Files to modify:**
- `app/Http/Controllers/PaymentController.php` - Initiation
- `app/Http/Controllers/BkashController.php` - Callback handling
- `config/bkash.php` - Configuration

---

## Troubleshooting Guide

### Common Issues

**Issue:** Email verification not working
**Solution:** Check `MAIL_MAILER` in `.env`, verify `APP_URL` is correct

**Issue:** Images not displaying
**Solution:** Run `php artisan storage:link`

**Issue:** Payment failing
**Solution:** Verify bKash credentials, check `BKASH_SANDBOX` setting

**Issue:** Connection count not updating
**Solution:** Check `connections` table, verify callback is being hit

**Issue:** Livewire not updating
**Solution:** Clear cache with `php artisan cache:clear`, check browser console

**Issue:** "Database connection [X] not configured" error
**Solution:** This occurs when using `$model->connection` where `connection` is both a field name and Eloquent's reserved property for database connection. Use `getAttribute('connection')` and `setAttribute('connection', $value)` instead of direct property access. Also add integer casting in model: `protected $casts = ['connection' => 'integer'];`

**Issue:** Payment stage not updating correctly
**Solution:** Ensure payment flow follows the correct sequence: initiated → pending → completed. Check BkashController callback is receiving transactionStatus. Verify applyConnections() is only called for completed payments.

**Issue:** Dashboard widgets not displaying
**Solution:** Run `php artisan optimize:clear` to clear all caches. Verify widget sort orders are set correctly. Check that default widgets are removed from AdminPanelProvider.

---

## Key Learnings & Best Practices

### Payment Processing
1. **Always use stage tracking** - Multi-stage payment lifecycle prevents race conditions and allows recovery from failures
2. **Prevent duplicate connection application** - Check `connections_applied` flag before adding connections to user account
3. **Provide admin manual controls** - System failures happen; admins need ability to manually reconcile payments
4. **Track timestamps** - payment_initiated_at, payment_completed_at help with debugging and reconciliation
5. **Store error messages** - Failed payments should record error_message for troubleshooting

### Database Reserved Properties
1. **Avoid field names that conflict with Eloquent properties:**
   - `connection` → conflicts with database connection property
   - `attributes` → conflicts with model attributes array
   - `relations` → conflicts with loaded relationships
2. **When conflicts unavoidable:**
   - Use `getAttribute('field_name')` for reading
   - Use `setAttribute('field_name', $value)` for writing
   - Add explicit type casting in model's `$casts` array
3. **Example from Connection model:**
   ```php
   // In model
   protected $casts = ['connection' => 'integer'];

   // In usage
   $currentCount = (int) $userConnection->getAttribute('connection');
   $userConnection->setAttribute('connection', $currentCount + 1);
   ```

### Filament Admin Panel
1. **Custom widgets over default widgets** - Remove default widgets to reduce clutter
2. **Use widget sort orders** - Control layout with `protected static ?int $sort`
3. **Provide manual actions for critical operations** - Apply Connections, Mark Completed, Mark Failed
4. **Use confirmation modals** - Prevent accidental actions with `->requiresConfirmation()`
5. **Show relevant actions only** - Use `->visible(fn () => ...)` to hide actions when not applicable
6. **Brand consistency** - Match admin panel color to brand identity
7. **Filter by status** - Allow admins to quickly find records by stage, applied status, etc.

### Dashboard Analytics
1. **Show trends, not just numbers** - Daily comparison helps identify growth/decline
2. **Provide time range filters** - 7/14/30/90 days allows different perspectives
3. **Make metrics actionable** - Clickable stats that link to filtered lists
4. **Exclude admin data** - Statistics should only count real users
5. **Use appropriate chart types** - Line charts for trends, tables for recent activity

### Cache Management
1. **Auto-clear caches on updates** - Use model events (saved, deleted) to invalidate cache
2. **Clear all caches after major changes** - `php artisan optimize:clear`
3. **Cache expensive queries** - Use `Cache::remember()` for settings and statistics

### Security & Privacy
1. **Admin profile isolation** - Always exclude is_admin=true from user-facing queries
2. **404 instead of 403** - Don't reveal existence of admin profiles
3. **Validate payment status** - Only apply connections for transactionStatus === 'Completed'
4. **Use transactions for critical operations** - Wrap connection application in DB transactions

---

## File Locations Reference

### Models
- User: `app/Models/User.php`
- Profile models: `app/Models/*.php`
- Package: `app/Models/Package.php`
- Purchase: `app/Models/Purchase.php`

### Controllers
- Payment: `app/Http/Controllers/PaymentController.php`
- bKash: `app/Http/Controllers/BkashController.php`

### Middleware
- CheckConnection: `app/Http/Middleware/CheckConnection.php`
  - Tracks profile visits in VisitedProfile model
  - Decrements connection count when viewing profiles
  - Uses getAttribute/setAttribute to avoid DB connection conflicts
  - Redirects to packages page when connections exhausted

### Routes
- Main: `routes/web.php`
- Auth: `routes/auth.php`
- Admin: `/admin` (Filament auto-generated)

### Views
- Layouts: `resources/views/components/layouts/*.blade.php`
- Auth: `resources/views/livewire/auth/*.blade.php` (includes "Go Back Home" buttons)
- Profile: `resources/views/livewire/profile/*.blade.php`
- Settings: `resources/views/livewire/settings/*.blade.php`
- Packages: `resources/views/livewire/packages.blade.php`
- Payment History: `resources/views/livewire/payment-history.blade.php`
- Connection History: `resources/views/livewire/connection-history.blade.php`
- Emails: `resources/views/emails/invoice.blade.php`
- Error Pages: `resources/views/errors/`
  - 404.blade.php (Page Not Found - pink theme)
  - 403.blade.php (Access Forbidden - red theme with lock icon)
  - 500.blade.php (Server Error - red theme with warning icon)
  - 419.blade.php (Page Expired - pink theme with clock icon)

### Mail
- InvoiceMail: `app/Mail/InvoiceMail.php`

### Migrations
- All migrations: `database/migrations/*.php`

### Seeders
- PackageSeeder: `database/seeders/PackageSeeder.php`

### Filament (Admin Panel)
- Admin Provider: `app/Providers/Filament/AdminPanelProvider.php`
- Resources: `app/Filament/Resources/`
  - UserResource.php (with profile verification tabs)
  - PackageResource.php (with popular/active filters)
  - PurchaseResource.php (with manual admin actions)
  - SettingResource.php (with auto-cache clearing)
  - Faqs/FaqResource.php (with rich text editor)
- Pages: `app/Filament/Pages/` (auto-generated by resources)
- Widgets: `app/Filament/Widgets/`
  - StatsOverview.php (9 key metrics with trends)
  - UserGrowthChart.php (with 7/14/30/90 day filters)
  - RevenueChart.php (with 7/14/30/90 day filters)
  - LatestPurchases.php (table widget, last 10 purchases)

### Config
- App: `config/app.php`
- Database: `config/database.php`
- bKash: `config/bkash.php`
- Filament: Auto-configured via AdminPanelProvider

### Assets
- CSS: `resources/css/app.css`
- JS: `resources/js/app.js`
- Uploaded photos: `storage/app/public/photos/`

---

## Summary for LLMs

This is a **Laravel 12 matrimony platform** using **Livewire Volt** for the frontend and **Filament 4** for the admin panel. The architecture emphasizes:

1. **Atomic Profile Design** - 18 separate models for profile sections
2. **Smart Connection System** - Mutual requests auto-accept, paid profile views
3. **Package-based Monetization** - Tiered connection packages with bKash payment
4. **Transaction Tracking** - Complete payment and connection history
5. **Privacy Controls** - Per-section visibility settings
6. **Minimal Controllers** - Volt components handle most logic
7. **Admin Panel** - Filament 4 for data management
8. **Database-driven Sessions/Cache** - Scalable architecture

**When working on this project:**
- Use Volt component pattern for new frontend features
- Use Filament resources for admin CRUD operations
- Follow atomic model design for new profile sections
- Always check authentication and connection status
- Maintain privacy control patterns
- Use computed properties for heavy queries
- Follow Laravel naming conventions
- Create Filament resources for managing system data

**Key Integration Points:**
- Payment flow: Package selection → `PaymentController` → bKash → `BkashController` → Purchase record → Invoice email
- Connection flow: `User` model methods → `connected` pivot → `CheckConnection` middleware
- Profile access: Authentication → Connection check → Privacy settings
- Notifications: Triggered in connection methods → displayed in navbar
- Admin panel: `/admin` → Filament resources → Model CRUD

**Recent Additions (Latest Updates):**
- **Payment Stage Tracking:** Comprehensive multi-stage payment lifecycle with timestamps
- **Admin Manual Controls:** Three manual actions for payment intervention (Apply Connections, Mark Completed, Mark Failed)
- **Dashboard Analytics:** 4 custom widgets (StatsOverview, UserGrowthChart, RevenueChart, LatestPurchases)
- **Brand Consistency:** Admin panel primary color changed to Pink (#ec4899)
- **Critical Bug Fix:** Resolved "Database connection [X] not configured" error using getAttribute/setAttribute
- **Custom Error Pages:** Professional 404, 403, 500, 419 pages with brand-consistent design
- **Settings System:** Configurable app settings via Filament with auto-cache clearing
- **UI/UX Overhaul:** All 13 profile components + auth pages modernized with CAPTCHA
- **Custom Components:** Reusable select-input component with icon support
- **Admin Exclusion:** Complete isolation of admin profiles from regular users
- **Enhanced Profile Management:** NID, Student ID, University now editable
- **Filament Resources:** 5 fully configured resources (User, Package, Purchase, Setting, FAQ)
- **Package system:** 4 default tiers (Starter, Popular, Premium, Ultimate)
- **Purchase tracking:** Transaction IDs, invoice numbers, and stage tracking
- **Payment history:** Statistics and detailed records with stage filtering
- **Connection history:** Profile cards and status tracking
- **Invoice automation:** Email invoices after successful payment
- **FAQ System:** FaqResource and about page
- **Navigation:** "Go Back Home" buttons on login and register pages

**Design System Standards:**
- Consistent card design with rounded-xl borders and shadow effects
- Custom color scheme (custom-pink, custom-red, maroon)
- Responsive grid layouts (1 → 2 → 3/4 columns)
- Modern button styles with hover effects
- Focus states with custom-pink ring
- Privacy controls per profile section
- Mobile-first responsive approach

This platform is production-ready with room for enhancement in:
- Creating additional Filament resources (Users, Purchases, Packages)
- Implementing advanced role-based access control
- Adding automated profile moderation
- Integrating additional payment gateways
- Implementing real-time chat functionality
- Adding advanced matching algorithms
- Enhanced security features (2FA, activity logs)
