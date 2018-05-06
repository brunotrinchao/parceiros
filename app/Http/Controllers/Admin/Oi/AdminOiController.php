<?php

namespace App\Http\Controllers\Admin\Oi;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Oi\Atendimento;
use App\Models\Client;
use App\Models\Contact;
use App\Helpers\Helper;

class AdminOiController extends AdminController
{
    public function index(Request $request, $type){
        $array['type'] = ($request->type == 'solicitar-atendimento')? 'A':'F';
        $array['type_name'] = ($request->type == 'solicitar-atendimento')? 'Solicitar atendimento':'Fechar contrato';
        $array['type_slug'] = $type;
        
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
            DB::raw("GROUP_CONCAT(contacts.phone SEPARATOR ', ') AS phone"))
            ->leftJoin('contacts', 'clients.id', '=', 'contacts.client_id')
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
        //$indicacao = Financiamento::where('type', $array['type'])->get();
        return view('admin.oi.indicacao.index', compact('clients', 'array'));
    }

    public function create(Request $request, Response $response, Atendimento $atendimento, Client $client){
       
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
        
        $atendimento->client_id = $request->client_id;
        $atendimento->partner_id = auth()->user()->partners_id;
        $atendimento->note = $request->note;
        $atendimento->date = date('Y-m-d');
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

    public function update(Request $request, Response $response, Atendimento $atendimento){
       
        $messagesRule = [
            'note.required' => 'Informações é obrigatório',
            'status.required' => 'Status é obrigatório',
        ];
        $validatedData = Validator::make($request->all(), [
            'note' => 'required',
            'status' => 'required',
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

        $atendimento = Atendimento::find($request->oi_id);
        $atendimento->id = $request->oi_id;
        $atendimento->note = $request->note;
        $atendimento->status = $request->status;
 
        if($atendimento->save()){
            $retorno['message'] = 'Negócio atualizado com sucesso.';
            $retorno['success'] = true;
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao atualizar negócio.';
        $retorno['success'] = false;
        return response()->json($retorno);
    }

    public function single(Request $request, $type, $id){
        $array['type'] = ($request->type == 'solicitar-atendimento')? 'A':'F';
        $array['type_name'] = ($request->type == 'solicitar-atendimento')? 'Solicitar atendimento':'Fechar contrato';
        $array['type_slug'] = $type;
        // dd($this->getFinanciamento($id));
        $cliente =  $this->getCliente($id)[0];
        $solicitacoes =  $this->getSolicitacao($id);
         
        return view('admin.oi.indicacao.single', compact('cliente', 'solicitacoes', 'array'));
    }

    private function getCliente($id){
        $sql = 'SELECT clients.id,clients.partners_id, 
                        clients.user_id, 
                        clients.name, 
                        clients.email, 
                        clients.birth,
                        DATE_FORMAT(clients.birth,"%d/%m/%Y" ) as birth_formatada,
                        clients.sex,
                        CASE clients.sex 
                            WHEN "M" THEN "Masculino" 
                            ELSE "Feminino" END as sex_formatado,
                        clients.type,
                        clients.cpf_cnpj,
                        clients.contact,
                        clients.n_officials,
                        clients.date,
            DATE_FORMAT(clients.date,"%d/%m/%Y" ) as date_formatada,
            GROUP_CONCAT(contacts.phone) AS phone
            FROM clients 
            INNER JOIN contacts ON clients.id = contacts.client_id 
            WHERE 1 = 1 AND clients.id = ' . $id;
        return json_decode(json_encode(DB::select(DB::raw($sql))), true);
    }

    private function getSolicitacao($client_id){
        $sql = 'SELECT id,
                        note,
                        status,
                        CASE status 
                            WHEN "A" THEN "Aguardando contato" 
                            WHEN "C" THEN "Contactado" 
                            WHEN "I" THEN "Inconsistente" 
                            WHEN "V" THEN "Visitado" 
                            WHEN "E" THEN "Em negociação" 
                            END as status_formatado,
                        created_at,
                        DATE_FORMAT(created_at,"%d/%m/%Y" ) as date_formatada
                        FROM atendimentos 
                        WHERE 1 = 1 
                        AND atendimentos.client_id = ' . $client_id . '
                        ORDER BY created_at';
        return json_decode(json_encode(DB::select(DB::raw($sql))), true);
    }

    private static function numberUnformat($number)
    {
        $ret = null;
        if (!empty($number)) {
            $ret = str_replace(',', '.', str_replace('.', '', $number));
            $ret = str_replace('R$ ', '', $ret);
            $ret = str_replace('% ', '', $ret);
        }
        return $ret;
    }
}
