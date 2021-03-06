<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use WithFaker;

    public function testUserCanRegister()
    {
        $email = $this->faker->email;
        $response = $this->postJson(route('register'), [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => $this->faker->password
        ]);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => ['token', 'user']
            ])
            ->assertSeeText($email);
    }

    public function testNoNameProvided()
    {
        $response = $this->postJson(route('register'), [
            'name' => '',
            'email' => $this->faker->email,
            'password' => '888'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }

    public function testInvalidEmailProvided()
    {
        $email = "wronggmail.com";
        $response = $this->postJson(route('register'), [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => $this->faker->password
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }

    public function testInvalidPasswordProvided()
    {
        $response = $this->postJson(route('register'), [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => '888'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertSeeText('The password must be at least 6 characters.');;
    }
}
