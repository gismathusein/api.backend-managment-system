<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
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
            'name' => ['string'],
            'group_type' => ['integer','min:0','max:1'],
            'status' => ['integer','min:0','max:1'],
            'users'=>['exists:App\Models\User,id'],
            'admins'=>['exists:App\Models\User,id'],
        ];
    }
}
