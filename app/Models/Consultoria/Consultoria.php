<?php

namespace App\Models\Consultoria;

use Illuminate\Database\Eloquent\Model;

class Consultoria extends Model
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
