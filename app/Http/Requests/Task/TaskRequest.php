<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['string'],
            'status' => ['integer'],
            'group_id' => ['required', 'exists:App\Models\Group,id'],
            'user_id' => ['integer', 'exists:App\Models\User,id'],
            'checklists' => ['string'],
        ];
    }
}
