<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'company_id'=>'integer|exists:App\Models\Company,id',
            'department_id'=>'integer|exists:App\Models\Department,id',
            'position_id'=>'integer|exists:App\Models\Position,id',
            'fin_code'=>'string|min:7|max:7|unique:users,fin_code,'.$this->id,
            'serial_number'=>'string|min:6|max:13|unique:users,serial_number,'.$this->id,
            'serial_code'=>'string',
            'name' => 'string|between:2,100',
            'surname' => 'string|between:2,100',
            'phone'=>'string|max:14|unique:users,phone,'.$this->id,
            'address'=>'string|between:3,100',
            'email' => 'string|email|max:100|unique:users,email,'.$this->id,
            'password' => 'string|min:6',
            'photo' => 'image|mimes:jpg,jpeg,png',
            'status' => 'integer|min:0|max:1'
        ];
    }
}
