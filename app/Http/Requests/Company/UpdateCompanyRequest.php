<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name' => ['string'],
            'email' => ['email', 'string'],
            'phone' => ['max:9', 'min:9', 'string'],
            'address' => ['string'],
            'logo' => ['image', 'mimes:jpg,jpeg,png'],
            'status' => ['integer', 'min:0', 'max:1']
        ];
    }
}
