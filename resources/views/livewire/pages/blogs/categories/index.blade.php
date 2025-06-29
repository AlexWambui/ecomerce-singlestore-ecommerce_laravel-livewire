<div class="Blogs">
    <div class="container">
        <div class="breadcrumbs">
            <a href="{{ Route::has('blogs.index') ? route('blogs.index') : '#' }}" wire:navigate>Blogs</a>
            <span>Categories</span>
        </div>

        <div class="header">
            <div class="info">
                <h2>Blog Categories</h2>
                <div class="stats">
                    <span>{{ $count_blog_categories }} {{ Str::plural('categories', $count_blog_categories) }}</span>
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
                <a href="{{ Route::has('blog-categories.create') ? route('blog-categories.create') : '#' }}" wire:navigate class="btn">New Blog Category</a>
            </div>
        </div>

        <div class="blogs small_cards">
            @forelse ($blog_categories as $category)
                <div class="blog card" wire:key="category-{{ $category->id }}">
                    <div class="details">
                        <div class="image">
                            @if ($category->image)
                                <img src="{{ $category->image_url }}" alt="{{ $category->title }}">
                            @else
                                <span>{{ substr($category->title, 0, 1) }}</span>
                            @endif
                        </div>

                        <div class="info">
                            <h3>{{ $category->title }}</h3>
                            <p>{!! Str::words($category->description, 8, '...') ?? 'Not Described' !!}</p>
                        </div>
                    </div>

                    <div class="actions">
                        <div class="crud">
                            <a href="{{ Route::has('blog-categories.edit') ? route('blog-categories.edit', ['blog_category' => $category->uuid]) : '#' }}" wire:navigate class="edit">
                                <x-svgs.edit />
                            </a>

                            <button x-data
                                    x-on:click.prevent="$wire.set('delete_category_id', {{ $category->id }}); $dispatch('open-modal', 'confirm-category-deletion')"
                                    class="delete">
                                <x-svgs.trash />
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p>No blog categories found.</p>
            @endforelse
        </div>

        {{-- ✅ Pagination --}}
        <div class="mt-6">
            {{ $blog_categories->links() }}
        </div>
    </div>

    <x-modal name="confirm-category-deletion" :show="$delete_category_id !== null" focusable>
        <div class="custom_form">
            <form wire:submit="deleteBlogCategory" @submit="$dispatch('close-modal', 'confirm-category-deletion')" class="p-6">
                <h2 class="text-lg font-semibold text-gray-900">Confirm Deletion</h2>

                <p class="mt-2 mb-4 text-sm text-gray-600">Are you sure you want to permanently delete this blog category?</p>

                <div class="mt-6 flex justify-start">
                    <button type="button" class="mr-2" x-on:click="$dispatch('close-modal', 'confirm-category-deletion')">
                        Cancel
                    </button>
                    <button type="submit" class="btn_danger">
                        Delete Blog Category
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
