<x-slot name="head">
    {{-- SEO --}}
    <meta name="description" content="Buy the best products in Nairobi, Kenya. We offer the best electronics, fashion, and home goods. Shop with us today.">
    <meta name="keywords" content="ecommerce, online shopping, deals">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Buy products at the best price with fast delivery.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/images/default-og.png') }}">

    <title>{{ config('app.name') }} | Best Products in Nairobi, Kenya</title>
</x-slot>

<div class="HomePage">
    <section class="Hero">
        <div class="container">
            <div class="hero_wrapper">
                <div class="overlay"></div>

                <div class="text">
                    <h1>{{ config('app.name') }}</h1>
                    <p class="sub_title">{{ config('app.slogan') }}</p>
                    <p class="punchline">Shop with us today and get the best products at the best price.</p>
                </div>

                <div
                    class="slideshow"
                    x-data="{
                        images: [
                            '{{ asset('assets/images/ecommerce.jpg') }}',
                            '{{ asset('assets/images/hero-2.jpg') }}',
                            '{{ asset('assets/images/hero-3.jpg') }}',
                        ],
                        current: 0,
                        delay: 5000,
                        start() {
                            setInterval(() => {
                                this.current = (this.current + 1) % this.images.length;
                            }, this.delay);
                        }
                    }"
                    x-init="start()"
                >
                    <template x-for="(image, index) in images" :key="index">
                        <div
                            class="absolute inset-0 transition-opacity duration-1000"
                            x-show="current === index"
                            x-transition:enter="opacity-0"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="opacity-100"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                        >
                            <img
                                :src="image"
                                alt="{{ config('app.name') }} Online Shopper"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            />
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <section class="FeaturedProducts">
        <div class="container">
            <div class="section_header">
                <h2>Most Popular</h2>
                <a href="{{ Route::has('products-page') ? route('products-page') : '#' }}">View All</a>
            </div>

            <div class="products_list custom_cards">
                @forelse($products as $product)
                    @include('livewire.pages.products.products.card')
                @empty
                    <p>No products yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
