<?php

use App\Models\User;

test('the default locale is used until one is chosen', function () {
    config(['app.locale' => 'th']);

    $this->get(route('login'))->assertSee('เข้าสู่ระบบ');
});

test('the locale can be switched and is remembered', function () {
    $this->from(route('login'))
        ->get(route('locale', 'th'))
        ->assertRedirect(route('login'))
        ->assertSessionHas('locale', 'th');

    $this->get(route('login'))->assertSee('เข้าสู่ระบบ');

    $this->get(route('locale', 'en'));

    $this->get(route('login'))->assertSee('Log in to your account');
});

test('unsupported locales are rejected', function () {
    $this->get(route('locale', 'fr'))->assertNotFound();
});

test('validation messages are translated', function () {
    $this->withSession(['locale' => 'th'])
        ->post(route('register.store'), ['password' => 'password', 'password_confirmation' => 'x'])
        ->assertSessionHasErrors(['name' => 'กรุณากรอกชื่อ', 'password_confirmation' => 'ยืนยันรหัสผ่านไม่ตรงกับรหัสผ่าน']);
});

test('pages and dates follow the locale', function () {
    $user = User::factory()->create(['created_at' => '2026-10-07 15:49:00']);

    $this->withSession(['locale' => 'th'])->actingAs($user)
        ->get(route('users.index'))
        ->assertSee('จัดการผู้ใช้งาน')
        ->assertSee('7 ต.ค. 2026 15:49');

    $this->withSession(['locale' => 'en'])->actingAs($user)
        ->get(route('users.index'))
        ->assertSee('Manage users')
        ->assertSee('7 Oct 2026 15:49');
});
