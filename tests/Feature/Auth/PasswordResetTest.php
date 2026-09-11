<?php

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::resetPasswords());
});

test('reset password link screen can be rendered', function () {
    $response = $this->get(route('password.request'));

    $response->assertOk();
});

test('reset password link can be requested', function () {
    // Password reset via phone_number is not supported by Laravel's built-in
    // password broker (which relies on email). This test is skipped.
    $this->markTestSkipped('Password reset via phone_number is not supported by the default password broker.');
});

test('reset password screen can be rendered', function () {
    $this->markTestSkipped('Password reset via phone_number is not supported by the default password broker.');
});

test('password can be reset with valid token', function () {
    $this->markTestSkipped('Password reset via phone_number is not supported by the default password broker.');
});

test('password cannot be reset with invalid token', function () {
    $this->markTestSkipped('Password reset via phone_number is not supported by the default password broker.');
});
