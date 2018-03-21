<?php

namespace App\Models;
use Illuminate\Http\Response;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Contact;
use App\Models\Imovel\Properties;

class Client extends Model
{
    public function insert($data){
        if($this->validaCPF($this->cpf_cnpj)){
            if($this->save()) {
                return [
                    'success' => true, 
                    'message' => 'Cliente cadastrado com sucesso',
                    'last_insert_id' => $this->id];
            }
            return [
                'success' => false, 
                'message' => 'Erro ao cadastrar cliente'];
        }
        return [
            'success' => false, 
            'message' => 'CPF inválido.'];
    }

    public function search(Array $data, $totalPage){
        $clients = $this;
        if($data['name']){
            $this->where('name', $data['name']);
        }
        
        // $clients = $this->where(function($query) use ($data){
            
        //     if($data['name']){
        //         $query->where('name', $data['name']);
        //     }
        //     if($data['date']){
        //         $arrDate = explode('-',$data['date']);
        //         if(count($arrDate) > 1){
        //             $query->whereBetween('date', $arrDate);
        //         }else{
        //             $query->whereBetween('date', $data['date']);
        //         }
        //     }
        //     if($data['phone']){
        //         $query->where('contacts.phone', $data['phone']);
        //     }
        // });
        // $clients->join('contacts', 'contacts.client_id', '=', 'clients.id')
        $clients->toSql();
        dd($clients);
        // $users = DB::table('clients')
        //             ->select(	
        //                 'users.name AS user_name',
        //                 'users.level AS user_level',
        //                 'users.status AS user_status',
        //                 'users.image AS user_image',
        //                 'users.email AS user_email',
        //                 'clients.name AS client_name',
        //                 'clients.id AS client_id',
        //                 'clients.email AS client_email',
        //                 'clients.type AS client_type',
        //                 'clients.sex AS client_sex',
        //                 'clients.n_officials AS client_n_officials',
        //                 'clients.date AS client_date',
        //                 'clients.cpf_cnpj AS client_cpf_cnpj',
        //                 'clients.contact AS client_contact',
        //                 'clients.birth AS client_birth',
        //                 'addresses.street AS address_street',
        //                 'addresses.neighborhood AS address_neighborhood',
        //                 'addresses.zip_code AS address_zip_code',
        //                 'addresses.state AS address_state',
        //                 'addresses.number AS address_number',
        //                 'addresses.complement AS address_complement',
        //                 'addresses.city AS address_city',
        //                 'contacts.phone AS contact_phone' )
        //                 ->join('users', 'users.id', '=', 'clients.id')
        //                 ->join('addresses', 'addresses.id_client', '=', 'clients.id')
        //                 ->join('contacts', 'contacts.id_client', '=', 'clients.id');


        
        // if(auth()->user()->level != 'S' || auth()->user()->level != 'P' || auth()->user()->level != 'G'){
        //     $users->where('clients.id_user', auth()->user()->id);
        // }
        // $users->groupBy('contacts.id_client');

        // $clients = $users->get();
        
        // return $clients;
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function contacts(){
        return $this->hasMany(Contact::class);
    }

    public function properties(){
        return $this->hasMany(Properties::class);
    }

    private function validaCPF($cpf)
    {

        // Verifiva se o número digitado contém todos os digitos
        $cpf = preg_replace('/[^0-9]/i', '', $cpf);

        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    public function formatStatusPropertie($status){
        $arr = [
            'A' => 'Aguardando contato',
            'B' => 'Telefone errado',
            'C' => 'Desistiu',
            'D' => 'Négocio fechado',
            'E' => 'Em andamento',
        ];

        if(!$status || $status == null){
            return 'Sem status';
        }

        return $arr[$status];
    }
}
