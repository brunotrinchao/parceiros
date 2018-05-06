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
    public function getPropertiesClient(Request $request, Response $response, $id){
        
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
        ->selectRaw("DATE_FORMAT(properties.created_at,'%d/%m/%Y' ) as date_formatado")
        ->selectRaw("(CASE properties__buy__statuses.status 
		WHEN 'A' THEN  'Aguardando contato'
        WHEN 'B' THEN  'Telefone errado'
        WHEN 'C' THEN  'Desistiu contato'
        WHEN 'D' THEN  'Négocio fechado'
        WHEN 'E' THEN  'Em andamento'    END) AS status_formatado")
        ->selectRaw("(CASE properties.trade 
		WHEN 'A' THEN  'Alugar'
        WHEN 'C' THEN  'Comprar'
        WHEN 'V' THEN  'Vender' END) AS trade_formatado")
        ->selectRaw("(CASE properties.type 
		WHEN 'I' THEN  'Interessado'
        WHEN 'P' THEN  'Proprietário'    END) AS type_formatado")
        ->join('clients', 'clients.id', '=', 'properties.client_id')
        ->join('properties__buy__statuses', 'properties__buy__statuses.properties_id', '=', 'properties.id')
        ->where('properties.client_id', '=', $id)
        ->when(auth()->user()->level == 'U', function ($query) {
            $query->join('users', 'users.id', '=', 'clients.user_id')
                    ->where('users.id', '=', auth()->user()->id);
            return $query;
        })
        ->orderBy('properties.created_at', 'desc');
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
        
        $messagesRule = [
            'type.required' => 'Tipo de negócio é obrigatório',
            'amount.required' => 'Valor do imóvel é obrigatório',
            'type_propertie.required' => 'Tipo do imóvel é obrigatório',
            'neighborhood.required' => 'Bairro é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'type' => 'required',
            'amount' => 'required',
            'type_propertie' => 'required',
            'neighborhood' => 'required'
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
        
        $type = null;
        $trade = null;
        
        if(isset($request->type)){
            $exp = explode('-',$request->type);
            $type = $exp[1];
            $trade = $exp[0];
        }
        $propertie->client_id = $request->id;
        $propertie->amount = number_format(Helper::numberUnformat($request->amount), 2, '.', '');     
        $propertie->note = $request->note;
        $propertie->type_propertie = $request->type_propertie;
        $propertie->neighborhood = $request->neighborhood;
        $propertie->type = $type;
        $propertie->trade = $trade;
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
        $propertie->type_propertie = $request->type_propertie;
        $propertie->neighborhood = $request->neighborhood;
        $propertie->note = $request->note;
        
        if($propertie->save()){
            $retorno['message'] = 'Negócio atualizado com sucesso.';
            $retorno['success'] = true;
                $status = $propertie->properties_status;
                $status->status = $request->status;
                $status->save();
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao atualizar negócio.';
        $retorno['success'] = false;
        return response()->json($retorno);
        // dd($propertie);
    }
}
