<?php

namespace App\Http\Requests;

use App\Enums\CharType;
use App\Enums\LengthType;
use App\Facades\Session;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentTypeRequest extends FormRequest
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
            'name' => ['filled', 'string'],
            'abbr' => ['filled', 'string'],
            'length_type' => ['filled', 'string', Rule::in(LengthType::values())],
            'length' => ['filled', 'integer', new IntegerPositiveRule()],
            'char_type' => ['filled', 'string', Rule::in(CharType::values())],
        ];
    }
}
