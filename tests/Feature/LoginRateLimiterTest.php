<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\RateLimiter;

class LoginRateLimiterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'registration_number' => '123456',
            'password' => bcrypt('correct-password'),
        ]);

        RateLimiter::clear('/api/login');
    }

    public function test_login_is_rate_limites_after_too_many_attempts()
    {
        $payload = [
            'registration_number' => "123456",
            'password' => "Password123!",
        ];

        for ($i = 0; $i < 5; $i++){
            $response = $this->postJson('/api/login', $payload);
            $response->assertStatus(401);
        }

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(429);
        $response->assertJson([
            'message' => 'Trop de tentatives. Réessayez dans 60 secondes.',
        ]);
    }
}
