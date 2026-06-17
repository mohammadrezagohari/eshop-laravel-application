<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'role' => 'required|in:' . implode(',', [
                User::ROLE_CUSTOMER,
                User::ROLE_SELLER,
                User::ROLE_ADMIN,
            ]),
        ];
    }
}
