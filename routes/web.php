<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UnifiedLogin;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;





// Laravel Vue Login Page Routing

Route::get('/', [HomeController::class, 'HomePage'])->name('home');
Route::get('/login-page', [UserController::class, 'LoginPage'])->name('login-page');
Route::get('/RegistrationPage', [UserController::class, 'registration'])->name('registratin');
Route::get('/reset-password-page', [UserController::class, 'ResetPasswordPage'])->name('reset-password-page');
Route::get('/send-otp-page', [UserController::class, 'SendOtpPage'])->name('send-otp-page');
Route::get('/verify-otp-page', [UserController::class, 'VerifyOtpPage'])->name('verify-otp-page');



// Laravel Vue Dashboard Page Routing

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'DashboardPage'])->name('DashboardPage');
    Route::get('/CategoryPage', [CategoryController::class, 'CategoryPage'])->name('CategoryPage');
    Route::get('/category-save', [CategoryController::class, 'CategorySavePage'])->name('category-save');
    Route::get('/CustomerPage', [CustomerController::class, 'CustomerPage'])->name('CustomerPage');
    Route::get('/customer-save', [CustomerController::class, 'CustomerSave'])->name('customer-save');
    Route::get('/ProductPage', [ProductController::class, 'ProductPage'])->name('ProductPage');
    Route::get('/product-save', [ProductController::class, 'ProductSavePage'])->name('product-save');
    Route::get('/SalePage', [InvoiceController::class, 'SalePage'])->name('SalePage');
    Route::get('/InvoiceListPage', [InvoiceController::class, 'InvoiceListPage'])->name('InvoiceListPage');
    Route::get('/profile', [UserController::class, 'ProfilePage'])->name('profile');
});

// Unified Login

Route::post('/login', [UnifiedLogin::class, 'UnifiedLogin'])->name('login');
Route::post('/logout', [UnifiedLogin::class, 'UnifiedLogout'])->name('logout');
// For User 

Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::get('/user-logout', [UserController::class, 'UserLogout']);
Route::get('/send-otp', [UserController::class, 'SendOTPCode']);
Route::get('/verify-otp', [UserController::class, 'VerifyOTP']);

Route::post('/reset-password', [UserController::class, 'ResetPassword']);
Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::get('/user-profile', [UserController::class, 'UserProfile']);
    Route::put('/user-update', [UserController::class, 'UpdateProfile']);
});


// Routes for Category

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-category', [CategoryController::class, 'CategoryCreate']);
    Route::get('/list-category', [CategoryController::class, 'CategoryList']);
    Route::post('/delete-category/{id}', [CategoryController::class, 'CategoryDelete']);
    Route::get('/category-by-id', [CategoryController::class, 'CategoryByID']);
    Route::post('/update-category', [CategoryController::class, 'CategoryUpdate']);
});


// Routes for Customer

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-customer', [CustomerController::class, 'CustomerCreate']);
    Route::get('/list-customer', [CustomerController::class, 'CustomerList']);
    Route::post('/delete-customer/{id}', [CustomerController::class, 'CustomerDelete']);
    Route::get('/customer-by-id', [CustomerController::class, 'CustomerByID']);
    Route::post('/update-customer', [CustomerController::class, 'CustomerUpdate']);
});
// Routes for Product

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::get('/list-product', [ProductController::class, 'ProductList']);
    Route::post('/create-product', [ProductController::class, 'ProductCreate']);
    Route::post('/delete-product/{id}', [ProductController::class, 'ProductDelete']);
    Route::get('/product-by-id', [ProductController::class, 'ProductByID']);
    Route::post('/update-product', [ProductController::class, 'ProductUpdate']);
});
// Routes for Invoice

Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::post('/create-invoice', [InvoiceController::class, 'CreateInvoice']);
    Route::get('/select-invoice', [InvoiceController::class, 'SelectInvoice']);
    Route::get('/invoice-list', [InvoiceController::class, 'InvoiceList']);
    Route::post('/delete-invoice/{invoice_id}', [InvoiceController::class, 'InvoiceDelete']);

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
