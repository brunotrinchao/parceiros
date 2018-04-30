<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Oi\PlanosCategoria;
use App\Models\Oi\Planos;
use Validator;

class AdminPlanosController extends AdminController
{
    public function index(Request $request){
        $planos = DB::table('planos')
            ->select(
                'planos.id',
                'planos.planos_category_id',
                'planos.name',
                'planos.description',
                'planos.status',
                'planos.created_at as date',
                'planos_categorias.name as name_category'
            )
            ->join('planos_categorias', 'planos_categorias.id', '=', 'planos.planos_category_id')
            ->orderby('planos_categorias.name')
            ->orderby('planos.created_at')
            ->get();
            
            return view('admin.administracao.planos.index', compact('planos'));
    }

    public function create(Request $request, Planos $planos){
        if($request->method() == 'POST'){
            $messagesRule = [
                'name.required' => 'Título é obrigatório.',
                'description.required' => 'Informações é obrigatório.',
                'category_id.required' => 'Categoria é obrigatório.'
            ];
            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required'
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

            $planoOrder = Planos::where('planos_category_id', $request->category_id)
            ->count();
            
            $planos->name = $request->name;
            $planos->description = $request->description;
            $planos->planos_category_id = $request->category_id;
            $planos->status = ($request->status == 'on')? 'A' : 'I';
            $planos->order = $planoOrder + 1;

            if($planos->save()){
                $dados['success'] = true;
                $dados['message'] = 'Plano cadastrada com sucesso.';
                return response()->json($dados);
            }
            
            $dados['success'] = false;
            $dados['message'] = 'Erro ao cadastrar plano.';
            return response()->json($dados);
        }
        $categories = PlanosCategoria::get();
        $planos = null;
        return view('admin.administracao.planos.novo', compact('categories', 'planos'));
    }

    public function edit($id){
        $planos = Planos::find($id);
        $categories = PlanosCategoria::get();
        return view('admin.administracao.planos.novo', compact('categories', 'planos'));
    }

    public function update(Request $request){
        if($request->id){
            $messagesRule = [
                'name.required' => 'Título é obrigatório.',
                'description.required' => 'Informações é obrigatório.',
                'category_id.required' => 'Categoria é obrigatório.'
            ];
            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required'
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
            
            $planos = Planos::find($request->id);
            $planos->name = $request->name;
            $planos->description = $request->description;
            $planos->planos_category_id = $request->category_id;
            $planos->status = ($request->status == 'on')? 'A' : 'I';
            if($planos->save()){
                $dados['success'] = true;
                $dados['message'] = 'Plano atualizada com sucesso.';
                return response()->json($dados);
            }
            
            $dados['success'] = false;
            $dados['message'] = 'Erro ao atualizar Plano.';
            return response()->json($dados);
        }
    }


}
