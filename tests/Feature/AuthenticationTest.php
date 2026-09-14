<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_projects(): void
    {
        $response = $this->get('/projects');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_tasks(): void
    {
        $response = $this->get('/tasks');

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}