<?php

namespace App\Http\Controllers\Admin\Financiamento;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Financiamento\Financiamento;
use App\Models\Client;
use App\Models\Contact;
use App\Helpers\Helper;

class AdminFinanciamentoController extends AdminController
{
    public function index(Request $request, $type){
        $array['type'] = ($request->type == 'tradicional')? 'T':'R';
        $array['type_name'] = ($request->type == 'tradicional')? 'Tradicional':'Refinanciamento';
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
        return view('admin.financiamento.indicacao.index', compact('clients', 'array'));
    }

    public function create(Request $request, Response $response, Financiamento $financiamento, Client $client, Contact $contact){
        
        $messagesRule = [
            'renda_comprovada.required' => 'Renda comprovada é obrigatório',
            'valor_bem.required' => 'Valor do bem é obrigatório',
            'valor_financiamento.required' => 'Valor do financiamento é obrigatório',
            'type.required' => 'Tipo é obrigatório',
        ];
        $validatedData = Validator::make($request->all(), [
            'renda_comprovada' => 'required',
            'valor_bem' => 'required',
            'valor_financiamento' => 'required',
            'type' => 'required'
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

        $financiamento->client_id = $request->client_id;
        $financiamento->partner_id = auth()->user()->partners_id;
        $financiamento->renda_comprovada = number_format($this->numberUnformat($request->renda_comprovada), 2, '.', '');
        $financiamento->valor_bem = number_format($this->numberUnformat($request->valor_bem), 2, '.', '');
        $financiamento->valor_financiamento = number_format($this->numberUnformat($request->valor_financiamento), 2, '.', '');
        $financiamento->type = $request->type;
        $financiamento->date = date('Y-m-d');

        $last_id = $financiamento->insert($financiamento);
        if($last_id['success']){
            $retorno['success'] = true;
            $retorno['message'] = 'Financiamento criado com sucesso.';
            $retorno['last_id'] = $last_id['last_insert_id'];
            return response()->json($retorno);
        }
        $retorno['success'] = false;
        $retorno['message'] = 'Erro ao criar financiamento.';
        return response()->json($retorno);
    }

    public function update(Request $request, Response $response, Financiamento $financiamento){
       
        $messagesRule = [
            'renda_comprovada.required' => 'Renda comprovada é obrigatório',
            'valor_bem.required' => 'Valor do bem é obrigatório',
            'valor_financiamento.required' => 'Valor do financiamento é obrigatório',
            'status.required' => 'Status é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'renda_comprovada' => 'required',
            'valor_bem' => 'required',
            'valor_financiamento' => 'required',
            'status' => 'required'
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

        $financiamento = Financiamento::find($request->financiamento_id);
        $financiamento->id = $request->financiamento_id;
        $financiamento->renda_comprovada = Helper::unFormatMoney($request->renda_comprovada);
        $financiamento->valor_bem = Helper::unFormatMoney($request->valor_bem);
        $financiamento->valor_financiamento = Helper::unFormatMoney($request->valor_financiamento);
        $financiamento->status = $request->status;

        if($financiamento->save()){
            $retorno['message'] = 'Negócio atualizado com sucesso.';
            $retorno['success'] = true;
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao atualizar negócio.';
        $retorno['success'] = false;
        return response()->json($retorno);
    }

    public function single(Request $request, $type, $id){
        $array['type'] = ($request->type == 'tradicional')? 'T':'R';
        $array['type_name'] = ($request->type == 'tradicional')? 'Tradicional':'Refinanciamento';
        $array['type_slug'] = $type;
        // dd($this->getFinanciamento($id));
        $cliente =  $this->getCliente($id)[0];
        $financiamentos =  $this->getFinanciamento($id, $array['type']);
         
        return view('admin.financiamento.indicacao.single', compact('cliente', 'financiamentos', 'array'));
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

    private function getFinanciamento($client_id, $type){
        $sql = 'SELECT id,
                        type,
                        valor_bem,
                        renda_comprovada,
                        valor_financiamento,
                        date,
                        DATE_FORMAT(date,"%d/%m/%Y" ) as date_formatada,
                        status,
                        CASE status 
                            WHEN "A" THEN "Aguardando contato" 
                            WHEN "C" THEN "Contactado" 
                            WHEN "I" THEN "Inconsistente" 
                            WHEN "V" THEN "Visitado" 
                            WHEN "E" THEN "Em negociação" 
                            END as status_formatado
                        FROM financiamentos 
                        WHERE 1 = 1 
                        AND financiamentos.client_id = ' . $client_id . ' 
                        AND financiamentos.type = "' .$type. '"
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
