<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

// Laravel Vue Page Routing

Route::get('/', [HomeController::class, 'HomePage']);

// For User 

Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::get('/user-logout', [UserController::class, 'UserLogout']);
Route::get('/send-otp', [UserController::class, 'SendOTPCode']);
Route::get('/verify-otp', [UserController::class, 'VerifyOTP']);

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/reset-password', [UserController::class, 'ResetPassword']);
    Route::get('/user-profile', [UserController::class, 'UserProfile']);
    Route::put('/user-update', [UserController::class, 'UpdateProfile']);
});


// Routes for Category

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-category', [CategoryController::class, 'CategoryCreate']);
    Route::get('/list-category', [CategoryController::class, 'CategoryList']);
    Route::post('/delete-category', [CategoryController::class, 'CategoryDelete']);
    Route::get('/category-by-id', [CategoryController::class, 'CategoryByID']);
    Route::post('/update-category', [CategoryController::class, 'CategoryUpdate']);
});


// Routes for Customer

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-customer', [CustomerController::class, 'CustomerCreate']);
    Route::get('/list-customer', [CustomerController::class, 'CustomerList']);
    Route::post('/delete-customer', [CustomerController::class, 'CustomerDelete']);
    Route::get('/customer-by-id', [CustomerController::class, 'CustomerByID']);
    Route::post('/update-customer', [CustomerController::class, 'CustomerUpdate']);
});
// Routes for Product

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-product', [ProductController::class, 'ProductCreate']);
    Route::get('/list-product', [ProductController::class, 'ProductList']);
    Route::post('/delete-product', [ProductController::class, 'ProductDelete']);
    Route::get('/product-by-id', [ProductController::class, 'ProductByID']);
    Route::post('/update-product', [ProductController::class, 'ProductUpdate']);
});
// Routes for Invoice

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-invoice', [InvoiceController::class, 'CreateInvoice']);
    Route::get('/select-invoice', [InvoiceController::class, 'SelectInvoice']);
    Route::post('/delete-invoice', [InvoiceController::class, 'InvoiceDelete']);
    Route::get('/invoice-details', [InvoiceController::class, 'InvoiceDetails']);
});
// Routes for Dashboard

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::get('/summary', [DashboardController::class, 'Summary']);
});



// For employee

Route::post('/employee-login', [EmployeeController::class, 'Login']);
Route::post('/employee-logout', [EmployeeController::class, 'Logout']);

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/employee-register', [EmployeeController::class, 'Register']);
    Route::get('/employee-profile', [EmployeeController::class, 'Profile']);
});
