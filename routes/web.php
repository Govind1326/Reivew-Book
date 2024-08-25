<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/', [HomeController::class, 'index'])->name('home.bookfind');
Route::get('/bookdetail/{id}', [HomeController::class, 'detail'])->name('book.bookdetail');
Route::post('savereview', [BookController::class, 'saveReview'])->name('books.saveReview');

Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('register', [AccountController::class, 'register'])->name('account.register');
        Route::post('register', [AccountController::class, 'newUser'])->name('account.newUser');
        Route::get('login', [AccountController::class, 'login'])->name('account.login');
        Route::post('login', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });
    Route::group(['middleware' => 'auth'], function () {
        
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::get('changepassword', [AccountController::class, 'passwordPage'])->name('account.passwordPage');
        Route::post('changepassword', [AccountController::class, 'changepassword'])->name('account.changePassword');

        Route::group(['middleware' => 'check-admin'], function () {
            Route::get('users', [AccountController::class, 'users'])->name('account.users');
            Route::post('users', [AccountController::class, 'update'])->name('user.update');
            Route::delete('users', [AccountController::class, 'delete'])->name('user.delete');
            //books
            Route::get('books', [BookController::class, 'index'])->name('books.index');
            Route::get('books/create', [BookController::class, 'create'])->name('books.create');
            Route::get('books/update/{id}', [BookController::class, 'update'])->name('books.update');
            Route::post('books', [BookController::class, 'store'])->name('books.store');
            Route::delete('books', [BookController::class, 'destroy'])->name('books.destroy');
            //reviews
            Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
            Route::post('reviews', [ReviewController::class, 'update'])->name('reviews.update');
            Route::delete('reviews', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        });
        Route::get('myreviews', [AccountController::class, 'myreviews'])->name('myreviews.index');
        Route::post('myreviews', [ReviewController::class, 'update'])->name('myreviews.update');
        Route::delete('myreviews', [ReviewController::class, 'destroy'])->name('myreviews.destroy');
    });
});
