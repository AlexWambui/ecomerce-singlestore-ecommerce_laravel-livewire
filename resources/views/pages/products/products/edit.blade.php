<x-app-layout>
    <div class="custom_form py-4 max-w-8xl mx-auto">
        <div class="header">
            <a href="{{ Route::has('products.index') ? route('products.index') : '#' }}" wire:navigate>
                <x-svgs.arrow-left class="w-5 h-5" />
            </a>
            <h2>Update Product</h2>
        </div>

        <form action="{{ route('products.update', $product->uuid) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="title" class="required">Title</label>
                    <input type="text" name="title" id="title" autocomplete="title" value="{{ old('title', $product->title) }}" autofocus>
                    <x-form-input-error field="title" />
                </div>

                <div class="inputs">
                    <label for="product_category_id">Category</label>
                    <select name="product_category_id" id="product_category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>

                        @endforeach
                    </select>
                    <x-form-input-error field="product_category_id" />
                </div>

                <div class="inputs">
                    <label for="product_code">Product Code</label>
                    <input type="text" name="product_code" id="product_code" autocomplete="product_code" value="{{ old('product_code', $product->product_code) }}">
                    <x-form-input-error field="product_code" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="is_featured">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>

                        Featured Product
                    </label>
                    <x-form-input-error field="is_featured" />
                </div>

                <div class="inputs">
                    <label for="is_visible">
                        <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $product->is_visible) ? 'checked' : '' }}>
                        Visible to Customers
                    </label>
                    <x-form-input-error field="is_visible" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="stock_count">Stock Count</label>
                    <input type="number" name="stock_count" id="stock_count" placeholder="Stock in hand" value="{{ old('stock_count', $product->stock_count) }}" />
                    <x-form-input-error field="stock_count" />
                </div>

                <div class="inputs">
                    <label for="safety_stock">Safety Stock Count</label>
                    <input type="number" name="safety_stock" id="safety_stock" placeholder="Safety Stock Count" value="{{ old('safety_stock', $product->safety_stock) }}" />
                    <x-form-input-error field="safety_stock" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="production_cost">Production Cost</label>
                    <input type="number" step="0.01" name="production_cost" id="production_cost" value="{{ old('production_cost', $product->production_cost) }}" placeholder="Enter the Production Cost eg. 300.00" />
                    <x-form-input-error field="production_cost" />
                </div>

                <div class="inputs">
                    <label for="selling_price">Selling Price</label>
                    <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}" placeholder="Enter the Buying Price eg. 500.00" />
                    <x-form-input-error field="selling_price" />
                </div>

                <div class="inputs">
                    <label for="discount_price">Discount Price (Price after discount)</label>
                    <input type="number" step="0.01" name="discount_price" id="discount_price" value="{{ old('discount_price', $product->discount_price) }}" placeholder="Enter the Price after discount eg. 200.00" />
                    <x-form-input-error field="discount_price" />
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="product_measurement">Product Measurement</label>
                    <input type="number" name="product_measurement" id="product_measurement" value="{{ old('product_measurement', $product->product_measurement) }}" placeholder="Eg. 200">
                    <span class="inline_alert">{{ $errors->first('product_measurement') }}</span>
                </div>

                <div class="inputs">
                    <label for="measurement_unit">Measurement Unit</label>
                    <select name="measurement_unit" id="measurement_unit">
                        <option value="">Select Measurement Unit</option>
                        @foreach(\App\Enums\ProductMeasurementUnits::labels() as $value => $label)
                            <option value="{{ $value }}" {{ old('measurement_unit', $product->measurement_unit) == $value ? 'selected' : '' }}>{{ $label }}</option>

                        @endforeach
                    </select>
                    <span class="inline_alert">{{ $errors->first('measurement_unit') }}</span>
                </div>

                <div class="inputs">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" min="1" value={{ old('sort_order', $product->sort_order) }}>
                    <span class="inline_alert">{{ $errors->first('sort_order') }}</span>
                </div>
            </div>

            <div class="inputs_group_3">
                <div class="inputs">
                    <label for="image">Images (Max allowed images is 5 and < 2MB)</label>
                    <input type="file" name="images[]" id="images" accept=".png, .jpg, .jpeg, .webp, .svg" multiple>
                    <x-form-input-error field="images.*" />

                    @if ($product->productImages->count())
                        <div class="existing_images flex gap-2 flex-wrap mt-2" id="sortable">
                            @foreach ($product->productImages as $image)
                                <div class="relative w-32 h-32 sortable_images" id="{{ $image->id }}">
                                    <img src='{{ Storage::url("products/images/" . $image->image) }}' alt="{{ $product->title }}" class="w-full h-full object-cover rounded" />

                                    <a href="#" data-action="{{ route('product-images.destroy', $image->uuid) }}" data-image-id="{{ $image->id }}" class="delete-image-link absolute top-1 right-1 btn_danger text-white p-1 rounded-full shadow hover:bg-red-100">
                                        <x-svgs.trash class="w-4 h-4" />
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="inputs">
                <label for="description">Description</label>
                <textarea name="description" id="ckeditor" cols="30" rows="10">{{ old('description', $product->description) }}</textarea>
                <x-form-input-error field="description" />
            </div>

            <div class="buttons_group">
                <button type="submit">Update Product</button>
                <a href="{{ Route::has('products.index') ? route('products.index') : '#' }}" wire:navigate class="btn btn_danger">Cancel</a>
            </div>
        </form>

        <form id="delete-image-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <x-slot name="scripts">
        <x-ckeditor />

        <script src="{{ asset('assets/js/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/jquery_ui.js') }}"></script>
        <script>
            $(document).ready(function() {
            $("#sortable").sortable({
                update : function(event, ui) {
                    var photo_id = new Array();
                    $('.sortable_images').each(function() {
                        var id = $(this).attr('id');
                        photo_id.push(id);
                    });

                    $.ajax({
                        type : "POST",
                        url : "{{ url('admin/product-images/sort') }}",
                        data : {
                            "photo_id" : photo_id,
                            "_token" : "{{ csrf_token() }}"
                        },
                        dataType : "json",
                        success : function(data) {

                        },
                        error : function (data) {

                        }
                    });
                }
            });
        });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteLinks = document.querySelectorAll('.delete-image-link');
                const deleteForm = document.getElementById('delete-image-form');

                deleteLinks.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();

                        if (confirm('Are you sure you want to delete this image?')) {
                            deleteForm.setAttribute('action', this.dataset.action);
                            deleteForm.submit();
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-app-layout>

