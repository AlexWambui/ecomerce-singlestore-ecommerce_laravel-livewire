<x-app-layout>
    <div class="custom_form py-4 max-w-8xl mx-auto">
        <div class="header">
            <a href="{{ Route::has('products.index') ? route('products.index') : '#' }}" wire:navigate>
                <x-svgs.arrow-left class="w-5 h-5" />
            </a>
            <h2>Create New Product</h2>
        </div>

        <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="title" class="required">Title</label>
                    <input type="text" name="title" id="title" autocomplete="title" value="{{ old('title') }}" autofocus>
                    <x-form-input-error field="title" />
                </div>

                <div class="inputs">
                    <label for="product_category_id">Category</label>
                    <select name="product_category_id" id="product_category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                        @endforeach
                    </select>
                    <x-form-input-error field="product_category_id" />
                </div>

                <div class="inputs">
                    <label for="product_code">Product Code</label>
                    <input type="text" name="product_code" id="product_code" autocomplete="product_code" value="{{ old('product_code') }}">
                    <x-form-input-error field="product_code" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="is_featured">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        Featured Product
                    </label>
                    <x-form-input-error field="is_featured" />
                </div>

                <div class="inputs">
                    <label for="is_visible">
                        <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}>
                        Visible to Customers
                    </label>
                    <x-form-input-error field="is_visible" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="stock_count">Stock Count</label>
                    <input type="number" name="stock_count" id="stock_count" placeholder="Stock in hand" value="{{ old('stock_count', 0) }}" />
                    <x-form-input-error field="stock_count" />
                </div>

                <div class="inputs">
                    <label for="safety_stock">Safety Stock Count</label>
                    <input type="number" name="safety_stock" id="safety_stock" placeholder="Safety Stock Count" value="{{ old('safety_stock', 0) }}" />
                    <x-form-input-error field="safety_stock" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="production_cost">Production Cost</label>
                    <input type="number" step="0.01" name="production_cost" id="production_cost" value="{{ old('production_cost', 0.00) }}" placeholder="Enter the Production Cost eg. 300.00" />
                    <x-form-input-error field="production_cost" />
                </div>

                <div class="inputs">
                    <label for="selling_price">Selling Price</label>
                    <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price', 0.00) }}" placeholder="Enter the Buying Price eg. 500.00" />
                    <x-form-input-error field="selling_price" />
                </div>

                <div class="inputs">
                    <label for="discount_price">Discount Price (Price after discount)</label>
                    <input type="number" step="0.01" name="discount_price" id="discount_price" value="{{ old('discount_price', 0.00) }}" placeholder="Enter the Price after discount eg. 200.00" />
                    <x-form-input-error field="discount_price" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="product_measurement">Product Measurement</label>
                    <input type="number" name="product_measurement" id="product_measurement" value="{{ old('product_measurement') }}" placeholder="Eg. 200">
                    <span class="inline_alert">{{ $errors->first('product_measurement') }}</span>
                </div>

                <div class="inputs">
                    <label for="measurement_unit">Measurement Unit</label>
                    <select name="measurement_unit" id="measurement_unit">
                        <option value="">Select Measurement Unit</option>
                        @foreach(\App\Enums\PRODUCT_MEASUREMENT_UNITS::labels() as $value => $label)
                            <option value="{{ $value }}" {{ old('measurement_unit') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <span class="inline_alert">{{ $errors->first('measurement_unit') }}</span>
                </div>

                <div class="inputs">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" min="1" value={{ old('sort_order') }}>
                    <span class="inline_alert">{{ $errors->first('sort_order') }}</span>
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="image">Images (Max allowed images is 5 and < 2MB)</label>
                    <input type="file" name="images[]" id="images" accept=".png, .jpg, .jpeg, .webp, .svg" multiple>
                    <x-form-input-error field="images.*" />
                </div>
            </div>

            <div class="inputs">
                <label for="description">Description</label>
                <textarea name="description" id="ckeditor" cols="30" rows="10">{{ old('description') }}</textarea>
                <x-form-input-error field="description" />
            </div>

            <div class="buttons_group">
                <button type="submit">Save Product</button>
                <a href="{{ Route::has('product-categories.index') ? route('product-categories.index') : '#' }}" wire:navigate class="btn btn_danger">Cancel</a>
            </div>
        </form>
    </div>

    <x-slot name="scripts">
        <x-ckeditor />
    </x-slot>
</x-app-layout>

