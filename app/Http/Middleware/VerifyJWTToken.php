<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;

class VerifyJWTToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
    */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
        } catch (Exception $e) {
            return $e;
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $code = 1010;
                $response['code'] = $code;
                return response()->json($response);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                $code = 1010;
                $response['code'] = $code;
                return response()->json($response);
                
            }else{
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}