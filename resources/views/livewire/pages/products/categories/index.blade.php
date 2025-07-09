<div class="Products ProductCategories">
    <div class="container">
        <div class="breadcrumbs">
            <a href="{{ Route::has('products.index') ? route('products.index') : '#' }}" wire:navigate>Products</a>
            <span>Categories</span>
        </div>

        <div class="header">
            <div class="info">
                <h2>Product Categories</h2>
                <div class="stats">
                    <span>{{ $count_categories }} {{ Str::plural('category', $count_categories) }}</span>
                </div>
            </div>

            <div class="search">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search by title..."
                        wire:model="search"
                        wire:keydown.enter="performSearch"
                        class="pr-8"
                    >
                    @if($search)
                        <button
                            wire:click="resetSearch"
                            class="absolute right-1 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        >
                            X
                        </button>
                    @endif
                </div>
            </div>

            <div class="button">
                <a href="{{ Route::has('product-categories.create') ? route('product-categories.create') : '#' }}" class="btn">New Product Category</a>
            </div>
        </div>

        <div class="categories_list small_cards">
            @forelse($categories as $category)
                <div class="card">
                    <div class="details">
                        <div class="image">
                            <img src="{{ $category->image_url }}" alt="{{ $category->title }}">
                        </div>

                        <div class="info">
                            <h3>{{ $category->title }}</h3>
                            <p>{!! Str::words($category->description, 5, '...') !!}</p>
                        </div>
                    </div>

                    <div class="actions">
                        <div class="others">
                            <span>{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</span>
                        </div>

                        <div class="crud">
                            <a href="{{ Route::has('product-categories.edit') ? route('product-categories.edit', $category->uuid) : '#' }}" class="edit">
                                <x-svgs.edit />
                            </a>
                            <button class="delete">
                                <x-svgs.trash />
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p>No categories found.</p>
            @endforelse
        </div>
    </div>
</div>
