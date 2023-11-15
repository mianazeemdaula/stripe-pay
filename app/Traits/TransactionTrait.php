<?php

namespace App\Traits;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

trait TransactionTrait
{
    public function updateBalance(float $amount, string $description = null)
    {
        $transaction =  $this->transaction;
        $balance = 0;
        if($transaction){
            $balance = $transaction->balance;
        }
        $debit = $credit = 0;
        if($amount > 0){
            $balance = $balance + $amount;
            $credit = $amount;
        }else{
            $balance = $balance - abs($amount);
            $debit = abs($amount);
        }
        $transaction = new Transaction([
            'debit' => $debit,
            'credit' => $credit,
            'balance' =>  $balance,
            'description' => $description,
            'user_id' => $this->id,
        ]);

        // Associate the transaction with the user
        $this->transactions()->save($transaction);
    }
}
