<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;

class StripeController extends Controller
{
    // get all accounts assoicate to stripe
    public function getAllAccounts()  {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $accounts = Account::all();
        return response()->json($accounts, 200);
    }

    // get account by id
    public function getAccount($id){
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $account = Account::retrieve($id);
        return response()->json($account, 200);
    }

    // create account
    public function setExternalAccount(){
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $account = Account::createExternalAccount(
            'acct_1J5X2t2eZvKYlo2C',
            [
                'external_account' => [
                    'country' => 'US',
                    'currency' => 'usd',
                    'routing_number' => '110000000',
                    'account_number' => '000123456789',
                    'account_holder_name' => 'Jenny Rosen',
                ],
            ]
        );
    }
}
