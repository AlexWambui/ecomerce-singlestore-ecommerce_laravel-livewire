<x-app-layout>
    <div class="custom_form py-4 max-w-4xl mx-auto">
        <div class="header">
            <a href="{{ Route::has('product-categories.index') ? route('product-categories.index') : '#' }}" wire:navigate>
                <x-svgs.arrow-left class="w-5 h-5" />
            </a>
            <h2>Create New Product Category</h2>
        </div>

        <form action="{{ route('product-categories.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="inputs">
                <label for="title" class="required">Title</label>
                <input type="text" name="title" id="title" autocomplete="title" value="{{ old('title') }}" autofocus>
                <x-form-input-error field="title" />
            </div>

            <div class="inputs">
                <label for="image">Image (Less than 2MB)</label>
                <input type="file" name="image" id="image" accept=".png, .jpg, .jpeg, .webp, .svg">
                <x-form-input-error field="image" />
            </div>

            <div class="inputs">
                <label for="description">Description</label>
                <textarea name="description" id="ckeditor" cols="30" rows="10">{{ old('description') }}</textarea>
                <x-form-input-error field="description" />
            </div>

            <div class="buttons_group">
                <button type="submit">Save Product Category</button>
                <a href="{{ Route::has('product-categories.index') ? route('product-categories.index') : '#' }}" wire:navigate class="btn btn_danger">Cancel</a>
            </div>
        </form>
    </div>

    <x-slot name="scripts">
        <x-ckeditor />
    </x-slot>
</x-app-layout>

