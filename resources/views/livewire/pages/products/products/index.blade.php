<div class="Products ProductCategories">
    <div class="container">
        <div class="breadcrumbs">
            <a href="{{ Route::has('product-categories.index') ? route('product-categories.index') : '#' }}">Categories</a>
            <span>Products</span>
        </div>

        <div class="header">
            <div class="info">
                <h2>Products</h2>
                <div class="stats">
                    <span>{{ $count_products }} {{ Str::plural('product', $count_products) }}</span>
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
                <a href="{{ Route::has('products.create') ? route('products.create') : '#' }}" class="btn">New Product</a>
            </div>
        </div>

        <div class="products_list small_cards">
            @forelse($products as $product)
                <div class="card">
                    <div class="details">
                        <div class="image">
                            <img src="{{ $product->image_url }}" alt="{{ $product->title }}">
                        </div>

                        <div class="info">
                            <h3>{{ $product->title }}</h3>
                            <p>{!! Str::words($product->description, 5, '...') !!}</p>
                        </div>
                    </div>

                    <div class="actions">
                        <div class="other_actions">

                        </div>

                        <div class="crud">
                            <a href="{{ Route::has('products.edit') ? route('products.edit', $product->uuid) : '#' }}" class="btn">Edit</a>
                            <button class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            @empty
                <p>No products found.</p>
            @endforelse
        </div>
    </div>
</div>
