<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    public function handle($request, Closure $next)
    {
        try {
 
            $user = JWTAuth::parseToken()->authenticate();
 
        } catch (Exception $e) {
 
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                
                return responder()->error(401, 'Usuário não autenticado')->respond(401);
            
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
              
                return responder()->error(401, 'Token expirado')->respond(401);
            
            } else{
              
                return responder()->error(401, 'Token de autorização não encontrado')->respond(401);

            }
        }
 
        return $next($request);
 
    }
}