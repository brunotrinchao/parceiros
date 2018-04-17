<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Validator;

class AdminCategoryController extends Controller
{
    public function list(){
        $category = Category::get();
        return response()->json($category);
    }

    public function add(Request $request, Category $category){
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
