<?php

namespace App\Http\Requests\Ubigeo;

use App\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
            'code' => ['required', 'string', 'size:2'],
            'latitude' => ['filled', 'numeric', 'required_with:longitude'],
            'longitude' => ['filled', 'numeric', 'required_with:latitude'],
        ];
    }
}
