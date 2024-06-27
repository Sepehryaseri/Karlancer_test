<?php

namespace App\Http\Requests\TaskTitle;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetTaskTitleListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|nullable',
            'from_date' => 'date_format: Y-m-d',
            'to_date' => 'date_format:Y-m-d|required_with:from_date'
        ];
    }
}
