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

### System Models (3 models)

| Model | Key Fields | Purpose |
|-------|-----------|---------|
| **Package** | name, description, connections, price, is_popular, is_active | Connection packages for purchase |
| **Purchase** | user_id, package_id, amount, transaction_id, payment_id, invoice_number, payment_method, status, connections_purchased, payment_response | Payment/purchase records |
| **Setting** | key, value, type, group, description | Configurable application settings managed via Filament admin panel |

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

### 7. Payment Integration (bKash)

**Flow:**
```
User initiates payment
    ↓
PaymentController creates bKash transaction
    ↓
Redirect to bKash
    ↓
User completes payment
    ↓
bKash callback to /bkash/callback
    ↓
BkashController verifies payment
    ↓
On success: increment connection_count by 3
    ↓
Redirect to /bkash/success or /bkash/failed
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
- Controller: `app/Http/Controllers/BkashController.php`
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
- Primary Color: Amber
- Default Panel: Yes
```

**Features:**
- Auto-discovers Resources in `app/Filament/Resources`
- Auto-discovers Pages in `app/Filament/Pages`
- Auto-discovers Widgets in `app/Filament/Widgets`
- Includes default Dashboard page
- Includes AccountWidget and FilamentInfoWidget

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

### Recommended Resources to Create

For this matrimony platform, consider creating Filament resources for:

1. **User Management**
   - `php artisan make:filament-resource User --generate`
   - Manage users, verify emails, moderate profiles

2. **Package Management**
   - `php artisan make:filament-resource Package --generate`
   - Create/edit packages, set prices, mark as popular

3. **Purchase Monitoring**
   - `php artisan make:filament-resource Purchase --generate`
   - View all transactions, refunds, payment status

4. **Connection Moderation**
   - Monitor connection requests
   - Handle reported users

5. **Profile Verification**
   - Verify NID, student ID
   - Approve/reject profiles

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

### Phase 2: Settings & Configuration (Latest)
9. **Settings System** - Configurable application settings via Filament admin panel
   - Statistics (total reviews, review average, total marriages)
   - Contact information (address, phone, email, WhatsApp)
   - Social media links (Facebook, Instagram)
   - Auto-cache clearing on updates
   - Integration in welcome page

### Phase 3: UI/UX Modernization (Latest)
10. **Profile Components Redesign** - All 13 profile components updated with:
    - Consistent modern card design
    - Improved button styling (Edit, Save, Show/Hide)
    - Better input focus states
    - Responsive grid layouts
    - Enhanced visual hierarchy

11. **Authentication Pages Redesign** - Modern login and registration pages:
    - Split layout with gradient backgrounds
    - Labeled inputs with accessibility
    - Custom select components
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

### Phase 4: Admin & Security (Latest)
14. **Admin Profile Exclusion** - Complete isolation of admin users:
    - Hidden from search results
    - Cannot view admin profiles directly (404)
    - Excluded from statistics
    - Not shown in connection history
    - Maintained admin panel access

### Files Updated in Latest Development
- **Profile Components:** 13 files in `resources/views/livewire/profile/`
  - family.blade.php, parmanent.blade.php, lifestyle.blade.php
  - personal_attitude.blade.php, hobby.blade.php, language.blade.php
  - education.blade.php, present_address.blade.php
  - introduction.blade.php, basic_info.blade.php
  - physical_attr.blade.php, spiritual.blade.php, partner.blade.php

- **Authentication:** 2 files
  - login.blade.php
  - register.blade.php

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

### Routes
- Main: `routes/web.php`
- Auth: `routes/auth.php`
- Admin: `/admin` (Filament auto-generated)

### Views
- Layouts: `resources/views/components/layouts/*.blade.php`
- Auth: `resources/views/livewire/auth/*.blade.php`
- Profile: `resources/views/livewire/profile/*.blade.php`
- Settings: `resources/views/livewire/settings/*.blade.php`
- Packages: `resources/views/livewire/packages.blade.php`
- Payment History: `resources/views/livewire/payment-history.blade.php`
- Connection History: `resources/views/livewire/connection-history.blade.php`
- Emails: `resources/views/emails/invoice.blade.php`

### Mail
- InvoiceMail: `app/Mail/InvoiceMail.php`

### Migrations
- All migrations: `database/migrations/*.php`

### Seeders
- PackageSeeder: `database/seeders/PackageSeeder.php`

### Filament (Admin Panel)
- Admin Provider: `app/Providers/Filament/AdminPanelProvider.php`
- Resources: `app/Filament/Resources/` (to be created)
- Pages: `app/Filament/Pages/` (to be created)
- Widgets: `app/Filament/Widgets/` (to be created)

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
- **Settings System:** Configurable app settings via Filament with auto-cache clearing
- **UI/UX Overhaul:** All 13 profile components + auth pages modernized
- **Custom Components:** Reusable select-input component with consistent styling
- **Admin Exclusion:** Complete isolation of admin profiles from regular users
- **Enhanced Profile Management:** NID, Student ID, University now editable
- **Package system:** 4 default tiers (Starter, Popular, Premium, Ultimate)
- **Purchase tracking:** Transaction IDs and invoice numbers
- **Payment history:** Statistics and detailed records
- **Connection history:** Profile cards and status tracking
- **Invoice automation:** Email invoices after successful payment
- **Filament 4 admin panel:** With Settings resource configured

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
