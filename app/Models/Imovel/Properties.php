<?php

namespace App\Models\Imovel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Imovel\Properties_Buy_Status;

class Properties extends Model
{

    public function properties_status(){
        return $this->hasOne(Properties_Buy_Status::class);
    }



    public function insert($data){
        
        if($this->save()) {
            return [
                'success' => true, 
                'menssage' => 'Cadastro realizado com sucesso',
                'last_insert_id' => $data->id];
        }
        return [
            'success' => false, 
            'menssage' => 'Erro ao cadastrar'];
    }
}
