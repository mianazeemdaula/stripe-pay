<?php

use Illuminate\Support\Facades\Route;
use Stripe\Stripe;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\BillingController;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login',[App\Http\Controllers\AuthController::class, 'login']);
Route::post('/login',[App\Http\Controllers\AuthController::class, 'doLogin']);
Route::get('/signout',[App\Http\Controllers\AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AuthController::class, 'dashboard'])->name('dashboard');

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::resource('products',\App\Http\Controllers\User\ProductController::class);
        Route::resource('invoices',\App\Http\Controllers\User\InvoiceController::class);
    });
});


// Payment gateways routes
Route::get('cashapp/{invoice}', [InvoiceController::class, 'cashapp']);
Route::post('cashapp-session', [BillingController::class, 'cashAppSession']);

// Success and cancel payments routes
Route::get('invoice-success/{id}', [InvoiceController::class, 'successInvoice']);
Route::get('invoice-cancel/{id}', [InvoiceController::class, 'cancelInvoice']);

