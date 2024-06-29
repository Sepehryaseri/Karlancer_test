<?php

namespace App\Http\Requests\TaskTitle;

use App\Rules\CategoryRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskTitleListRequest extends FormRequest
{
    public function __construct(public CategoryRule $categoryRule, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
            'name' => 'string',
            'due_date' => 'date_format:Y-m-d H:i:s',
            'categories' => 'array',
            'categories.*' => ['string', $this->categoryRule]
        ];
    }
}
