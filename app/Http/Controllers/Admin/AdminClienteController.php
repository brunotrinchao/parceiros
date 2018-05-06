<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Helpers\Helper;

class AdminClienteController extends AdminController
{
    private $id = null;
    private $type = null;
    private $cpf = null;
    private $user_id = null;
    private $partners_id = null;
    private $cliente;

    public function get(Request $request){

        if(strlen($request->cpf) == 14 && $request->client_type == 'F'){
            if(!Helper::validaCPF($request->cpf)){
                $retorno['message'] = 'CPF inválido.';
                $retorno['success'] = false; 
                return response()->json($retorno);
            }
        }else if(strlen($request->cpf) == 18 && $request->client_type == 'J'){
            if(!Helper::validaCNPJ($request->cpf)){
                $retorno['message'] = 'CNPJ inválido.';
                $retorno['success'] = false; 
                return response()->json($retorno);
            }
        }
        
        $this->id = $request->id;
        $this->type = $request->client_type;
        $this->cpf = $request->cpf;
        $this->partners_id = $request->partners_id;
        $this->getCliente();
        
        if($this->cliente[0]['id']){
            $retorno['message'] = count($this->cliente) . ' registros encontrados';
            $retorno['data'] = $this->cliente; 
            $retorno['success'] = true; 
            return response()->json($retorno);
        }
        $retorno['message'] = 'Nenhum registro encontrado.';
        $retorno['data'] = []; 
        $retorno['success'] = true; 
        return response()->json($retorno);
    }

    public function create(Request $request, Response $response, Client $client, Contact $contact){
        
        $cpf_cnpj = null;
        if(strlen($request->cpf_cnpj) == 14 && $request->client_type == 'F'){
            $cpf_cnpj = "CPF";
            if(!Helper::validaCPF($request->cpf_cnpj)){
                $retorno['message'] = 'CPF inválido.';
                $retorno['success'] = false; 
                return response()->json($retorno);
            }
        }else if(strlen($request->cpf_cnpj) == 18 && $request->client_type == 'J'){
            $cpf_cnpj = "CNPJ";
            if(!Helper::validaCNPJ($request->cpf_cnpj)){
                $retorno['message'] = 'CNPJ inválido.';
                $retorno['success'] = false; 
                return response()->json($retorno);
            }
        }
        // Valida campos
        $messagesRule = [
            'birth.required' => 'Data de nascimento é obrigatório',
            'cpf_cnpj.required' => $cpf_cnpj .' é obrigatório',
            'cpf_cnpj.unique' => 'Esse '.$cpf_cnpj.' já esta em uso.',
            'email.required' => 'E-mail é obrigatório',
            'email.unique' => 'Esse e-mail já está em uso',
            'nome.required' => 'Nome é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients,email',
            'cpf_cnpj' => 'required|unique:clients,cpf_cnpj',
            'birth' => 'required'
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
        // Salva os dados do cliente
        $client->user_id = auth()->user()->id;
        $client->partners_id = auth()->user()->partners->id;
        $client->name = $request->name;
        $client->email = $request->email;
        $client->birth = date('Y-m-d', strtotime($request->birth));
        $client->sex = $request->sex;
        $client->cpf_cnpj = $request->cpf_cnpj;
        $client->date = date('Y-m-d');
        $client->type = $request->client_type;
        $clientInsert = $client->insert($client);

        if($clientInsert['success']){
            $contactInsert = $contact->insert($clientInsert['last_insert_id'], $request->phone);
            $retorno['success'] = true;
            $retorno['message'] = 'Cliente criado com sucesso.';
            $retorno['last_id'] = $clientInsert['last_insert_id'];
            return response()->json($retorno);
        }
        $retorno['success'] = false;
        $retorno['message'] = 'Erro ao cadastrar cliente.';
        return response()->json($retorno);
    }

    private function getCliente(){
        $sql = 'SELECT clients.id,clients.partners_id, clients.user_id, clients.name, clients.email, clients.birth,DATE_FORMAT(clients.birth,"%d/%m/%Y" ) as birth_formatada,clients.sex,clients.type,clients.cpf_cnpj,clients.contact,clients.n_officials,clients.date,DATE_FORMAT(clients.date,"%d/%m/%Y" ) as date_formatada,GROUP_CONCAT(contacts.phone) AS phone FROM clients INNER JOIN contacts ON clients.id = contacts.client_id WHERE 1 = 1';
        if($this->id != null){
            $sql .= ' AND id = "' . $this->id .'"';
        }
        if($this->type != null){
            $sql .= ' AND clients.type = "' . $this->type .'"';
        }
        if($this->user_id != null){
            $sql .= ' AND clients.user_id = "' . $this->user_id .'"';
        }
        if($this->cpf != null){
            $sql .= ' AND clients.cpf_cnpj = "' . $this->cpf .'"';
        }
        if(auth()->user()->level == "U"){
            $sql .= ' AND clients.users_id = ' . intval(auth()->user()->id);
        }
        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= ' AND clients.partners_id = ' . intval(auth()->user()->partners_id);
            $sql .= ' GROUP BY clients.id';
        }
   
        $retorno = json_decode(json_encode(DB::select(DB::raw($sql))), true);
        $this->cliente = $retorno;
    }

    // GETTER and SETTER
    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        $this->cpf = $cpf;
    }
}
