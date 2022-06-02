<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
    public function rules()
    {
        return [
            'company_id' => ['required', 'integer'],
            'user_id.*' => ['integer'],
            'start_date' => ['date_format:m/d/Y'],
            'end_date' => ['date_format:m/d/Y', 'after:start_date']
        ];
    }
}
