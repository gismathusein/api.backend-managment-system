<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','string'],
            'group_type' => ['required','integer','min:0','max:1'],
            'status' => ['required','integer','min:0','max:1'],
            'users'=>['required','exists:App\Models\User,id'],
            'admins'=>['required','exists:App\Models\User,id'],
        ];
    }
}
