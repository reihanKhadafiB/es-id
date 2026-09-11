<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
})->skip('Requires Vite manifest (run npm run build first).');

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.update'))
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'phone_number' => '081234567890',
        ]);

    $response->assertSessionHasNoErrors();

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->phone_number)->toBe('081234567890');
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.update'))
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'phone_number' => $user->phone_number,
        ]);

    $response->assertSessionHasNoErrors();

    expect($user->refresh()->phone_number)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response->assertSessionHasErrors('password');

    expect($user->fresh())->not->toBeNull();
});
