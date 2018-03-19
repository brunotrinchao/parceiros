<?php

namespace App\Models;
use Illuminate\Http\Response;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function insert($data){
        
        if($this->save()) {
            return Response::json(array(
                'success' => true, 
                'menssage' => 'Cliente cadastrado com sucesso',
                'last_insert_id' => $data->id), 
                200);
        }
        return Response::json(array(
            'success' => false, 
            'menssage' => 'Erro ao cadastrar cliente'),
             200);
    }
}
