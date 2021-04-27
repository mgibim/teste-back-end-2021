<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Transformers\UserTransformer;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{
 
    public function login(UserRequest $request)
    {

        $credentials = $request->only('email', 'password');
        
        try {

            if (! $token = JWTAuth::attempt($credentials)) {
                return responder()->error(401, 'Usuário ou senha inválida')->respond(401);
            }

        } catch (JWTException $e) {
    	
            return responder()->error(500, 'Não foi possível gerar o token')->respond(500);

        }
 	
        return responder()->success([
            'token' => $token,
            'user'  => (new UserTransformer)->transform(Auth::user())
        ])->respond(200);

    }
 
    public function logout(Request $request)
    {
        
        try {
            
            JWTAuth::invalidate($request->token);
 
            return responder()->success(['message' => 'Logout realizado com sucesso'])->respond(200);
        
        } catch (JWTException $exception) {

            return responder()->error(401, 'Usuário não autenticado')->respond(401);

        }

    }
 
    public function me(Request $request)
    {

        return responder()->success([
            'user' => (new UserTransformer)->transform(Auth::user()),
        ])->respond(200);

    }

    public function refresh() {

        try {
            
            $token = JWTAuth::parseToken()->refresh();

            return responder()->success(['token' => $token])->respond(200);
            
        } catch (JWTException $exception){
          
            return responder()->error(401, 'Token inválido')->respond(401);
        
        }

    }
}