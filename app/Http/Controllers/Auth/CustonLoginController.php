<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Product;
use App\Helpers\Helper;

class CustonLoginController extends Controller
{

    

    public function  loginUser(Request $request){
        $email          = $request->email;
        $password       = $request->password;

        $credenciais = $request->only('email', 'password');
        if(Auth::attempt($credenciais)){
            $produtoModel = Product::list();
        
            foreach($produtoModel as $produto){
                $produtoLista[$produto->id]['nome'] = $produto->name;
                $produtoLista[$produto->id]['slug'] = Helper::createSlug($produto->name);
            }
            session()->push('portalparceiros.lista_produto', $produtoLista);
            // dd(session()->get('portalparceiros'));
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
