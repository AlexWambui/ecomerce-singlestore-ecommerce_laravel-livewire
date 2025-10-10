<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ProductMeasurementUnits;

class ProductRequest extends FormRequest
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
        $product = $this->route('product');

        return [
            'title' => [
                'required',
                'string',
                'max:80',
                $product ? Rule::unique('products', 'title')->ignoreModel($product) : Rule::unique('products', 'title'),
            ],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'product_code' => ['nullable', 'string', 'max:255'],
            'sku' => [
                'nullable',
                'string',
                'max:255',
                $product
                    ? Rule::unique('products', 'sku')->ignoreModel($product)
                    : Rule::unique('products', 'sku'),
            ],
            'barcode' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_visible' => ['sometimes', 'boolean'],
            'production_cost' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'discount_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'product_measurement' => ['nullable', 'integer', 'min:0'],
            'measurement_unit' => ['nullable', Rule::in(array_column(ProductMeasurementUnits::cases(), 'value'))],
            'track_inventory' => ['sometimes', 'boolean'],
            'stock_count' => ['nullable', 'integer', 'min:0'],
            'safety_stock' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'url'],
            'meta_tags' => ['nullable', 'json'],
            'og_tags' => ['nullable', 'json'],
            'noindex' => ['sometimes', 'boolean'],
            'nofollow' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The product title is required.',
            'title.unique' => 'A product with this title already exists.',
            'sku.unique' => 'This SKU is already in use.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.max' => 'Each image must be under 2MB.',
            'images.max' => 'You can upload a maximum of 5 images.',
            'measurement_unit.in' => 'Please select a valid measurement unit.',
            'canonical_url.url' => 'The canonical URL must be a valid URL.',
        ];
    }
}
