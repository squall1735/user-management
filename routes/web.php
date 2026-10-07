<?php

use App\Livewire\Settings\Profile;
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

    Route::livewire('profile/edit', Profile::class)->name('profile.edit');
    Route::redirect('settings/{any?}', '/profile/edit')->where('any', '.*');

    Route::livewire('users', Index::class)->name('users.index');
    Route::livewire('users/create', Editor::class)->name('users.create');
    Route::livewire('users/{user}/edit', Editor::class)->name('users.edit');
});
