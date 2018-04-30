<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
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
        if(!Helper::validaCPF($request->cpf)){
            $retorno['message'] = 'CPF inválido.';
            $retorno['success'] = false; 
            return response()->json($retorno);
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

    private function getCliente(){
        $sql = 'SELECT clients.id,clients.partners_id, clients.user_id, clients.name, clients.email, clients.birth,DATE_FORMAT(clients.birth,"%d/%m/%Y" ) as birth_formatada,clients.sex,clients.type,clients.cpf_cnpj,clients.contact,clients.n_officials,clients.date,DATE_FORMAT(clients.date,"%d/%m/%Y" ) as date_formatada,GROUP_CONCAT(contacts.phone) AS phone FROM clients INNER JOIN contacts ON clients.id = contacts.client_id WHERE 1 = 1';
        if($this->id != null){
            $sql .= ' AND id = "' . $this->id .'"';
        }
        if($this->type != null){
            $this->type = ($this->type == 'on')? 'F':'J';
            $sql .= ' AND clients.type = "' . $this->type .'"';
        }
        if($this->user_id != null){
            $sql .= ' AND clients.user_id = "' . $this->user_id .'"';
        }
        if($this->partners_id != null){
            $sql .= ' AND clients.partners_id = "' . $this->partners_id .'"';
        }
        if($this->id != null || $this->type != null || $this->user_id != null || $this->partners_id != null){
            $sql .= ' AND clients.cpf_cnpj = "' . $this->cpf .'"';
        }else{
            $sql .= ' GROUP BY clients.id';
        }

        $this->cliente = json_decode(json_encode(DB::select(DB::raw($sql))), true);

        
    }


    // GETTER and SETTER
    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        $this->cpf = $cpf;
    }
}
