<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken($user->email, ['*']);

        return [
            'token' => $token->plainTextToken,
            'name' => $user->name,
            'message' => 'register successfully',
        ];
    }

    public function login(array $data): array
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken($user->email, ['*']);

        return [
            'token' => $token->plainTextToken,
            'name' => $user->name,
            'message' => 'login successfully',
        ];
    }

    public function logout(User $user = null): void
    {
        if (!$user) {
            return;
        }

        $currentToken = $user->currentAccessToken();

        if ($currentToken) {
            $currentToken->delete();
            return;
        }

        $user->tokens()->delete();
    }
}
