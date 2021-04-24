<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
 
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            
            return responder()
                ->error(422, 'Ocorreu um erro de validação')
                ->data(['fields' => $validator->errors()])
                ->respond();
            
            //return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Create token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                
                return responder()->error(422, 'Usuário ou senha inválida')->respond();
                
            }
        } catch (JWTException $e) {
    	
            //return $credentials;
            return responder()->error(500, 'Não foi possível gerar o token')->respond();
            
        }
 	
 		//Token created, return with success response and jwt token
        return responder()->success([
            'token' => $token,
            'user'  => [
                'id'    => auth()->user()->id,
                'name'  => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        ])->respond();
    }
 
    public function logout(Request $request)
    {
        
        try {
            
            JWTAuth::invalidate($request->token);
 
            return responder()->success(['message' => 'Logout realizado com sucesso'])->respond();
        
        } catch (JWTException $exception) {

            return responder()->error(401, 'Usuário não autenticado');

        }
    }
 
    public function me(Request $request)
    {
 
        return responder()->success([
            'user'  => [
                'id'    => auth()->user()->id,
                'name'  => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        ])->respond();
    }

    public function refresh() {

        try {
            
            $token = JWTAuth::parseToken()->refresh();
            
            return responder()->success([
                'token' => $token,
            ])->respond();
            
        } catch (JWTException $exception){
          
            return responder()->error(400, 'Token inválido')->respond();
        
        }

    }
}