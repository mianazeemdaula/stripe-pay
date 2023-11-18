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
use App\Http\Controllers\SquareController;
use App\Http\Controllers\StripeController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login',[App\Http\Controllers\AuthController::class, 'login'])->name('login');
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
Route::get('sqaure', [SquareController::class, 'index']);
Route::get('stripe/accounts', [StripeController::class, 'getAllAccounts']);
Route::get('stripe/account/{id}', [StripeController::class, 'getAccount']);
Route::get('stripe/set-account', [StripeController::class, 'setExternalAccount']);

// Payment gateways routes
Route::get('invoice/{tag}', [InvoiceController::class, 'cashapp']);
Route::post('cashapp-session', [BillingController::class, 'cashAppSession']);

// Success and cancel payments routes
Route::get('invoice-success/{id}', [InvoiceController::class, 'successInvoice']);
Route::get('invoice-cancel/{id}', [InvoiceController::class, 'cancelInvoice']);

// Dispute routes
Route::get('report/{tag}', [App\Http\Controllers\DisputeController::class, 'create']);
Route::post('report/{tag}', [App\Http\Controllers\DisputeController::class, 'store']);

// Payout routes

Route::get('payout/{tag}', [App\Http\Controllers\PayoutController::class, 'create']);
Route::post('payout/{tag}', [App\Http\Controllers\PayoutController::class, 'store']);

Route::get('datafeed', function(){
    
    $invoices = \App\Models\Invoice::all();
    foreach($invoices as $invoice){
        $invoice->tax = ($invoice->amount_paid * 0.029) + 0.30;
        $invoice->save();
    }
    $userIds =  \App\Models\Invoice::distinct('user_id')->pluck('user_id');
    foreach($userIds as $id){
        $amountPaid = \App\Models\Invoice::where('user_id',$id)->sum('amount_paid');
        $tax = \App\Models\Invoice::where('user_id',$id)->sum('tax');
        $user = \App\Models\User::find($id);
        $user->balance = $amountPaid - $tax;
        $user->save();
    }
    return 'done';
});

Route::get('/test', function(){
    $user = \App\Models\User::find(2);
    $user->updateBalance(10, 'Add balance');
    $user->updateBalance(-5, 'withdraw balance');
    return 'done';
});

