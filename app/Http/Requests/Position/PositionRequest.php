<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
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
            'department_id' => ['required', 'integer','exists:App\Models\Department,id'],
            'name' => ['required', 'string'],
            'status' => ['integer','max:1','min:0']
        ];
    }
}
