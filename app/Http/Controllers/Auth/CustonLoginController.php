<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Product;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviaEmail;

class CustonLoginController extends Controller
{

    public function  loginUser(Request $request){
        
        $email          = $request->email;
        $password       = $request->password;
        $credentials = $request->only('email', 'password');

        if(Auth::attempt(['email'=> $email, 'password'=> $password])){
            $produtoModel = Product::list();
            foreach($produtoModel as $produto){
                $produtoLista[$produto->id]['nome'] = $produto->name;
                $produtoLista[$produto->id]['slug'] = $produto->slug;
            }
            session()->push('portalparceiros.lista_produto', $produtoLista);
            
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
            return response()->json($retorno);
        }

    }

    public function recoverUser(Request $request){
        $user = User::where('email', $request->email)->first();
        $novaSenha = Helper::geraSenha();
        $user->password = bcrypt($novaSenha);
        if($user->save()){
            $html = "<h3>Nova senha de acesso ao sistema de parceiros da SUPERCRÉDITO</h3>";
            $html .= "<p><strong>Senha: </strong> $novaSenha</p>";
            
            $obj = (object)[];
            $obj->html = $html;
            $obj->nome = $request->name;
            $obj->email = $request->email;
            $obj->assunto = 'Recuperação de senha | Parceiro SUPERCRÉDITO';

            Mail::to('brunotrinchao@gmail.com')->send(new EnviaEmail($obj));

            $retorno['message'] = 'Um email com as nova senha foi enviado para <b>' .$request->email.'</b>.';
            $retorno['success'] = true; 
            return response()->json($retorno);
        }
        $retorno['message'] = 'Erro ao recuperar senha.';
        $retorno['success'] = true; 
        return response()->json($retorno);
        
    }
}
