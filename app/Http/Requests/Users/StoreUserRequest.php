<?php

namespace App\Http\Requests\Users;

use App\Enums\Gender;
use App\Facades\Session;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Joalvm\Utils\Rules\AlphaSpaceRule;

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
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['filled', 'string', 'min:8'],
            'confirm_password' => ['required_with:password', 'same:password'],
            'avatar_url' => ['filled', 'string'],
            'enabled' => ['filled', 'boolean'],
            'redirect_url' => ['filled', 'string'],
            'person' => [
                'required_without:person_id',
                'array:names,last_names,gender,document_type_id,id_document',
            ],
            'person.names' => ['required_with:person', 'string', new AlphaSpaceRule()],
            'person.last_names' => ['required_with:person', 'string', new AlphaSpaceRule()],
            'person.gender' => ['required_with:person', 'string', Rule::in(Gender::values())],
            'person.document_type_id' => ['required_with:person', 'integer', new IntegerPositiveRule()],
            'person.id_document' => ['required_with:person', 'string'],
        ];
    }
}
