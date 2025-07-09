<div class="Products ProductCategories">
    <div class="container">
        <div class="breadcrumbs">
            <a href="{{ Route::has('product-categories.index') ? route('product-categories.index') : '#' }}" wire:navigate>Categories</a>
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
                <div class="admin_product_card card">
                    <div class="details">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->slug }}" class="rounded-lg w-20 h-20 object-cover">
                        @else
                            <span class="bg-red-200 text-xl text-gray-700 rounded-lg w-20 h-20 flex items-center justify-center font-semibold uppercase">{{ substr($product->title, 0, 1) }}</span>
                        @endif

                        <div class="info">
                            <h3>{{ $product->title }}</h3>
                            @if ($product->discount_price && $product->discount_price < $product->selling_price)
                                <p class="price">
                                    <span class="selling_price">
                                        Ksh. {{ number_format($product->effective_price, 2) }}
                                    </span>
                                    <span class="discount_price">
                                        {{ number_format($product->selling_price, 2) }}
                                    </span>
                                    <span class="discount_percentage">
                                        {{ $product->discount_percentage }}% off
                                    </span>
                                </p>
                            @else
                                <p class="price">
                                    <span class="selling_price">
                                        Ksh. {{ number_format($product->effective_price, 2) }}
                                    </span>
                                </p>
                            @endif
                            <div class="extras">
                                <div class="spans_group">
                                    <span>Code: {{ $product->product_code ?? 'N/A' }}</span>
                                    <span>In Stock: {{ $product->stock_count }}</span>
                                </div>
                                <span>{{ $product->productCategory->title ?? 'Uncategorized' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <div class="others">
                            <button
                                wire:click="toggleVisibility('{{ $product->uuid }}')" wire:loading.attr="disabled" wire:target="toggleVisibility" class="{{ $product->is_visible ? 'border border-green-500 bg-green-100 text-green-900 text-xs p-1' : 'border border-red-500 bg-red-100 text-red-900 text-xs p-1' }}">
                                {{ $product->is_visible_label }}
                            </button>

                            <button
                                wire:click="toggleFeatured('{{ $product->uuid }}')"
                                wire:loading.attr="disabled"
                                wire:target="toggleFeatured"
                                class="{{ $product->is_featured ? 'border border-green-500 bg-green-100 text-green-900 text-xs p-1' : 'border border-red-500 bg-red-100 text-red-900 text-xs p-1' }}">
                                {{ $product->is_featured_label }}
                            </button>
                        </div>

                        <div class="crud">
                            <a href="{{ Route::has('products.edit') ? route('products.edit', $product->uuid) : '#' }}" class="edit">
                                <x-svgs.edit />
                            </a>
                            <button class="delete">
                                <x-svgs.trash />
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p>No products found.</p>
            @endforelse
        </div>
    </div>
</div>
