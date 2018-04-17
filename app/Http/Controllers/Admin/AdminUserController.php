<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Partner;
use App\User;
use Validator;

class AdminUserController extends AdminController
{
    public function index(Request $request){
        $partners = Partner::orderby('name')->get();
        $users = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.image',
                'users.date',
                'partners.id as partner_id',
                'partners.name as partner_name',
                'partners.image as partner_image',
                'partners.status as partner_status'
            )
            ->selectRaw("(CASE users.status 
                WHEN 'A' THEN  'Ativo'
                WHEN 'I' THEN  'Inativo' END) AS status_format")
            ->selectRaw("(CASE users.level 
                WHEN 'S' THEN  'Super Admin'
                WHEN 'A' THEN  'Admin'
                WHEN 'G' THEN  'Gerente'
                WHEN 'U' THEN  'Usuário' END) AS level_format")
            ->when(auth()->user()->level == "A" || auth()->user()->level == "G", function ($query) {
                $query->where('level', '<>', 'S')->where('partners_id', '=', auth()->user()->partners_id);
                return $query;
            })
            ->join('partners', 'partners.id', '=', 'users.partners_id')
            ->orderby('name')
            // ->toSql();
            ->get();
            // dd($users);
            
            return view('admin.usuarios', compact('users', 'partners'));
    }

    public function create(Request $request, User $user){
        $messagesRule = [
            'name.required' => 'Nome do usuário é obrigatório.',
            'email.required' => 'E-mail do usuário é obrigatório.',
            'partners_id.required' => 'Parceiro é obrigatório.'
        ];
        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'partners_id' => 'required'
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

        $user->name = $request->name;
        $user->email = $request->email;
        $user->partners_id = $request->partners_id;
        $user->level = $request->level;
        $user->password = bcrypt($request->email);
        $user->date = date('Y-m-d');
        
        if($user->save()){
            $retorno['message'] = 'Parceiro cadastrado com sucesso.';
            $retorno['success'] = true; 
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao cadastrar parceiro.';
        $retorno['success'] = false; 
        return response()->json($retorno);
    }

    public function edit(Request $request){
        $messagesRule = [
            'name.required' => 'Nome do parceiro é obrigatório.',
            'level.required' => 'Nível de acesso é obrigatório.'
        ];
        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'level' => 'required'
        ], $messagesRule);
        if($request->id == auth()->user()->id){
            $retorno['message'] = 'Você não pode inativar seu usuário.';
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
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->partners_id = $request->partners_id;
        $user->level = $request->level;
        $user->status = $request->status;
        $user->password = bcrypt($request->email);
        $user->date = date('Y-m-d');

        if($user->save()){
            $retorno['message'] = 'Usuário atualizado com sucesso.';
            $retorno['success'] = true; 
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao editar usuário.';
        $retorno['success'] = false; 
        return response()->json($retorno);
    }

    public function getUser(Request $request){
        if($request->id){
            $user = User::find($request->id);
            if($user){
                $data['data'] = $user; 
                $data['success'] = true; 
                $data['message'] = 'Usuário carregado com sucesso.'; 
                return $data;
            }
            $data['success'] = false; 
            $data['message'] = 'Usuário não encontrado.'; 
            return $data;
        }
        $data['success'] = false; 
        $data['message'] = 'Erro ao recuperar usuário.'; 
        return $data;
    }

    private function petfilFormatado($perfil){
        switch ($perfil) {
            case 'S':
               return 'Super Admin';
                break;
            case 'A':
               return 'Admin';
                break;
            case 'G':
               return 'Gerente';
                break;
            default:
                return 'Usuário';
                break;
        }
    }
}
