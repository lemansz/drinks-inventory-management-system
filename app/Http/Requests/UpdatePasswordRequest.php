<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdatePasswordRequest extends FormRequest
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
            'current-password' => ['required', 'string', 'regex:/^\d{6}$/'],
            'new-password'     => ['required', 'string', 'regex:/^\d{6}$/'],
            'confirm-password' => ['required', 'string', 'same:new-password']
        ];
    }

    public function messages(): array
    {
        return [
            'current-password.required' => 'Current password is required.',
            'current-password.regex'    => 'Current password must be exactly 6 digits.',
            'new-password.required'     => 'New password is required.',
            'new-password.regex'        => 'New password must be exactly 6 digits.',
            'confirm-password.required' => 'Password confirmation is required.',
            'confirm-password.same'     => 'The password confirmation does not match.'
        ];
    }
}
