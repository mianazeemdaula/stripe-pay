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
    return view('welcome');
});
Route::get('/pay/{id}', function ($id) {
    $invoice = \App\Models\Invoice::where('invoice_id', $id)->firstOrFail();
    return view('web.init_payment', compact('id'));
});

Route::post('checkout', [BillingController::class, 'createCheckoutSession']);
Route::get('success', function() {
    return 'Payment successful!';
});
Route::get('cancel', function() {
    return 'Payment canceled!';
});
