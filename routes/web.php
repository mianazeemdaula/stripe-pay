<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login', function () {
    return view('login');
});


// Payment gateways routes
Route::get('cashapp/{invoice}', [InvoiceController::class, 'cashapp']);
Route::post('cashapp-session', [BillingController::class, 'cashAppSession']);

// Success and cancel payments routes
Route::get('invoice-success/{id}', [InvoiceController::class, 'successInvoice']);
Route::get('invoice-cancel/{id}', [InvoiceController::class, 'cancelInvoice']);
