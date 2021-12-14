<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user){
        $user->created_by = $user->id;
        $user->updated_by = $user->id;
        $user->save();
    }
}
