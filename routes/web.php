<?php

use App\Livewire\Users\Editor;
use App\Livewire\Users\Index;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', fn () => view('dashboard', [
        'totalUsers' => User::count(),
        'usersToday' => User::whereDate('created_at', today())->count(),
    ]))->name('dashboard');

    Route::livewire('users', Index::class)->name('users.index');
    Route::livewire('users/create', Editor::class)->name('users.create');
    Route::livewire('users/{user}/edit', Editor::class)->name('users.edit');
});

require __DIR__.'/settings.php';
