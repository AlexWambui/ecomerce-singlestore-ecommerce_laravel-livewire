<div class="Products CategorizedProducts">
    <section class="HeroSection">
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ Route::has('shop-page') ? route('shop-page') : '#' }}" wire:navigate>Shop</a>
                <span>{{ $category->title }}</span>
            </div>

            <h1 class="title">{{ $category->title }}</h1>

            <p>We have <b>{{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}</b> in this category</p>
        </div>
    </section>

    <section class="Categories">
        <div class="container">
            <div class="categories_list flex gap-4">
                @if($categories->count() > 0)
                    <a href="{{ Route::has('shop-page') ? route('shop-page') : '#' }}" class="text-sm" wire:navigate>All Categories</a>
                    @foreach ($categories as $category)
                        <a href="{{ Route::has('products-categorized-page') ? route('products-categorized-page', $category->slug) : '#' }}" class="text-blue-500 text-sm" wire:navigate>
                            {{ $category->title }}
                        </a>
                    @endforeach
                @else
                    <p>No categories yet.</p>
                @endif
            </div>
        </div>
    </section>

    <section class="CategorizedProductsList">
        <div class="container">
            <div class="products_list custom_cards">
                @forelse ($products as $product)
                    @include('livewire.pages.general.products.card')
                @empty
                    <p>No avaible products for this category</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
