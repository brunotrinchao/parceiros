<?php

namespace App\Http\Controllers\Admin\Imoveis\Indicacao;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Imovel\Properties;
use App\Models\Contact;
use App\User;
use Validator;

class AdminComprarController extends AdminController
{
    public function index(Request $request){
        
        $array['type'] = $request->type;
        $array['trade'] = $request->type;
        $array['type_name'] = ($request->type == 'I')? 'Interessado':'Proprietário';
        $array['trade_name'] = ($request->type == 'A')? 'Alugar':'Comprar';
        $array['type_slug'] = ($request->type == 'I')? 'interessado':'proprietario';
        $array['trade_slug'] = ($request->type == 'A')? 'alugar':'comprar';

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
        
        return view('admin.imoveis.indicacao.index', compact('clients', 'array'));
    }

    public function create(Request $request, Response $response, Properties $propertie, Client $client){
       
        $messagesRule = [
            'amount.required' => 'Valor do imóvel é obrigatório',
            'type_propertie.required' => 'Tipo do imóvel é obrigatório',
            'neighborhood.required' => 'Bairro é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'amount' => 'required',
            'type_propertie' => 'required',
            'neighborhood' => 'required'
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
        
        $propertie->client_id = $request->client_id;
        $propertie->partner_id = auth()->user()->partners_id;
        $propertie->amount = number_format($this->numberUnformat($request->amount), 2, '.', '');     
        $propertie->note = $request->note;
        $propertie->type_propertie = $request->type_propertie;
        $propertie->neighborhood = $request->neighborhood;
        $propertie->type = $request->type;
        $propertie->trade = $request->trade;
        $propertieInsert = $propertie->insert($propertie);
        if($propertieInsert['success']){
            $retorno['success'] = true;
            $retorno['message'] = 'Negócio criado com sucesso.';
            $retorno['last_id'] = $propertieInsert['last_insert_id'];
            return response()->json($retorno);
        }
        $retorno['success'] = false;
        $retorno['message'] = 'Erro ao criar atendimento.';

        return response()->json($retorno);
        
    }

    public function update(Request $request, Response $response, Properties $properties){
       
        $messagesRule = [
            'note.required' => 'Informações é obrigatório',
            'status.required' => 'Status é obrigatório',
            'status.required' => 'Status é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'note' => 'required',
            'status' => 'required',
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

        $properties = Properties::find($request->imoveis_id);
        $properties->id = $request->imoveis_id;
        $properties->amount = number_format($this->numberUnformat($request->amount), 2, '.', '');     
        $properties->note = $request->note;
        $properties->type_propertie = $request->type_propertie;
        $properties->neighborhood = $request->neighborhood;
        $properties->note = $request->note;
        $properties->status = $request->status;

        if($properties->save()){
            $retorno['message'] = 'Negócio atualizado com sucesso.';
            $retorno['success'] = true;
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao atualizar negócio.';
        $retorno['success'] = false;
        return response()->json($retorno);
    }

    public function single(Request $request, $type, $trade, $id){
        $array['type'] = ($type == 'interessado')? 'I':'P';
        $array['type_name'] = ($type == 'interessado')? 'Interessado':'Próprietário';
        $array['type_slug'] = $type;
        $array['trade'] = ($trade == 'comprar')? 'C': ($trade == 'vender')? 'V':'A';
        $array['trade_name'] = ($trade == 'comprar')? 'Comprar': ($trade == 'vender')? 'Vender':'Alugar';
        $array['trade_slug'] = $trade;
        // dd($this->getFinanciamento($id));
        $cliente =  $this->getCliente($id)[0];
        $imoveis =  $this->getimoveis($id);
         
        return view('admin.imoveis.indicacao.single', compact('cliente', 'imoveis', 'array'));
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

    private function getImoveis($client_id){
        $sql = 'SELECT id,
                        amount,
                        type_propertie,
                        neighborhood,
                        type,
                        note,
                        trade,
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
                        FROM properties 
                        WHERE 1 = 1 
                        AND properties.client_id = ' . $client_id . '
                        ORDER BY created_at';
        return json_decode(json_encode(DB::select(DB::raw($sql))), true);
    }
    // public function index(Request $request){
    //     $clients = DB::table('clients')
    //     ->select('clients.id',
    //     'clients.user_id',
    //     'clients.birth',
    //     'clients.contact',
    //     'clients.cpf_cnpj',
    //     'clients.date',
    //     'clients.email',
    //     'clients.name',
    //     'clients.n_officials',
    //     'clients.sex',
    //     'clients.type')
    //     ->selectRaw('GROUP_CONCAT(contacts.phone) AS phone')
    //     ->join('contacts', 'clients.id', '=', 'contacts.client_id')
    //     ->when(auth()->user()->level == "S" || auth()->user()->level == "A" || auth()->user()->level == "G", function ($query) {
    //         $query->where('partners_id', '=', intval(auth()->user()->partners_id));
    //         return $query;
    //     })
    //     ->when(auth()->user()->level == "U", function ($query) {
    //         $query->where('users_id', '=', intval(auth()->user()->id))
    //             ->where('partners_id', '=', intval(auth()->user()->partners_id));
    //         return $query;
    //     })
    //     ->groupBy('clients.id')
    //     ->get();
        
    //     return view('admin.imoveis.index', compact('clients'));
    // }

    public function insertBuy(Request $request, Response $response,Client $client, Properties $propertie, Contact $contact){
        $messagesRule = [
            'name.required' => 'Nome do cliente é obrigatório',
            'email.required' => 'E-mail é obrigatório',
            'cpf_cnpj.required' => 'CPF é obrigatório',
            'birth.required' => 'Data de nascimento é obrigatório',
            'type.required' => 'Tipo de negócio é obrigatório',
            'amount.required' => 'Valor do imóvel é obrigatório',
            'type_propertie.required' => 'Tipo do imóvel é obrigatório',
            'neighborhood.required' => 'Bairro é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients,email',
            'cpf_cnpj' => 'required|unique:clients,cpf_cnpj',
            'birth' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'type_propertie' => 'required',
            'neighborhood' => 'required'
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
       
        // Cliente
        $client->user_id = auth()->user()->id;
        $client->partners_id = auth()->user()->partners->id;
        $client->name = $request->name;
        $client->email = $request->email;
        $client->birth = date('Y-m-d', strtotime(str_replace('/','-',$request->birth)));
        $client->sex = $request->sex;
        $client->cpf_cnpj = $request->cpf_cnpj;
        $client->contact = $request->contact;
        $client->date = date('Y-m-d');
        $client->type = 'F';
        $clientInsert = $client->insert($client);
        if($clientInsert['success']){
            // Imovel
            $propertie->client_id = $clientInsert['last_insert_id'];
            $propertie->amount = number_format($this->numberUnformat($request->amount), 2, '.', '');     
            $propertie->note = $request->note;
            $propertie->type_propertie = $request->type_propertie;
            $propertie->neighborhood = $request->neighborhood;
            $propertie->type = $request->type;
            $propertie->trade = $request->trade;
            $propertieInsert = $propertie->insert($propertie);
            // Contato
            $contactInsert = $contact->insert($clientInsert['last_insert_id'], $request->phone);
        }
        return json_encode($clientInsert);
        
    }


    public function search(Request $request, Client $client){
        $dataFrom = $request->all();
        $clients = $client->search($dataFrom, $this->totalPage);
        $properties = $client->formatStatusPropertie(null);

        return view('admin.imoveis.comprar', compact('clients', 'properties'));
    }

    public function getClient($id){
        $clients = Client::where('clients.id', $id)
                        ->first();
        $addresses = $clients->addresses;
        $contacts = $clients->contacts;
        $properties = $clients->properties;
        foreach ($properties as $key => $propertie) {
            $properties_status = $propertie->properties_status;
        }
        if(!empty($clients)){
            $data['clients'] = $clients; 
            $data['success'] = true; 
            $data['message'] = 'Cliente carregado com sucesso.'; 
        }else{
            $data['success'] = false; 
            $data['message'] = 'Erro ao carregar cliente.'; 
        }
        return $data;
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
