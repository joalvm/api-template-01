<?php

namespace App\Http\Requests\Users;

use App\Enums\UserRole;
use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Session::isAuthenticated();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['filled', 'string', Rule::in(UserRole::values())],
            'avatar_url' => ['nullable', 'string'],
            'email' => ['filled', 'email'],
            'password' => ['filled', 'string', 'min:8'],
            'current_password' => ['required_with:password', 'string', 'min:8'],
            'confirm_password' => ['required_with:password', 'same:password'],
            'enabled' => ['filled', 'boolean'],
            'redirect_url' => ['filled', 'string'],
        ];
    }
}
