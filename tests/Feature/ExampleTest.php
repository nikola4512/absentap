<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function userParentLoginSuccess()
    {
        $response = $this->post('/login', [
            'username' => '0064707377',
            'password' => '1871122204060001',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Login Success, Please wait!'
        ]);

        $this->assertGuest('parent');
    }
}
