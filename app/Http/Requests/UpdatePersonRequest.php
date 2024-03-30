<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Facades\Session;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Joalvm\Utils\Rules\AlphaSpaceRule;

class UpdatePersonRequest extends FormRequest
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
            'names' => ['filled', 'string', new AlphaSpaceRule()],
            'last_names' => ['filled', 'string', new AlphaSpaceRule()],
            'gender' => ['filled', 'string', Rule::in(Gender::values())],
            'document_type_id' => ['filled', 'integer', new IntegerPositiveRule()],
            'id_document' => ['filled', 'string'],
            'email' => ['nullable', 'string'],
        ];
    }
}
