<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BancosCategoria;
use Validator;

class AdminBancosCategoriasController extends Controller
{
    public function get(Request $request, BancosCategoria $category){
        return json_decode(json_encode(BancosCategoria::where('product_id', $request->product_id)->orderBy('name')->get()));
    }

    public function add(Request $request, BancosCategoria $category){
        
        $messagesRule = [
            'product_id.required' => 'Produto é obrigatório.',
            'name.required' => 'Nome da categoria é obrigatório.'
        ];
        $validatedData = Validator::make($request->all(), [
            'product_id' => 'required',
            'name' => 'required',
        ], $messagesRule);
        
        if($validatedData->fails()){
            $arrMsg;
            foreach(json_decode($validatedData->messages()) as $t){
                $arrMsg[] = '- '. $t[0];
            }
            $retorno['message'] = implode('<br>', $arrMsg);
            $retorno['success'] = false; 
            return response()->json($retorno);
        }

        $category->product_id = $request->product_id;
        $category->name = $request->name;
        
        if($category->save()){
            $dados['success'] = true;
            $dados['message'] = 'Categoria cadastrada com sucesso.';
            return response()->json($dados);
        }
        $dados['success'] = false;
        $dados['message'] = 'Erro ao cadastrar categoria.';
        return response()->json($dados);
    }
}
