<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name'              => ['required', 'string'],
            'cost_per_unit'     => ['required', 'numeric', 'min:1'],
            'selling_price'     => ['required', 'numeric', 'gt:cost_per_unit'],
            'supplier'          => ['required', 'string', 'min:3'],
            'supplier_phone_no' => ['required', 'numeric', 'digits:11'],
            'photo'             => ['nullable', 'images', 'mimes:jpeg,png,jpg', 'max:5120'],
            'category_id'       => ['required']
        ];
    }
}
