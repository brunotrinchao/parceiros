<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Auth;

class CustonLoginController extends Controller
{

    

    public function  loginUser(Request $request){
        $email          = $request->email;
        $password       = $request->password;

        $credenciais = $request->only('email', 'password');
        if(Auth::attempt($credenciais)){
            $retorno = [
                'data' => auth()->user(),
                'success' => true,
                'message' => 'Login efetuado com sucesso.'
            ];
            return response()->json($retorno);
        }else{
            $retorno = [
                'success' => false,
                'message' => 'Erro ao tentar logar.'
            ];
        }
        return response()->json($retorno);

    }
}
