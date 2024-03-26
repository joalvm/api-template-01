<?php

namespace App\Http\Requests\Users;

use App\Enums\UserRole;
use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Session::isAuthenticated() and !Session::isUserBasic();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'person_id' => ['required_without:person', 'integer'],
            'role' => ['filled', 'string', Rule::in(UserRole::values())],
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['filled', 'string', 'min:8'],
            'confirm_password' => ['required_with:password', 'same:password'],
            'avatar_url' => ['filled', 'string'],
            'enabled' => ['filled', 'boolean'],
            'super_admin' => [
                'filled',
                'boolean',
                'exclude_if:role,' . UserRole::USER->value,
                'exclude_if:role,' . UserRole::ADMIN->value,
                Rule::excludeIf(fn () => !Session::isSuperAdmin()),
            ],
            'redirect_url' => ['filled', 'string'],
        ];
    }
}
