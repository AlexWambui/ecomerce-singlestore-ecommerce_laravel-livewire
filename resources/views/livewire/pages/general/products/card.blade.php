<div class="product_card card">
    <div class="image">
        @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->slug }}">
        @else
            <img src="{{ asset('assets/images/default-image.jpg') }}" alt="{{ $product->slug }}">
        @endif

        @if ($product->stock_count > 0)
            <div class="cart_btn">
                <form action="{{ Route::has('cart.store') ? route('cart.store', $product->id) : '#' }}" method="POST">
                    @csrf
                    <button type="submit" title="Add to Cart">
                        <x-svgs.add-to-shopping-cart />
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="content">
        <div class="extras">
            <span>
                <a href="{{ Route::has('categorized-products') ? route('categorized-products') : '#' }}">
                    {{ $product->productCategory->title ?? 'uncategorized' }}
                </a>
            </span>
            @if($product->stock_count <= 0)
                <span class="danger">out of stock</span>
            @endif
        </div>

        <h3 class="title">
            @if($product->slug)
                <a href="{{ Route::has('product-details-page') ? route('product-details-page', $product->slug) : '#' }}" wire:navigate>
                    {{ $product->title }}
                </a>
            @else
                <span>{{ $product->title }} ---</span>
            @endif
        </h3>

        <p class="product_price">
            <span class="selling_price">
                Ksh. {{ number_format($product->effective_price, 2) }}
            </span>
            @if ($product->discount_price && $product->discount_price < $product->selling_price)
                <span class="discount_price">
                    {{ number_format($product->selling_price, 2) }}
                </span>
                <span class="discount_percentage">
                    {{ $product->discount_percentage }}% off
                </span>
            @endif
        </p>
    </div>
</div>
