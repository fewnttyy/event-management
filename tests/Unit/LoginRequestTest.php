<?php

namespace Tests\Unit;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LoginRequestTest extends TestCase
{
    private function getValidationResult($data)
    {
        $request = new LoginRequest();
        return Validator::make($data, $request->rules());
    }

    #[Test]
    public function test_email_is_required()
    {
        $validator = $this->getValidationResult([
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    #[Test]
    public function test_email_must_be_valid_format()
    {
        $validator = $this->getValidationResult([
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    #[Test]
    public function test_password_is_required()
    {
        $validator = $this->getValidationResult([
            'email' => 'test@example.com',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    #[Test]
    public function test_valid_data_passes_validation()
    {
        $validator = $this->getValidationResult([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertFalse($validator->fails());
    }
}
