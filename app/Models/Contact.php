<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function insert($client_id, $data){
        $count = 0;
        $this->client_id = $client_id;
        foreach($data as $phone){
            $this->phone = $phone;
            if($this->save()){
                $count++;
            }
        }
        if($count > 0) {
            return [
                'success' => true, 
                'message' => $count . 'contatos cadastradoscom sucesso',
                'last_insert_id' => $this->id];
        }
        return [
            'success' => false, 
            'message' => 'Erro ao cadastrar'];
    }

    private function removeMask($text)
    {
        return str_replace(array(".", "-", "/", " ", "(", ")"), "", $text);
    }

    
}
