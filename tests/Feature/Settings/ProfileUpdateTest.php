<?php

use App\Livewire\Settings\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs($this->user = User::factory()->create(['password' => 'password']));
});

test('profile page is displayed and old settings urls redirect to it', function () {
    $this->get('/profile/edit')->assertOk();
    $this->get('/settings/profile')->assertRedirect('/profile/edit');
});

test('guests are redirected to login', function () {
    auth()->logout();

    $this->get(route('profile.edit'))->assertRedirect(route('login'));
});

test('name and email can be updated without touching the password', function () {
    Livewire::test(Profile::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('save')
        ->assertHasNoErrors();

    $this->user->refresh();

    expect($this->user->name)->toBe('Test User')
        ->and($this->user->email)->toBe('test@example.com')
        ->and(Hash::check('password', $this->user->password))->toBeTrue();
});

test('email must be unique but may stay the same', function () {
    $other = User::factory()->create();

    Livewire::test(Profile::class)
        ->set('email', $other->email)
        ->call('save')
        ->assertHasErrors(['email']);

    Livewire::test(Profile::class)
        ->call('save')
        ->assertHasNoErrors();
});

test('password can be updated', function () {
    Livewire::test(Profile::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('password', '');

    expect(Hash::check('new-password', $this->user->refresh()->password))->toBeTrue();
});

test('correct current password must be provided to update password', function () {
    Livewire::test(Profile::class)
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('save')
        ->assertHasErrors(['current_password']);

    Livewire::test(Profile::class)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('save')
        ->assertHasErrors(['current_password']);

    expect(Hash::check('password', $this->user->refresh()->password))->toBeTrue();
});

test('new password must be confirmed', function () {
    Livewire::test(Profile::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'different')
        ->call('save')
        ->assertHasErrors(['password']);
});
