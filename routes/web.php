<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ThemeAssetController;
use Illuminate\Support\Facades\Route;

// Theme assets (CSS, images, fonts) — served from resources/themes/{slug}/
Route::get('/themes/{slug}/{path}', ThemeAssetController::class)
    ->where('path', '.*')
    ->name('theme.asset');

Route::middleware(['set.locale', 'set.theme'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::post('/language/{code}', [LanguageController::class, 'switch'])->name('language.switch');

    // Catch-all for dynamic pages (must be last)
    Route::get('/{page:slug}', [PageController::class, 'show'])->name('pages.show');
});
