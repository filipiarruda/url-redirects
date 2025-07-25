<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\RedirectUrlForm;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/redirects', RedirectUrlForm::class)->name('redirects.form');

Route::get('/r/{code}', [RedirectController::class, 'redirectUrl'])->name('redirects.redirect');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
