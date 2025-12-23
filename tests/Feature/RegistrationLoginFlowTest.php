<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegistrationLoginFlowTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_can_register_and_is_redirected_based_on_role()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test-user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ];

        $response = $this->postJson('/role-selection/register', $payload);
        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', ['email' => 'test-user@example.com', 'role' => 'user']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_can_login_and_receives_redirect_url_for_role()
    {
        // create user
        $user = User::create([
            'name' => 'Login User',
            'email' => 'login-user@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'chauffeur',
            'email_verified_at' => now(),
        ]);

        $payload = [
            'email' => 'login-user@example.com',
            'password' => 'secret123',
            'role' => 'chauffeur',
        ];

        $response = $this->postJson('/role-selection/login', $payload);
        $response->assertStatus(200)->assertJson(['success' => true]);

        $json = $response->json();
        $this->assertArrayHasKey('redirect_url', $json);
        $this->assertStringContainsString('/dashboard', $json['redirect_url']);
    }
}
