<?php

namespace App\Observers;

use App\Models\Outlet;

class OutletObserver
{
    public function created(Outlet $outlet){
        $outlet->created_by = auth()->user()->id;
        $outlet->updated_by = auth()->user()->id;
        $outlet->save();
    }
}
