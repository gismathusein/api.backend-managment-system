<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'string'],
            'phone' => ['required', 'max:9', 'min:9', 'string'],
            'address' => ['required', 'string'],
            'logo' => ['image', 'mimes:jpg,jpeg,png'],
            'status' => ['integer', 'min:0', 'max:1']
        ];
    }
}
