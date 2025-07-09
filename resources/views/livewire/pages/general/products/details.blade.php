<div class="Products">
    <section class="ProductDetails">
        <div class="details container">
            <div class="images">
                <div class="image">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->slug }}">
                    @else
                        <img src="{{ asset('assets/images/default-image.jpg') }}" alt="{{ $product->slug }}">
                    @endif
                </div>
            </div>

            <div class="content">
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

                <div class="actions">
                    <button class="btn">Add to Cart</button>
                </div>

                <div class="extras">
                    <p>
                        <span>Category</span>
                        <span>
                            : <a href="{{ Route::has('categorized-products') ? route('categorized-products') : '#' }}">
                                {{ $product->productCategory->title ?? 'uncategorized' }}
                            </a>
                        </span>
                    </p>
                    <p>
                        <span>In count</span>
                        <span>: {{ $product->stock_count }}</span>
                    </p>
                    <p>
                        <span>Measurement</span>
                        <span>: {{ ($product->product_measurement && $product->measurement_unit) ? $product->product_measurement . ' ' . $product->measurement_unit : 'N/A' }}</span>
                    </p>
                    <p>
                        <span>Rating</span>
                        <span>: {{ $product->productReviews->count() }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="more_details container">
            <div class="description">
                {!! $product->description !!}
            </div>

            <div class="reviews">

            </div>
        </div>
    </section>
</div>
