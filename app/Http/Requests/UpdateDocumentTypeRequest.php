<?php

namespace App\Http\Requests;

use App\Enums\CharType;
use App\Enums\LengthType;
use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateDocumentTypeRequest extends FormRequest
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
            'name' => ['filled', 'string'],
            'abbr' => ['filled', 'string'],
            'length_type' => ['filled', new Enum(LengthType::class)],
            'length' => ['filled', 'integer', 'min:1', 'max:' . PHP_INT_MAX],
            'char_type' => ['filled', new Enum(CharType::class)],
        ];
    }
}
