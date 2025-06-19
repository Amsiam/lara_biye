<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Vendor\Bkash\BkashController;
use App\Http\Middleware\CheckConnection;
use Ihasan\Bkash\Facades\Bkash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'welcome')->name('home');

Volt::route('search', 'search')->name('search');

Route::get('dashboard', function () {
    return redirect()->route('profile', ['profileId' => auth()->user()->id]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('payment/{provider}', [PaymentController::class, 'processPayment'])->name('payment');

    Route::redirect('settings', 'settings/profile');
    Volt::route('profile/{profileId}', 'profile')->name('profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');


    Route::get('bkash/callback', [BkashController::class, 'callback'])->name('bkash.callback');
    Route::get('bkash/success', [BkashController::class, 'success'])->name('bkash.success');
    Route::get('bkash/failed', [BkashController::class, 'failed'])->name('bkash.failed');
});

Route::get('migrate', function () {
    Artisan::call('migrate');
})->name('migrate');

require __DIR__ . '/auth.php';
