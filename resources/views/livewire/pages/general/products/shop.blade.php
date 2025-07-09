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
                @if($categories->count() > 0)
                    <a href="{{ Route::has('shop-page') ? route('shop-page') : '#' }}" class="text-sm" wire:navigate>All Categories</a>
                    @foreach ($categories as $category)
                        <a href="#" class="text-blue-500 text-sm">{{ $category->title }}</a>
                    @endforeach
                @else
                    <p>No categories yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="Products">
        <div class="container">
            <div class="products_list custom_cards">
                @forelse($products as $product)
                    @include('livewire.pages.general.products.card')
                @empty
                    <p>No products yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
