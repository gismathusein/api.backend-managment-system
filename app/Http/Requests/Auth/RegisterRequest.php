<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() :bool
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
            'company_id'=>'required|integer|exists:App\Models\Company,id',
            'department_id'=>'required|integer|exists:App\Models\Department,id',
            'position_id'=>'required|integer|exists:App\Models\Position,id',
            'fin_code'=>'required|string|min:7|max:7|unique:users',
            'serial_number'=>'required|string|min:6|max:13|unique:users',
            'serial_code'=>'required|string',
            'name' => 'required|string|between:2,100',
            'surname' => 'required|string|between:2,100',
            'phone'=>'required|string|max:14|unique:users',
            'address'=>'required|string|between:3,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'photo' => 'image|mimes:jpg,jpeg,png',
            'status' => 'integer|min:0|max:1'
        ];
    }
}


