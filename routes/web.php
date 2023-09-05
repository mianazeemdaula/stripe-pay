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

Route::get('/', function () {
    return view('web.init_payment');
});

Route::get('checkout', [BillingController::class, 'createCheckoutSession']);
Route::get('success', function() {
    return 'Payment successful!';
});
Route::get('cancel', function() {
    return 'Payment canceled!';
});
