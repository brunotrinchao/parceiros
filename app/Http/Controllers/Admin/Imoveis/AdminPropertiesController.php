<?php

namespace App\Http\Controllers\Admin\Imoveis;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Imovel\Properties;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\Models\Imovel\Properties_Buy_Status;

class AdminPropertiesController extends Controller
{
    public function getPropertiesClient(Request $request, Response $response){

        $properties = DB::table('properties')
        ->select(
            'properties.id',
            'properties.amount',
            'properties.type_propertie',
            'properties.neighborhood',
            'properties.type',
            'properties.trade',
            'properties.note',
            'properties__buy__statuses.status  AS status',
            'properties.created_at as date')
        ->selectRaw("(CASE properties__buy__statuses.status 
		WHEN 'A' THEN  'Aguardando contato'
        WHEN 'B' THEN  'Telefone errado'
        WHEN 'C' THEN  'Desistiu contato'
        WHEN 'D' THEN  'Négocio fechado'
		WHEN 'E' THEN  'Em andamento'    END) AS status_formatado")
        ->join('clients', 'clients.id', '=', 'properties.client_id')
        ->join('properties__buy__statuses', 'properties__buy__statuses.properties_id', '=', 'properties.id')
        ->where('properties.client_id', '=', $request->id);
        if($request->type){
            $properties->where('properties.type', '=', $request->type);
        }
        if($request->trade){
            $properties->where('properties.trade', '=', $request->trade);
        }
        if(auth()->user()->level == 'U'){
            $properties->join('users', 'users.id', '=', 'clients.user_id');
            $properties->where('users.id', '=', auth()->user()->id);
        }

        if($properties->get()->count() > 0){
            $retorno['message'] = $properties->get()->count() . ' registros encontrados';
            $retorno['data'] = $properties->get(); 
            $retorno['success'] = true; 
            return response()->json($retorno);
        }
        $retorno['message'] = 'Nenhum registro encontrado.';
        $retorno['success'] = false; 
        return response()->json($retorno);
    }

    public function create(Request $request, Response $response, Properties $propertie){
        $propertie->client_id = $request->id;
        $propertie->amount = number_format(Helper::numberUnformat($request->amount), 2, '.', '');     
        $propertie->note = $request->note;
        $propertie->type_propertie = $request->type_propertie;
        $propertie->neighborhood = $request->neighborhood;
        $propertie->type = $request->type;
        $propertie->trade = $request->trade;
        $propertieInsert = $propertie->insert($propertie);
        if($propertieInsert){
            $retorno['success'] = true;
            $retorno['message'] = 'Negócio criado com sucesso.';
            return response()->json($retorno);
        }
        $retorno['success'] = false;
        $retorno['message'] = 'Erro ao criar negócio.';
        return response()->json($retorno);
    }

    public function update(Request $request, Properties_Buy_Status $status){

        // Valida campos
        $messagesRule = [
            'amount.required' => 'Valor do imóvel é obrigatório',
            'type_propertie.required' => 'Tipo do imóvel é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'amount' => 'required',
            'type_propertie' => 'required'
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

        $propertie = Properties::find($request->get('id'));
        $propertie->amount = number_format(Helper::numberUnformat($request->amount), 2, '.', ''); 
        $propertie->type_propertie = $request->get('type_propertie');
        $propertie->neighborhood = $request->get('neighborhood');
        $propertie->note = $request->get('note');
        
        
        if($propertie->save()){
            $retorno['message'] = 'Negócio atualizado com sucesso.';
            $retorno['success'] = true;
            if($request->get('status') != $propertie->status){
                $status = $propertie->properties_status;
                $status->status = $request->get('status');
            }
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao atualizar negócio.';
        $retorno['success'] = true;
        return response()->json($retorno);
        // dd($propertie);
    }
}
