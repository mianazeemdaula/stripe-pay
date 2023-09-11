<?php

namespace App\Traits;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

trait TransactionTrait
{
    public function updateBalance(float $amount, string $description = null)
    {
        $transaction = new Transaction([
            'amount' => $amount,
            'type' => $amount > 0 ? 'income' : 'withdrawal',
            'description' => $description,
            'user_id' => $this->id,
        ]);

        // Associate the transaction with the user
        $this->transactions()->save($transaction);

        // Update the user's balance
        if($amount > 0){
            $this->balance += $amount;
        }else{
            $this->balance -= $amount;
        }
        $this->save();
    }
}
