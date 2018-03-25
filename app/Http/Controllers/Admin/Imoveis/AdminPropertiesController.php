<?php

namespace App\Http\Controllers\Admin\Imoveis;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Imovel\Properties;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

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
            'properties.created_at as date')
        ->join('clients', 'clients.id', '=', 'properties.client_id')
        ->where('clients.id', '=', $request->id);
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
}
