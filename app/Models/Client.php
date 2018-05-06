<?php

namespace App\Models;
use Illuminate\Http\Response;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Contact;
use App\Models\Imovel\Properties;
use App\User;
use App\Helpers\Helper;

class Client extends Model
{
    public function insert($data){
        if(strlen($this->cpf_cnpj) == 14 && $this->type == 'F'){
            if(!Helper::validaCPF($this->cpf_cnpj)){
                $retorno['message'] = 'CPF inválido.';
                $retorno['success'] = false; 
                return response()->json($retorno);
            }
        }else if(strlen($this->cpf) == 18 && $this->type == 'J'){
            if(!Helper::validaCNPJ($this->cpf_cnpj)){
                $retorno['message'] = 'CNPJ inválido.';
                $retorno['success'] = false; 
                return response()->json($retorno);
            }
        }
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

    public function search(Array $data, $totalPage){

        
        $clients =  $this->where(function($query) use ($data){
            
            if($data['name']){
                $query->where('name', 'LIKE', '%'.$data['name'].'%');
            }
            if($data['date']){
                $arrDate = explode('-',$data['date']);
                if(count($arrDate) > 1){
                    $query->whereBetween('date', [$this->formatDate($arrDate[0]), $this->formatDate($arrDate[1])]);
                }else{
                    $query->where('date', $this->formatDate($data['date']));
                }
            }
            
        })
        ->paginate($totalPage);

            return $clients;
        
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

    public function users(){
        return $this->belongsTo(User::class);
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

    private function formatDate($data){
        return date('Y-m-d', strtotime(str_replace('/','-',$data)));
    }


    private function removeMask($text)
    {
        return str_replace(array(".", "-", "/", " ", "(", ")"), "", $text);
    }
}
