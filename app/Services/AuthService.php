<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    /**
     * Login web app by issuing token.
     *
     * @param  array $data
     * @return array
     */
    public function loginWebApp(array $data)
    {
        // Find user
        $user = User::query()
            ->where(function ($query) use ($data) {
                $query->where('email', $data['username_or_email'])
                    ->orWhere('username', $data['username_or_email']);
            })->first();

        // Validate credentials
        if (empty($user) || !Hash::check($data['password'], $user->password)) {
            throw new UnauthorizedException('Invalid username, email or password');
        }

        return [
            'user' => $user,
            'token' => $user->createToken('WebAppToken')->plainTextToken
        ];
    }
}
