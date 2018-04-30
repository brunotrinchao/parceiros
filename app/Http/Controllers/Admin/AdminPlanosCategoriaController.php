<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Oi\PlanosCategoria;
use Validator;
use App\Helpers\Helper;

class AdminPlanosCategoriaController extends AdminController
{
    public function index(Request $request){
        return view('admin.administracao.planos.categorias');
    }

    public function list(){
        $category = PlanosCategoria::get();
        return response()->json($category);
    }

    public function add(Request $request, PlanosCategoria $category){
        $messagesRule = [
            'name.required' => 'Nome da categoria é obrigatório.'
        ];
        $validatedData = Validator::make($request->all(), [
            'name' => 'required'
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

        $category->name = $request->name;
        $category->name = Helper:: createSlug($request->name);
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
