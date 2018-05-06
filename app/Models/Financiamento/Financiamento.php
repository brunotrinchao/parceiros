<?php

namespace App\Models\Financiamento;

use Illuminate\Database\Eloquent\Model;

class Financiamento extends Model
{
    public function insert($data){
        if($this->save()) {
            return [
                'success' => true, 
                'message' => 'Financiamento cadastrado com sucesso',
                'last_insert_id' => $this->id];
        }
        return [
            'success' => false, 
            'message' => 'Erro ao cadastrar atendimento'];
    }

}
