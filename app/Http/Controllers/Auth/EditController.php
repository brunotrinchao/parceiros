<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Models\Client;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Helper;

class EditController extends Controller
{
    
    protected function edit(Request $request, Response $response, Contact $contact){

        // Valida CPF
        if(!Helper::validaCPF($request->get('cpf_cnpj'))){
            $retorno['message'] = 'CPF inválido. <br>';
            $retorno['success'] = false; 
            return response()->json($retorno);
        }
        // Recupera cliente
        $cliente = Client::findOrFail($request->get('id'));
        // Valida campos
        $messagesRule = [
            'birth.required' => 'Data de nascimento é obrigatório',
            'cpf_cnpj.required' => 'CPF é obrigatório',
            'cpf_cnpj.unique' => 'Esse CPF já esta em uso.',
            'email.required' => 'E-mail é obrigatório',
            'email.unique' => 'Esse e-mail já esta em uso',
            'nome.required' => 'Nome é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients,email, '.$cliente->id,
            'cpf_cnpj' => 'required|unique:clients,cpf_cnpj,'.$cliente->id,
            'birth' => 'required'
        ], $messagesRule);

        if($validatedData->fails()){
            $arrMsg;
            foreach(json_decode($validatedData->messages()) as $t){
                $arrMsg[] = '- '. $t[0];
            }
            $retorno['message'] = implode('<br>', $arrMsg);
            $retorno['success'] = false; 
            return response()->json($retorno);
        }
        // Salva os dados do cliente
        $cliente->name = $request->get('name');
        $cliente->email = $request->get('email');
        $cliente->sex = $request->get('sex');
        $cliente->cpf_cnpj = $request->get('cpf_cnpj');
        $cliente->birth = Helper::formatDate($request->get('birth'));
        $cliente->contact = $request->get('contact');

        if($cliente->save()){
            $retorno['message'] = 'Cliente atualizado com sucesso.';
            $retorno['success'] = true;
            
            $contact->where('client_id', $request->get('id'))->delete();
            $contact->insert($request->get('id'), $request->phone);
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao atualizar cliente.';
        $retorno['success'] = true;
        return response()->json($retorno);

    }

}
