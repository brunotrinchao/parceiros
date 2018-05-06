<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\Models\Partner;
use Validator;

class AdminPartnerController extends AdminController
{
    public function index(Request $request){
        $partners = DB::table('partners')
            ->orderby('name')
            ->get();
            foreach($partners as $key => $partner){
                $partners[$key]->status_format = ($partner->status == 'A')? 'Ativo' : 'Inativo';
            }
            return view('admin.administracao.parceiros.index', compact('partners'));
    }

    public function getPartner(Request $request){
        if($request->id){
            $partner = Partner::find($request->id);
            if($partner){
                $data['data'] = $partner; 
                $data['success'] = true; 
                $data['message'] = 'Parceiro carregado com sucesso.'; 
                return $data;
            }
            $data['success'] = false; 
            $data['message'] = 'Parceiro não encontrado.'; 
            return response()->json($data);
        }
        $data['success'] = false; 
        $data['message'] = 'Erro ao recuperar parceiro.'; 
        return response()->json($data);
    }

    public function edit(Request $request){
        $messagesRule = [
            'name.required' => 'Nome do parceiro é obrigatório.'
        ];
        $validatedData = Validator::make($request->all(), [
            'name' => 'required'
        ], $messagesRule);
        if($request->id == auth()->user()->id){
            $retorno['message'] = 'Você não pode inativar sua conta.';
            $retorno['success'] = false; 
            return response()->json($retorno);
        }

        if($validatedData->fails()){
            $arrMsg;
            foreach(json_decode($validatedData->messages()) as $t){
                $arrMsg[] = '- '. $t[0];
            }
            $retorno['message'] = implode('<br>', $arrMsg);
            $retorno['success'] = false; 
            return response()->json($retorno);
        }
        $partner = Partner::find($request->id);
        $partner->name = $request->name;
        $partner->status = $request->status;
        $partner->date = date('Y-m-d');
        if($partner->save()){
            $retorno['message'] = 'Parceiro atualizado com sucesso.';
            $retorno['success'] = true; 
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao editar parceiro.';
        $retorno['success'] = false; 
        return response()->json($retorno);
    }

    public function create(Request $request, Partner $partner){
        $messagesRule = [
            'name.required' => 'Nome do parceiro é obrigatório.'
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

        $partner->name = $request->name;
        $partner->date = date('Y-m-d');
        if($partner->save()){
            $retorno['message'] = 'Parceiro cadastrado com sucesso.';
            $retorno['success'] = true; 
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao cadastrar parceiro.';
        $retorno['success'] = false; 
        return response()->json($retorno);
    }

    public function upload(Request $request, Partner $partner){
        $messagesRule = [
            'name.required' => 'O campo nome é obrigatório.',
            'file.required' => 'O campo arquivo é obrigatório.'
        ];
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'file' => 'required|max:1024',
        'file.*' => 'mimes:jpeg,png,jpg',
        ], $messagesRule);

    
        if ($validator->passes()) {
            $fileExp = explode('.', $request->file->getClientOriginalName());
            $name = time().'-logo-'.Helper::createSlug($request->name);
            $nameFile = "{$name}.{$fileExp[1]}";
            
            $upload = $request->file->storeAs('public/parceiros/' . Helper::createSlug($request->name), $nameFile);
            
            if($upload){
                $partner->file = $nameFile;
                $partner->name = $request->name;
                $partner->date = date('Y-m-d', strtotime(str_replace('/','-',$request->date)));
                
                if($partner->save()){
                    $retorno['message'] = 'Parceiro cadastrado com sucesso.';
                    $retorno['success'] = true; 
                    return response()->json($retorno);
                }
            }
            $retorno['message'] = 'Erro ao fazer upload';
            $retorno['success'] = false; 
            return response()->json($retorno);

        }
        return response()->json(['error'=> $validator->errors()]);
    }

    public function partnerEdit(Request $request){
        return view('admin.parceiros.editar');
    }

}
