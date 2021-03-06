<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testLoginUser()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('login'), [
            'email' => $user['email'],
            'password' => 'password'
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => ['token', 'user']
            ])
            ->assertSeeText('User logged in successfully');
    }

    public function testLoginFailsWithIncorrectCredentials()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'hello@gmail.com',
            'password' => 'yumm'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertSeeText('Invalid login credentials');
    }
}
