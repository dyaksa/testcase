<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Outlet;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function store(Request $request){
        $input = $request->only("merchant_id","outlet_id","bill_total");
        $validator = Validator::make($input, [
            "merchant_id" => "required",
            "outlet_id" => "required",
            "bill_total" => "required"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "unprocessable entity",
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "data" => [
                    "errors" => $validator->getMessageBag()
                ]
            ]);
        }

        $merchant = $this->findMerchantById($request->merchant_id, auth()->user()->id);
        if ($merchant == null) {
            return response()->json([
                "message" => "user cannot access merchant",
                "code" => Response::HTTP_BAD_REQUEST,
                "data" => [
                    "is_access" => false
                ]
            ]);
        }
        $outlet = $this->findOutletById($request->outlet_id, $merchant->id);
        if($outlet == null){
            return response()->json([
                "message" => "merchant id cannot access outlet",
                "code" => Response::HTTP_BAD_REQUEST,
                "data" => [
                    "is_access" => false
                ]
            ]);
        }
        $transactions = Transaction::create([
            "merchant_id" => $request->merchant_id,
            "outlet_id" => $request->outlet_id,
            "bill_total" => $request->bill_total
        ]);
        return response()->json([
            "message" => "success created transactions",
            "code" => Response::HTTP_CREATED,
            "data" => $transactions
        ]);


    }

    public function report_merchant($id){
        try {
            $merchant = Merchant::where("id", $id )->firstOrFail();
            if ($merchant->user_id != auth()->user()->id){
                return response()->json([
                    "message" => "bad request",
                    "code" => Response::HTTP_BAD_REQUEST,
                    "data" => [
                        "errors" => [
                            "is_access" => false
                        ]
                    ]
                ]);
            }
        }catch(ModelNotFoundException $e){
            return response()->json([
                "message" => $e->getMessage(),
                "code" => Response::HTTP_BAD_REQUEST,
                "data" => [
                    "errors" => [
                        "is_access" => false
                    ]
                ]
            ]);
        }

        $m = new Merchant;
        $report = $m->report($id);
        return response()->json([
            "message"=>"get all report",
            "code" => Response::HTTP_OK,
            "data" => $report
        ]);
    }

    public function report_outlet($mid,$oid){
        $merchant = $this->findMerchantById($mid,auth()->user()->id);
        if($merchant == null){
            return response()->json([
                "message" => "user not have merchant",
                "code" => Response::HTTP_NOT_FOUND,
                "data" => [
                    "is_access" => false
                ]
            ]);
        }

        $outlet = $this->findOutletById($oid, $merchant->id);
        if ($outlet == null){
            return response()->json([
                "message" => "merchant not have outlet",
                "code" => Response::HTTP_NOT_FOUND,
                "data" => [
                    "is_access" => false
                ]
            ]);
        }

        $report = $outlet->report($oid);
        return response()->json([
            "message" => "detail report",
            "code" => Response::HTTP_OK,
            "data" => $report
        ]);
    }

    protected function findOutletById($id, $merchantid){
        $outlet = Outlet::where(["id" => $id, "merchant_id" => $merchantid])->first();
        if ($outlet == null) {
            return null;
        }
        $outlet = new Outlet;
        return $outlet;
    }

    protected function findMerchantById($id,$userid){
        $merchant = Merchant::where(["id" => $id, "user_id" => $userid])->first();
        if($merchant == null){
            return null;
        }
        return $merchant;
    }
}
