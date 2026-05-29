<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreProductRequest extends FormRequest
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
            'category_id'       => ['required', 'integer'],
            'cost_per_unit'     => ['required', 'numeric', 'min:1'],
            'selling_price'     => ['required', 'numeric', 'gt:cost_per_unit'],
            'crates_available'  => ['required', 'integer', 'min:1'],
            'pieces_per_crate'  => ['required', 'integer', 'min:1'],
            'supplier'          => ['required', 'string', 'min:3'],
            'supplier_phone_no' => ['required', 'numeric', 'digits:11'],
            'photo'             => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120']
        ];
    }

    #[Override]
    public function attributes(): array
    {
        return [
            'cost_per_unit' => 'cost',
            'selling_price' => 'selling price',
            'crates_available' => 'crates',
            'pieces_per_crate' => 'crate',
            'supplier_phone_no' => 'phone number'
        ];
    }
}
