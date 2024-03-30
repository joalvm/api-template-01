<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Facades\Session;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Joalvm\Utils\Rules\AlphaSpaceRule;

class StorePersonRequest extends FormRequest
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
            'names' => ['required', 'string', new AlphaSpaceRule()],
            'last_names' => ['required', 'string', new AlphaSpaceRule()],
            'gender' => ['required', 'string', Rule::in(Gender::values())],
            'document_type_id' => ['required', 'integer', new IntegerPositiveRule()],
            'id_document' => ['required', 'string'],
            'email' => ['filled', 'string', 'email'],
        ];
    }
}
