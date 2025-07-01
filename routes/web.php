<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::get('/user-logout', [UserController::class, 'UserLogout']);
Route::get('/send-otp', [UserController::class, 'SendOTPCode']);
Route::post('/reset-password', [UserController::class, 'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::get('/user-profile', [UserController::class, 'UserProfile'])->middleware([TokenVerificationMiddleware::class]);
Route::put('/user-update', [UserController::class, 'UpdateProfile'])->middleware([TokenVerificationMiddleware::class]);


// Routes for Category

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-category', [CategoryController::class, 'CategoryCreate']);
    Route::get('/list-category', [CategoryController::class, 'CategoryList']);
    Route::post('/delete-category', [CategoryController::class, 'CategoryDelete']);
    Route::get('/category-by-id', [CategoryController::class, 'CategoryByID']);
    Route::post('/update-category', [CategoryController::class, 'CategoryUpdate']);
});
