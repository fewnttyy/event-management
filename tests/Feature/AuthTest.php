<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Repositories\AuthRepository;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Exception;

class AuthTest extends TestCase
{
    // use RefreshDatabase;

    protected $authRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authRepository = $this->app->make(AuthRepository::class);
    }

    #[Test]
    public function test_user_can_register()
    {
        $registerData = [
            'name' => 'Fenty Test Register',
            'email' => 'fenty.testregister@example.com',
            'password' => 'fenty_testregister',
            'password_confirmation' => 'fenty_testregister',
        ];

        $result = $this->authRepository->register($registerData);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertNotNull($result['token']);
        $this->assertDatabaseHas('users', [
            'email' => $registerData['email'],
        ]);

        $user = User::where('email', $registerData['email'])->first();
        $user->tokens()->delete();
        $user->delete();
    }

    #[Test]
    public function test_user_cannot_register_with_existing_email()
    {
        $existingEmail = 'fenty.testregister2@example.com';
        $user = User::factory()->create(['email' => $existingEmail]);

        try{
            $registerData = [
                'name' => 'Fenty Test Register',
                'email' => $existingEmail,
                'password' => 'fenty_testregister',
            ];
            $result = $this->authRepository->register($registerData);
            $this->fail('Exception was not thrown on register with existing email');
        }catch(Exception $e) {
            $this->assertEquals('Email already exists', $e->getMessage());
            $user->delete();
        }
    }

    #[Test]
    public function test_user_can_login()
    {
        $user = User::create([
            'name' => 'user test',
            'email' => 'user.test@example.com',
            'password' => Hash::make('usertest'),
            'password_confirmation' => 'usertest',
        ]);

        $loginData = [
            'email' => 'user.test@example.com',
            'password' => 'usertest',
        ];

        $result = $this->authRepository->login($loginData);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertNotNull($result['token']);
        $this->assertEquals($user['email'], $result['user']['email']);
        $user->tokens()->delete();
        $user->delete();
    }

    #[Test]
    public function test_user_cannot_login_with_invalid_credentials()
    {
        try{
            $loginData = [
                'email' => 'user.test@example.com',
                'password' => 'usertest',
            ];
            $result = $this->authRepository->login($loginData);
            $this->fail('Exception was not thrown on register with existing email');
        }catch(Exception $e) {
            $this->assertEquals('Invalid email or password', $e->getMessage());
        }
    }

    #[Test]
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $result = $this->authRepository->logout();

        $this->assertTrue($result);
        $user->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_logout()
    {
        try {
            $this->authRepository->logout();
            $this->fail('Exception was not thrown when user is not logged in');
        }catch(Exception $e) {
            $this->assertEquals('User not logged in', $e->getMessage());
        }
    }

    #[Test]
    public function test_user_can_get_user_info()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $result =  $this->authRepository->userInfo();

        $this->assertNotNull($result);
        $this->assertEquals($user['email'], $result->email);
        $user->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_get_user_info()
    {
        try {
            $this->authRepository->userInfo();
            $this->fail('Exception was not thrown when user is not logged in');
        }catch (Exception $e) {
            $this->assertEquals('User not logged in', $e->getMessage());
        }
    }
}
