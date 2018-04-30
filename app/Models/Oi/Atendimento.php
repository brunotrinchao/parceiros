<?php

namespace App\Models\Oi;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    public function insert($data){
            if($this->save()) {
                return [
                    'success' => true, 
                    'message' => 'Atendimento cadastrado com sucesso',
                    'last_insert_id' => $this->id];
            }
            return [
                'success' => false, 
                'message' => 'Erro ao cadastrar atendimento'];
        }

}
