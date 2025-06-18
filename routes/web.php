<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\General\HomePage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('home', HomePage::class)->name('home-page');
