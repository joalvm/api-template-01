<?php

namespace App\Http\Requests\Users;

use App\Enums\Gender;
use App\Enums\UserRole;
use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Joalvm\Utils\Rules\AlphaSpaceRule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Session::isLogged();
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
            'role' => ['required', 'string', new Enum(UserRole::class)],
            'avatar_url' => ['filled', 'string'],
            'email' => ['required', 'email'],
            'password' => ['filled', 'string', 'min:8'],
            'confirm_password' => ['required_with:password', 'same:password'],
            'enabled' => ['filled', 'boolean'],
            'person' => ['required_without:person_id', 'array'],
            'person.names' => ['required_with:person', 'string', new AlphaSpaceRule()],
            'person.last_names' => ['required_with:person', 'string', new AlphaSpaceRule()],
            'person.gender' => ['required_with:person', new Enum(Gender::class)],
            'person.document_type_id' => ['required_with:person', 'integer'],
            'person.id_document' => ['required_with:person', 'string'],
            'person.email' => ['filled', 'string'],
        ];
    }
}
