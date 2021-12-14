<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "merchant_name",
        "created_by",
        "updated_by"
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function outlet(){
        return $this->hasMany(Outlet::class);
    }

    public function report($id){
        $report = DB::table("merchants")
        ->selectRaw("merchants.id as merchant_id ,merchants.user_id, merchants.merchant_name, date(transactions.created_at) as created_at, sum(transactions.bill_total) as omzet, transactions.outlet_id")
        ->join("transactions", "merchant_id", "=", "merchants.id")
        ->where([
            ["merchants.id", "=", $id],
            [DB::raw("month(transactions.created_at)"), "=", DB::raw("month(now())")]
        ])
        ->groupByRaw("date(transactions.created_at), transactions.merchant_id, transactions.outlet_id")
        ->simplePaginate(5);
        return $report;
    }
}
