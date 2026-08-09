<?php

namespace App\Http\Requests;

use App\Enums\ProductUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::rulesFor($this->route('product')?->id);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'sell_price.required' => 'Harga Jual (Umum) wajib diisi.',
        ];
    }

    public static function rulesFor(?int $ignoreId = null): array
    {
        return [
            'barcode' => ['nullable', 'string', 'max:50'],
            'sku' => [
                'nullable', 'string', 'max:50',
                Rule::unique('products', 'sku')->ignore($ignoreId)->whereNull('deleted_at'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'unit' => ['required', Rule::in(ProductUnit::getValues())],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'content_per_bal' => ['nullable', 'integer', 'min:1'],
            'pcs_per_bal' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
            'min_purchase' => ['required', 'integer', 'min:1'],
            'base_cost' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
