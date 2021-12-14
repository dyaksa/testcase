<?php

namespace App\Observers;

use App\Models\Merchant;

class MerchantObserver
{

    public function created(Merchant $merchant){
        $merchant->created_by = auth()->user()->id;
        $merchant->updated_by = auth()->user()->id;
        $merchant->save();
    }

}
