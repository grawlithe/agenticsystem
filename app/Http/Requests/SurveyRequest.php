<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255",
            "feedback" => "required|string",
            "user_id" => "required|exists:users,id",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Please provide your name.",
            "email.required" => "Please provide your email address.",
            "email.email" => "Please provide a valid email address.",
            "feedback.required" => "Please provide your feedback.",
            "user_id.required" => "Please provide a valid user ID.",
            "user_id.exists" => "The selected user ID is invalid.",
        ];
    }
}
