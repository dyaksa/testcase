<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction){
        $transaction->created_by = auth()->user()->id;
        $transaction->updated_by = auth()->user()->id;
        $transaction->save();
    }
}
