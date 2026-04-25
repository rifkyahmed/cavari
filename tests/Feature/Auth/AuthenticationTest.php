<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_route_redirects_to_home_with_modal_open(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('open_auth_modal', true);
    }

    public function test_users_can_authenticate_and_are_redirected_to_intended_url(): void
    {
        $user = User::factory()->create();

        // Simulate visiting a protected custom order link, which saves intended_url
        $intendedUrl = 'http://localhost/custom-orders/uuid-123/pay';
        
        $response = $this->withSession(['intended_url' => $intendedUrl])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
                'intended_url' => $intendedUrl // The modal passes this explicitly
            ]);

        $this->assertAuthenticated();
        
        // Assert json response
        if ($response->baseResponse->headers->get('Content-Type') === 'application/json') {
             $response->assertJson(['redirect' => $intendedUrl]);
        } else {
             $response->assertRedirect($intendedUrl);
        }
    }

    public function test_admin_users_are_redirected_to_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertJson(['redirect' => route('admin.dashboard')]);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
