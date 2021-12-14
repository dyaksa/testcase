<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        }catch(Exception $e){
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    "message" => "token invalid",
                    "code" => Response::HTTP_UNAUTHORIZED,
                    "data" => [
                        "errors" => [
                            "is_valid" => false
                        ]
                    ]
                ]);
            }
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    "message" => "token expired",
                    "code" => Response::HTTP_UNAUTHORIZED,
                    "data" => [
                        "errors" => [
                            "is_valid" => false
                        ]
                    ]
                ]);
            }
            return response()->json([
                "message" => "Authorization token not found",
                "code" => Response::HTTP_UNAUTHORIZED,
                "data" => [
                    "errors" => [
                        "is_valid" => false
                    ]
                ]
            ]);
        }
        return $next($request);
    }
}
