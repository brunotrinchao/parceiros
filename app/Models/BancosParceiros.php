<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BancosParceiros extends Model
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
