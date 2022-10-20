<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\GoogleController;
use App\Http\Controllers\Api\V1\Auth\FacebookController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;


Route::group(['prefix' => '/customer'], function ()
{
    Route::group(['as' => 'customer.'], function ()
    {
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

        Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');
        Route::post('/verifyOTP', [RegisterController::class, 'verifyOTP'])->name('auth.verifyOTP');
        Route::post('/sendOTP', [RegisterController::class, 'sendOTP'])->name('auth.sendOTP');

        Route::post('/forgotPassword', [ForgotPasswordController::class, 'forgotPassword'])->name('auth.forgotPassword');

        Route::post('/resetPassword', [ResetPasswordController::class, 'resetPassword'])->name('auth.resetPassword');


        //facebook
        Route::get('/auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('auth.facebook');
        Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

        //Google
        Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
        Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

        Route::group(['middleware' => ['auth.api:api']], function ()
        {
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('/changePassword', [ResetPasswordController::class, 'changePassword'])->name('auth.changePassword');


            // Categories
            Route::get('categories', [CategoryController::class, 'index']);
            Route::get('categories/{category}', [CategoryController::class, 'show']);

            // Products
            Route::get('products', [ProductController::class, 'index']);
            Route::get('products/{productId}', [ProductController::class, 'show']);
            Route::get('products-with-category/{categoryId}', [ProductController::class, 'showProductsWithCatgeory']);
        });
    });

});
