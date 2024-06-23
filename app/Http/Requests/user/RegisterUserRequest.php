<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class RegisterUserRequest extends FormRequest
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
    #[ArrayShape(['name' => "string", 'email' => "string", 'password' => "string"])]
    public function rules(): array
    {
        return [
            'name' => 'string|required',
            'email' => 'email|required',
            'password' => 'string|confirmed|min:5|required',
        ];
    }
}
