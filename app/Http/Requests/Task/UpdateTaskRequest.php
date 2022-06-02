<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;


class UpdateTaskRequest extends FormRequest
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
            'title' => ['string'],
            'description' => ['string'],
            'status' => ['integer'],
            'group_id' => ['integer', 'exists:App\Models\Group,id'],
            'ids.*' => ['integer'],
            'checklists' => ['string']
//            'attachments' => ['mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf']
        ];
    }
}
