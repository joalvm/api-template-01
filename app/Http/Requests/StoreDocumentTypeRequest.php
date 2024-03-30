<?php

namespace App\Http\Requests;

use App\Enums\CharType;
use App\Enums\LengthType;
use App\Facades\Session;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentTypeRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'abbr' => ['required', 'string'],
            'length_type' => ['required', 'string', Rule::in(LengthType::values())],
            'length' => ['required', 'integer', new IntegerPositiveRule(2)],
            'char_type' => ['required', 'string', Rule::in(CharType::values())],
        ];
    }
}
