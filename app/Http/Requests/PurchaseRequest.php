<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'code' => 'required|string|max:255',
            'date' => 'required|date',
            'grand_total' => 'required|decimal:0,2',
            'purchase_file' => 'nullable|file',
            'payment_date' => 'nullable|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|decimal:0,2',
            'products.*.price' => 'required|decimal:0,2',
            'products.*.discount' => 'nullable|decimal:0,2',
            'products.*.total' => 'required|decimal:0,2'
        ];
    }
}
