<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Joalvm\Utils\Rules\AlphaSpaceRule;

class StorePersonRequest extends FormRequest
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
            'names' => ['required', 'string', new AlphaSpaceRule()],
            'last_names' => ['required', 'string', new AlphaSpaceRule()],
            'gender' => ['required', new Enum(Gender::class)],
            'document_type_id' => ['required', 'integer'],
            'id_document' => ['required', 'string'],
            'email' => ['filled', 'string'],
        ];
    }
}
