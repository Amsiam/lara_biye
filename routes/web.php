<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\Vendor\Bkash\BkashController;
use App\Http\Middleware\CheckConnection;
use App\Models\Notification;
use Ihasan\Bkash\Facades\Bkash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'welcome')->name('home');

Volt::route('search', 'search')->name('search');

Volt::route('contact', 'contact')->name('contact');

Volt::route('about', 'about')->name('about');

Volt::route('faq', 'faq')->name('faq');

// CAPTCHA image route
Route::get('captcha-image', [App\Http\Controllers\CaptchaController::class, 'generate'])->name('captcha.image');

// Protected profile image route
Route::get('profile-image/{userId}', [ProfileImageController::class, 'show'])->name('profile.image');

Route::get('dashboard', function () {
    return redirect()->route('profile', ['profileId' => auth()->user()->id]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Volt::route('packages', 'packages')->name('packages');
    Route::get('payment/{provider}/{package}', [PaymentController::class, 'processPayment'])->name('payment');

    Route::redirect('settings', 'settings/profile');
    Volt::route('profile/{profileId}', 'profile')->name('profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Volt::route('your-connections', 'connections')->name('your.connections');
    Volt::route('payment-history', 'payment-history')->name('payment.history');
    Volt::route('connection-history', 'connection-history')->name('connection.history');


    Route::get('bkash/callback', [BkashController::class, 'callback'])->name('bkash.callback');
    Route::get('bkash/success', [BkashController::class, 'success'])->name('bkash.success');
    Route::get('bkash/failed', [BkashController::class, 'failed'])->name('bkash.failed');


    //Notifications
    Route::get('markAsRead', function () {
        auth()->user()->notifications()->update(['read' => true]);
        return back();
    })->name('notifications.markAllAsRead');


    Route::get('markAsRead/{notification}', function (Notification $notification) {
        auth()->user()->notifications()->where('id', $notification->id)->update(['read' => true]);

        return redirect()->route('profile', ['profileId' => $notification->sender_id]);
    })->name('notifications.show');
});

Route::get('migrate', function () {
    Artisan::call('migrate');
})->name('migrate');

require __DIR__ . '/auth.php';
