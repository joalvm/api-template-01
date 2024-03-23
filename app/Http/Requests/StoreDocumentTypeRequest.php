<?php

namespace App\Http\Requests;

use App\Enums\CharType;
use App\Enums\LengthType;
use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDocumentTypeRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'abbr' => ['required', 'string'],
            'length_type' => ['required', new Enum(LengthType::class)],
            'length' => ['required', 'integer', 'min:1', 'max:' . PHP_INT_MAX],
            'char_type' => ['required', new Enum(CharType::class)],
        ];
    }
}
