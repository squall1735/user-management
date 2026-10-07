<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', fn () => view('dashboard', [
        'totalUsers' => User::count(),
        'usersToday' => User::whereDate('created_at', today())->count(),
    ]))->name('dashboard');
});

require __DIR__.'/settings.php';
