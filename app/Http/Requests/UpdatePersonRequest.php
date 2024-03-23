<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePersonRequest extends FormRequest
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
            'names' => ['filled', 'string', 'alpha_space'],
            'last_names' => ['filled', 'string', 'alpha_space'],
            'gender' => ['filled', new Enum(Gender::class)],
            'document_type_id' => ['filled', 'integer'],
            'id_document' => ['filled', 'string'],
            'email' => ['nullable', 'string'],
        ];
    }
}
