<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentHooksController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('stripe-pay-hooks', [PaymentHooksController::class, 'stripePayment']);
Route::post('stripe-paylink-hooks', [PaymentHooksController::class, 'stripeLinkPayment']);
Route::post('square-hooks', [PaymentHooksController::class, 'squareCashAppPayment']);

// Path: routes/api.php
Route::get('stripe-payments', [PaymentHooksController::class, 'getAllPaymentsWithMetadata']);