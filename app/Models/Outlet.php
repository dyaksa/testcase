<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Merchant;
use Illuminate\Support\Facades\DB;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        "merchant_id",
        "outlet_name",
        "created_by",
        "updated_by"
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function report($id){
        $report = DB::table("outlets")
        ->selectRaw("outlets.id as id, merchants.id as merchant_id, date(transactions.created_at) as created_at, sum(transactions.bill_total) as omzet, merchants.merchant_name, outlets.outlet_name")
        ->join("transactions", "transactions.outlet_id", "=", "outlets.id")
        ->join("merchants","outlets.merchant_id", "=", "merchants.id")
        ->where([
            ["outlets.id","=",$id],
            [DB::raw("month(transactions.created_at)"), "=", DB::raw("month(now())")]
        ])
        ->groupByRaw("date(transactions.created_at), outlets.id ")
        ->simplePaginate(5);
        return $report;
    }
}
