<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationAPIMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::get('/user-logout', [UserController::class, 'UserLogout']);
Route::get('/send-otp', [UserController::class, 'SendOTPCode']);
Route::get('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::post('/reset-password', [UserController::class, 'ResetPassword'])->middleware([TokenVerificationAPIMiddleware::class]);
Route::get('/user-profile', [UserController::class, 'UserProfile'])->middleware([TokenVerificationAPIMiddleware::class]);
Route::put('/user-update', [UserController::class, 'UpdateProfile'])->middleware([TokenVerificationAPIMiddleware::class]);


// Category Routes

Route::middleware([TokenVerificationAPIMiddleware::class])->group(function () {
    Route::post('/create-category', [CategoryController::class, 'CategoryCreate']);
    Route::get('/list-category', [CategoryController::class, 'CategoryList']);
    Route::post('/delete-category', [CategoryController::class, 'CategoryDelete']);
    Route::get('/category-by-id', [CategoryController::class, 'CategoryByID']);
    Route::post('/update-category', [CategoryController::class, 'CategoryUpdate']);
});

// Customer Routes

Route::middleware([TokenVerificationAPIMiddleware::class])->group(function () {
    Route::post('/create-customer', [CustomerController::class, 'CustomerCreate']);
    Route::get('/list-customer', [CustomerController::class, 'CustomerList']);
    Route::post('/delete-customer', [CustomerController::class, 'CustomerDelete']);
    Route::get('/customer-by-id', [CustomerController::class, 'CustomerByID']);
    Route::post('/update-customer', [CustomerController::class, 'CustomerUpdate']);
});
