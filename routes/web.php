<?php

use Illuminate\Support\Facades\Route;
use Stripe\Stripe;
use Stripe\Payout;
use Stripe\Event;

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

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin'] ], function () {
        Route::resource('users',\App\Http\Controllers\Admin\UserController::class);
        Route::resource('withdrawals',\App\Http\Controllers\Admin\WithdrawalController::class);
        Route::resource('payouts',\App\Http\Controllers\Admin\StripPayoutController::class);
        Route::resource('payments',\App\Http\Controllers\Admin\PaymentContorller::class);
        Route::resource('square',\App\Http\Controllers\Admin\SquarePayoutController::class);
    });

    Route::group(['prefix' => 'manager', 'as' => 'manager.', 'middleware' => ['role:manager'] ], function () {
        Route::resource('withdrawals',\App\Http\Controllers\Manager\WithdrawalController::class);
        Route::resource('payments',\App\Http\Controllers\Manager\PaymentContorller::class);
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::resource('products',\App\Http\Controllers\User\ProductController::class);
        Route::resource('invoices',\App\Http\Controllers\User\InvoiceController::class);
        Route::resource('payments',\App\Http\Controllers\User\PaymentController::class);
        Route::resource('withdrawals',\App\Http\Controllers\User\WithdrawalController::class);
    });
});


// Payment gateways routes
Route::get('cashapp/{tag}', [SquareController::class, 'index']);
Route::post('cashapp/{tag}', [SquareController::class, 'getCahappPayment'])->name('sqaurecashapp');
Route::any('sqaure/cashapp', [SquareController::class, 'processCashAppPayment']);


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
    $invoice = \App\Models\Invoice::find(763);
    Stripe::setApiKey(env('STRIPE_SECRET'));
    return Event::retrieve($invoice->tx_id);
});

Route::get('/asldjaljsflasdj', function(){

    return (new \App\Services\SquareService)->payments();

    Stripe::setApiKey(env('STRIPE_SECRET'));
    // Stripe get events with sort to the latest date
    // $vents = \Stripe\Event::all(['limit' => 50, 'type' => 'payment_intent.cancelled']);
    // dd($vents);
    // $payments = \Stripe\PaymentIntent::all([
    //     'limit' => 10, // Number of payments to retrieve
    // ]);
    // return $payments;
    $account = \Stripe\Account::retrieve();
    return ($account);
    $status = $account->charges_enabled; // true if charges are enabled
    $payouts = $account->payouts_enabled; // true if payouts are enabled
    // $requirements = $account->requirements->currently_due; // Any missing requirements

    return [
        'charges_enabled' => $status,
        'payouts_enabled' => $payouts,
        'missing_requirements' => $requirements ?? [],
    ];
});


Route::get('app/logs', function(){
    $filePath = storage_path("logs/laravel.log");
    if (\File::exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'text/plain',
        ]);
    }
    return response()->json(['error' => 'Log file not found'], 404);
});
