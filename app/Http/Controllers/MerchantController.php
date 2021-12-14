<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MerchantController extends Controller
{
    public function create(Request $request){
        $input = $request->only("merchant_name");
        $validator = Validator::make($input, [
            "merchant_name" => "required"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Unprocessable entity",
                "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "data" => [
                    "errors" => $validator->getMessageBag()
                ]
            ]);
        }

        $merchant = Merchant::create([
           "merchant_name" => $request->merchant_name,
           "user_id" => auth()->user()->id
        ]);
        return response()->json([
            "message" => "created merchant",
            "code" => Response::HTTP_CREATED,
            "data" => $merchant
        ]);
    }
}
