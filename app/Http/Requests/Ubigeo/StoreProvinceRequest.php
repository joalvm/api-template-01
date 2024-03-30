<?php

namespace App\Http\Requests\Ubigeo;

use App\Facades\Session;
use App\Rules\Pgsql\IntegerPositiveRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProvinceRequest extends FormRequest
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
            'department_id' => [
                'required',
                'integer',
                new IntegerPositiveRule(),
            ],
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'size:4'],
            'latitude' => ['filled', 'numeric', 'required_with:longitude'],
            'longitude' => ['filled', 'numeric', 'required_with:latitude'],
        ];
    }
}
