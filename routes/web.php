<?php

use App\Http\Middleware\CheckConnection;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'welcome')->name('home');

Volt::route('search', 'search')->name('search');

Route::get('dashboard', function () {
    return redirect()->route('profile', ['profileId' => auth()->user()->id]);
})
    ->middleware(['auth', 'verified', CheckConnection::class])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('profile/{profileId}', 'profile')->name('profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
