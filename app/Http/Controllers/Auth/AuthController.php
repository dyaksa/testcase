<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request){
        $input = $request->only("email","password");
        $validator = Validator::make($input, [
            "email" => "required|email",
            "password" => "required|min:6|max:20"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "unprocessable entity",
                "code"=>Response::HTTP_UNPROCESSABLE_ENTITY,
                "data" => [
                    "errors" => $validator->getMessageBag()
                ]
            ]);
        }

        if(!$token = auth()->attempt($input)){
            return response()->json([
                "message" => "Unauthorized",
                "code" => Response::HTTP_UNAUTHORIZED,
                "data" => [
                    "errors" => ["is_valid" => false]
                ]
            ]);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request){
        $input = $request->only("email","user_name","password","name");
        $validator = Validator::make($input, [
            "name" => "required",
            "email" => "required|email|unique:users",
            "user_name" => "required|unique:users|min:5|max:10",
            "password" => "required|min:6|max:20"
        ]);
        if ($validator->fails()){
            return response()->json([
                "message" => "Unprocessable Entity",
                "code"=> Response::HTTP_UNPROCESSABLE_ENTITY,
                "data" => [
                    "errors" => $validator->getMessageBag()
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "user_name" => $request->user_name,
            "password" => bcrypt($request->password)
        ]);

        return response()->json([
            "status"=> true,
            "code"=> Response::HTTP_CREATED,
            "data"=> $user
        ]);
    }

    protected function createNewToken($token){
        return response()->json([
            "message" => "success auth",
            "code" => Response::HTTP_OK,
            "data" => [
                "access_token" => $token,
                "token_type" => "Bearer",
                "user" => auth()->user()
            ]
        ]);
    }
}
