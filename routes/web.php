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
    });
});

require __DIR__ . '/auth.php';
