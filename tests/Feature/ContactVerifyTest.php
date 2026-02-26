<?php

use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

test('homepage returns a successful response', function () {
    $this->get('/')->assertOk();
});

test('contact verify form renders', function () {
    $this->get('/')
        ->assertSee('Contact Verification')
        ->assertSee('Address')
        ->assertSee('Contact');
});

test('validation errors on empty submit', function () {
    Livewire::test('contact-verify')
        ->call('verify')
        ->assertHasErrors(['street', 'city', 'zip', 'countryCode', 'phone', 'email']);
});

test('country code must be exactly 2 alpha characters', function (string $value) {
    Http::fake(['nominatim.openstreetmap.org/*' => Http::response([], 200)]);

    Livewire::test('contact-verify')
        ->set('street', 'Karlsplatz 1')
        ->set('city', 'Wien')
        ->set('zip', '1010')
        ->set('countryCode', $value)
        ->set('phone', '+43 1 58801')
        ->set('email', 'test@gmail.com')
        ->call('verify')
        ->assertHasErrors('countryCode');
})->with(['ABC', '1A', 'A', '12']);

test('valid address is verified via Nominatim', function () {
    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([
            ['display_name' => 'Karlsplatz 1, 1010 Wien, Austria'],
        ], 200),
    ]);

    Livewire::test('contact-verify')
        ->set('street', 'Karlsplatz 1')
        ->set('city', 'Wien')
        ->set('zip', '1010')
        ->set('countryCode', 'AT')
        ->set('phone', '+43 1 58801')
        ->set('email', 'test@gmail.com')
        ->call('verify')
        ->assertHasNoErrors()
        ->assertSet('addressStatus.valid', true);
});

test('invalid address returns not found', function () {
    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([], 200),
    ]);

    Livewire::test('contact-verify')
        ->set('street', 'Nonexistent Street 999')
        ->set('city', 'Faketown')
        ->set('zip', '00000')
        ->set('countryCode', 'AT')
        ->set('phone', '+43 1 58801')
        ->set('email', 'test@gmail.com')
        ->call('verify')
        ->assertHasNoErrors()
        ->assertSet('addressStatus.valid', false);
});

test('valid phone number is verified', function () {
    Http::fake(['nominatim.openstreetmap.org/*' => Http::response([], 200)]);

    Livewire::test('contact-verify')
        ->set('street', 'Test Street 1')
        ->set('city', 'Wien')
        ->set('zip', '1010')
        ->set('countryCode', 'AT')
        ->set('phone', '+43 1 58801')
        ->set('email', 'test@gmail.com')
        ->call('verify')
        ->assertHasNoErrors()
        ->assertSet('phoneStatus.valid', true);
});

test('invalid phone number fails verification', function () {
    Http::fake(['nominatim.openstreetmap.org/*' => Http::response([], 200)]);

    Livewire::test('contact-verify')
        ->set('street', 'Test Street 1')
        ->set('city', 'Wien')
        ->set('zip', '1010')
        ->set('countryCode', 'AT')
        ->set('phone', '+43 000 000000')
        ->set('email', 'test@gmail.com')
        ->call('verify')
        ->assertHasNoErrors()
        ->assertSet('phoneStatus.valid', false);
});

test('valid email with MX record passes', function () {
    Http::fake(['nominatim.openstreetmap.org/*' => Http::response([], 200)]);

    Livewire::test('contact-verify')
        ->set('street', 'Test Street 1')
        ->set('city', 'Wien')
        ->set('zip', '1010')
        ->set('countryCode', 'AT')
        ->set('phone', '+43 1 58801')
        ->set('email', 'test@gmail.com')
        ->call('verify')
        ->assertHasNoErrors()
        ->assertSet('emailStatus.valid', true);
});

test('invalid email format fails verification', function () {
    Http::fake(['nominatim.openstreetmap.org/*' => Http::response([], 200)]);

    Livewire::test('contact-verify')
        ->set('street', 'Test Street 1')
        ->set('city', 'Wien')
        ->set('zip', '1010')
        ->set('countryCode', 'AT')
        ->set('phone', '+43 1 58801')
        ->set('email', 'not-an-email')
        ->call('verify')
        ->assertHasNoErrors()
        ->assertSet('emailStatus.valid', false);
});
