<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\General\HomePage;
use App\Livewire\Pages\General\ContactPage;
use App\Livewire\Pages\Dashboard\Index as Dashboard;
use App\Livewire\Pages\Users\Index as Users;
use App\Livewire\Pages\Users\Form as CreateUser;
use App\Livewire\Pages\Users\Form as EditUser;
use App\Livewire\Pages\ContactMessages\Index as ContactMessages;
use App\Livewire\Pages\ContactMessages\Edit as EditContactMessages;
use App\Livewire\Pages\Blogs\Categories\Index as BlogCategoriesIndex;
use App\Http\Controllers\Blogs\BlogCategoryController;
use App\Livewire\Pages\Blogs\Blogs\Index as BlogsIndex;
use App\Http\Controllers\Blogs\BlogController;
use App\Livewire\Pages\DeliveryLocations\Regions\Index as DeliveryRegionsIndex;
use App\Livewire\Pages\DeliveryLocations\Regions\Form as CreateDeliveryRegion;
use App\Livewire\Pages\DeliveryLocations\Regions\Form as EditDeliveryRegion;
use App\Livewire\Pages\DeliveryLocations\Areas\Form as CreateDeliveryArea;
use App\Livewire\Pages\DeliveryLocations\Areas\Form as EditDeliveryArea;
use App\Livewire\Pages\Products\Categories\Index as ProductCategoriesIndex;
use App\Http\Controllers\Products\ProductCategoryController;
use App\Livewire\Pages\Products\Products\Index as ProductsIndex;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\ProductImageController;

Route::get('/', HomePage::class)->name('home-page');
Route::get('contact', ContactPage::class)->name('contact-page');

Route::middleware(['authenticated_user'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
});

Route::middleware(['admin_only'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('users', Users::class)->name('users.index');
        Route::get('users/create', CreateUser::class)->name('users.create');
        Route::get('users/{uuid}/edit', EditUser::class)->name('users.edit');

        Route::get('messages', ContactMessages::class)->name('contact-messages.index');
        Route::get('messages/{message}/edit', EditContactMessages::class)->name('contact-messages.edit');

        Route::get('blog-categories', BlogCategoriesIndex::class)->name('blog-categories.index');
        Route::get('blog-categories/create', [BlogCategoryController::class, 'create'])->name('blog-categories.create');
        Route::post('blog-categories', [BlogCategoryController::class, 'store'])->name('blog-categories.store');
        Route::get('blog-categories/{blog_category}/edit', [BlogCategoryController::class, 'edit'])->name('blog-categories.edit');
        Route::patch('blog-categories/{blog_category}', [BlogCategoryController::class, 'update'])->name('blog-categories.update');

        Route::get('blogs', BlogsIndex::class)->name('blogs.index');
        Route::get('blogs/create', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
        Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
        Route::patch('blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');

        Route::get('delivery-regions', DeliveryRegionsIndex::class)->name('delivery-regions.index');
        Route::get('delivery-regions/create', CreateDeliveryRegion::class)->name('delivery-regions.create');
        Route::get('delivery-regions/{delivery_region}/edit', EditDeliveryRegion::class)->name('delivery-regions.edit');

        Route::get('delivery-areas/create/{region_uuid}', CreateDeliveryArea::class)->name('delivery-areas.create');
        Route::get('delivery-areas/{area_uuid}/edit', EditDeliveryArea::class)->name('delivery-areas.edit');

        Route::get('product-categories', ProductCategoriesIndex::class)->name('product-categories.index');
        Route::get('product-categories/create', [ProductCategoryController::class, 'create'])->name('product-categories.create');
        Route::post('product-categories', [ProductCategoryController::class, 'store'])->name('product-categories.store');
        Route::get('product-categories/{product_category}/edit', [ProductCategoryController::class, 'edit'])->name('product-categories.edit');
        Route::patch('product-categories/{product_category}', [ProductCategoryController::class, 'update'])->name('product-categories.update');

        Route::get('products', ProductsIndex::class)->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::patch('products/{product}', [ProductController::class, 'update'])->name('products.update');

        Route::delete('product-images/{product_image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy');
        Route::post('product-images/sort', [ProductImageController::class, 'sort'])->name('product-images.sort');
    });
});

require __DIR__ . '/auth.php';
