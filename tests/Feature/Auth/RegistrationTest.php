<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_registration_route_redirects_to_home_with_modal_open(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('open_auth_modal', true);
        $response->assertSessionHas('auth_mode', 'register');
    }

    public function test_new_users_can_register_and_are_redirected_to_intended_url(): void
    {
        $intendedUrl = 'http://localhost/custom-orders/uuid-123/pay';

        $response = $this->postJson('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'intended_url' => $intendedUrl
        ]);

        $this->assertAuthenticated();
        
        if ($response->baseResponse->headers->get('Content-Type') === 'application/json') {
             $response->assertJson(['success' => true, 'redirect' => $intendedUrl]);
        } else {
             $response->assertRedirect($intendedUrl);
        }
    }
}
