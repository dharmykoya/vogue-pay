<?php


namespace App\services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class AuthService
{
    public function register($data)
    {
        $password = Hash::make($data['password']);

        return User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => $password
        ]);
    }
}
