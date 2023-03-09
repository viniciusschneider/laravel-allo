<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function registerUser() {
        $user = User::create([
            'name' => 'Vinicius',
            'email' => 'vini@gmail.com',
            'password' => Hash::make('1234@Allo'),
        ]);

        return JWTAuth::fromUser($user);
    }

    public function test_register()
    {
        $response = $this->post('/api/auth/register', [
            'name' => 'Vinicius',
            'email' => 'vini@gmail.com',
            'password' => '1234@Allo',
            'password_confirmation' => '1234@Allo',
        ]);

        $response->assertStatus(201);
    }

    public function test_register_with_duplicated_email()
    {
        $email = 'vini@gmail.com';

        User::create([
            'name' => 'Vinicius',
            'email' => $email,
            'password' => Hash::make('1234@Allo'),
        ]);

        $response = $this->post('/api/auth/register', [
            'name' => 'Vinicius',
            'email' => $email,
            'password' => '1234@Allo',
            'password_confirmation' => '1234@Allo',
        ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'DUPLICATE_EMAIL'
            ]);
    }

    public function test_register_with_password_weak()
    {
        $response = $this->post('/api/auth/register', [
            'name' => 'Vinicius',
            'email' => 'vini@gmail.com',
            'password' => '1234',
            'password_confirmation' => '1234',
        ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'PASSWORD_TOO_WEAK'
            ]);
    }

    public function test_login()
    {
        $email = 'vini@gmail.com';
        $password = '1234@Allo';

        User::create([
            'name' => 'Vinicius',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
    }

    public function test_login_fail()
    {
        $email = 'vini@gmail.com';
        $password = '1234@Allo';

        User::create([
            'name' => 'Vinicius',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $email,
            'password' => strtolower($password),
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'status' => 'UNAUTHORIZED'
            ]);
    }

    public function test_me()
    {
        $token = $this->registerUser();

        $response = $this->get('/api/auth/me', [
            'Authorization' => "Bearer $token"
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'name' => 'Vinicius'
            ]);
    }

    public function test_refresh()
    {
        $token = $this->registerUser();

        $response = $this->get('/api/auth/refresh', [
            'Authorization' => "Bearer $token"
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token'
            ]);
    }

    public function test_logout()
    {
        $token = $this->registerUser();

        $response = $this->post('/api/auth/logout', [], [
            'Authorization' => "Bearer $token"
        ]);

        $response
            ->assertStatus(200);
    }
}
