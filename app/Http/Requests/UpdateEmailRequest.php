<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new-email'      => ['required', 'email', 'unique:users,email'],
            'confirm-email'  => ['required', 'email', 'same:new-email'],
            'email-password' => ['required', 'string', 'regex:/^\d{6}$/']
        ];
    }

    
    public function messages()
    {
        return [
            'new-email.required' => 'New email is required.',
            'new-email.email' => 'Please enter a valid email address.',
            'new-email.unique' => 'This email is already in use.',
            'confirm-email.required' => 'Email confirmation is required.',
            'confirm-email.same' => 'The email confirmation does not match.',
            'email-password.required' => 'Password is required.',
            'email-password.regex' => 'Password must be exactly 6 digits.'
        ];
    }
}
