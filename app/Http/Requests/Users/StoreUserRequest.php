<?php

namespace App\Http\Requests\Users;

use App\Enums\UserRole;
use App\Facades\Session;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'person_id' => ['required_without:person', 'integer', new IntegerPositiveRule()],
            'role' => ['filled', 'string', Rule::in(UserRole::values())],
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['filled', 'string', 'min:8'],
            'confirm_password' => ['required_with:password', 'same:password'],
            'avatar_url' => ['filled', 'string'],
            'enabled' => ['filled', 'boolean'],
            'redirect_url' => ['filled', 'string'],
        ];
    }
}
