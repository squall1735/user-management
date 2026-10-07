<?php

use App\Livewire\Users\Editor;
use App\Livewire\Users\Index;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs($this->admin = User::factory()->create());
});

test('guests are redirected to login', function (string $route) {
    auth()->logout();

    $this->get(route($route, $this->admin))->assertRedirect(route('login'));
})->with(['users.index', 'users.create', 'users.edit']);

test('users list shows five newest users per page', function () {
    User::factory(9)->create();

    Livewire::test(Index::class)
        ->assertViewHas('users', fn ($users) => $users->count() === 5 && $users->total() === 10);
});

test('users can be searched by name or email and the term resets the page', function () {
    User::factory(9)->create();
    User::factory()->create(['name' => 'Somchai Jaidee', 'email' => 'a@example.com']);
    User::factory()->create(['name' => 'Other', 'email' => 'findme@example.com']);

    Livewire::test(Index::class)
        ->call('gotoPage', 3)
        ->set('search', 'Somchai')
        ->assertSet('paginators.page', 1)
        ->assertSee('Somchai Jaidee')
        ->assertDontSee('findme@example.com')
        ->set('search', 'findme@')
        ->assertSee('Other')
        ->assertDontSee('Somchai Jaidee')
        ->set('search', 'no-such-user')
        ->assertSee('No data found');
});

test('a user can be created', function () {
    Livewire::test(Editor::class)
        ->set('form.name', 'New User')
        ->set('form.email', 'new@example.com')
        ->set('form.password', 'password')
        ->set('form.password_confirmation', 'password')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('users.index'));

    $user = User::firstWhere('email', 'new@example.com');

    expect($user->name)->toBe('New User')
        ->and(Hash::check('password', $user->password))->toBeTrue();
});

test('creating a user validates the input', function () {
    Livewire::test(Editor::class)
        ->set('form.email', $this->admin->email)
        ->set('form.password', 'password')
        ->set('form.password_confirmation', 'different')
        ->call('save')
        ->assertHasErrors(['form.name', 'form.email', 'form.password']);
});

test('a user can be edited and a blank password keeps the old one', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    Livewire::test(Editor::class, ['user' => $user])
        ->assertSet('form.name', $user->name)
        ->set('form.name', 'Renamed')
        ->set('form.email', $user->email)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('users.index'));

    $user->refresh();

    expect($user->name)->toBe('Renamed')
        ->and(Hash::check('old-password', $user->password))->toBeTrue();
});

test('a password can be changed when editing', function () {
    $user = User::factory()->create();

    Livewire::test(Editor::class, ['user' => $user])
        ->set('form.password', 'new-password')
        ->set('form.password_confirmation', 'new-password')
        ->call('save')
        ->assertHasNoErrors();

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('a user can be deleted', function () {
    $user = User::factory()->create();

    Livewire::test(Index::class)
        ->call('confirmDelete', $user->id)
        ->call('delete');

    expect($user->fresh())->toBeNull();
});

test('a user cannot delete their own account', function () {
    Livewire::test(Index::class)
        ->call('confirmDelete', $this->admin->id)
        ->assertForbidden();

    expect($this->admin->fresh())->not->toBeNull();
});

test('user pages can be rendered', function () {
    $this->get(route('users.index'))->assertOk()->assertSee($this->admin->email);
    $this->get(route('users.create'))->assertOk();
    $this->get(route('users.edit', $this->admin))->assertOk()->assertSee($this->admin->email);
});
