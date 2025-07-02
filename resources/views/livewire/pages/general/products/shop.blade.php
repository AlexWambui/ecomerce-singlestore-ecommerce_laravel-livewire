<div class="ShopPage bg-slate-50">
    <div class="Hero">
        <div class="container flex justify-center">
            <div class="search md:w-[50dvh]">
                <input type="text" name="search" id="search" placeholder="Search {{ $count_products }} products" class="w-full bg-white border border-gray-300 rounded-full py-2 px-4">
            </div>
        </div>
    </div>

    <div class="Categories">
        <div class="container">
            <div class="categories_list flex gap-4">
                <a href="#" class="text-sm">All Categories</a>
                @forelse ($categories as $category)
                    <a href="#" class="text-blue-500 text-sm">{{ $category->title }}</a>
                @empty
                    <p>No categories yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="Products">
        <div class="container">
            <div class="products_list custom_cards">
                @forelse ($products as $product)
                    <div class="product card">
                        <div class="image">
                            <img src="{{ $product->image_url }}" alt="{{ $product->slug }}">
                        </div>
                        <div class="info">
                            <a href="{{ Route::has('product.details') ? route('product.details', $product->slug) : '#' }}" class="title">{{ $product->title }}</a>
                            @if ($product->discount_price && $product->discount_price < $product->selling_price)
                                <p class="price">
                                    <span class="text-green-600 font-bold">
                                        Ksh. {{ number_format($product->effective_price, 2) }}
                                    </span>
                                    <span class="line-through text-gray-500 ml-2">
                                        {{ number_format($product->selling_price, 2) }}
                                    </span>
                                </p>
                            @else
                                <p class="price">Ksh. {{ number_format($product->effective_price, 2) }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p>No products yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
