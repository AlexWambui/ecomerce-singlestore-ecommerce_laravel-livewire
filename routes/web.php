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

Route::get('/', HomePage::class)->name('home-page');
Route::get('contact', ContactPage::class)->name('contact-page');

Route::middleware(['authenticated_user'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
});

Route::middleware(['admin_only'])->group(function () {
    Route::get('users', Users::class)->name('users.index');
    Route::get('users/create', CreateUser::class)->name('users.create');
    Route::get('users/{uuid}/edit', EditUser::class)->name('users.edit');

    Route::get('messages', ContactMessages::class)->name('contact-messages.index');
    Route::get('messages/{message}/edit', EditContactMessages::class)->name('contact-messages.edit');
});

require __DIR__ . '/auth.php';
