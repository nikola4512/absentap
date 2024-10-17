<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserParentLoginTest extends TestCase
{
    public function testAuthControllerSuccess()
    { 
        // Kirim permintaan login
        $this->post('parent/sign-in', [
            'username' => '0064707377',
            'password' => '1871122204060001',
        ])
        ->assertStatus(302)
        ->assertRedirect('/parent')
        ->assertSessionHasNoErrors();
        // ->withoutExceptionHandling()
        // ->assertForbidden();
    }
}
