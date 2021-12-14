<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Outlet;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class OutletController extends Controller
{
    public function create(Request $request){
        $input = $request->only("outlet_name","merchant_id");
        $validator = Validator::make($input, [
            "outlet_name" => "required",
            "merchant_id" => "required"
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

        try {
            $merchant = Merchant::findOrFail($request->merchant_id);
        }catch(ModelNotFoundException $e){
            return response()->json([
                "message" => "bad request",
                "code" => Response::HTTP_BAD_REQUEST,
                "data" => [
                    "errors" => $e->getMessage()
                ]
            ]);
        }
        $outlet = Outlet::create([
            "outlet_name" => $request->outlet_name,
            "merchant_id" => $merchant->id
        ]);

        return response()->json([
            "message"=>"outlet created",
            "code" => Response::HTTP_CREATED,
            "data" => $outlet
        ]);
    }
}
