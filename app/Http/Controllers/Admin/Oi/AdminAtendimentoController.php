<?php

namespace App\Http\Controllers\Admin\Oi;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Validator;
use App\Models\Oi\Atendimento;
use Illuminate\Support\Facades\DB;

class AdminAtendimentoController extends AdminController
{
    public function index(Request $request){
        $clients = DB::table('clients')
        ->select('clients.id',
        'clients.user_id',
        'clients.birth',
        DB::raw("DATE_FORMAT(clients.birth,'%d/%m/%Y' ) as birth_formatada"),
        'clients.contact',
        'clients.cpf_cnpj',
        'clients.date',
        'clients.email',
        'clients.name',
        'clients.n_officials',
        'clients.sex',
        'clients.type',
        DB::raw("DATE_FORMAT(clients.date,'%d/%m/%Y' ) as data_formatada"),
        DB::raw("GROUP_CONCAT(contacts.phone) AS phone"))
        ->join('contacts', 'clients.id', '=', 'contacts.client_id')
        ->when(auth()->user()->level == "S" || auth()->user()->level == "A" || auth()->user()->level == "G", function ($query) {
            $query->where('partners_id', '=', intval(auth()->user()->partners_id));
            return $query;
        })
        ->when(auth()->user()->level == "U", function ($query) {
            $query->where('users_id', '=', intval(auth()->user()->id))
                ->where('partners_id', '=', intval(auth()->user()->partners_id));
            return $query;
        })
        ->groupBy('clients.id')
        ->get();
        return view('admin.oi.atendimento.index', compact('clients'));
    }

    public function create(Request $request, Response $response, Atendimento $atendimento){
        $messagesRule = [
            'note.required' => 'Informações é obrigatório',
        ];
        $validatedData = Validator::make($request->all(), [
            'note' => 'required'
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

        if($request->client_id){
            $atendimento->client_id = $request->client_id;
            $atendimento->note = $request->note;
            $last_id = $atendimento->insert($atendimento);
            if($last_id['success']){
                $retorno['success'] = true;
                $retorno['message'] = 'Atendimento criado com sucesso.';
                $retorno['last_id'] = $last_id['last_insert_id'];
                return response()->json($retorno);
            }
            $retorno['success'] = false;
            $retorno['message'] = 'Erro ao criar atendimento.';
            return response()->json($retorno);
        }
        $retorno['success'] = false;
        $retorno['message'] = 'Erro ao criar atendimento.';
        return response()->json($retorno);
    }
}
