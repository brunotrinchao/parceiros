<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminReportController extends AdminController
{
    private $tipo = NULL;
    public function index(Request $request){
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        
        if($request->method() == 'POST'){
            $arr_periodo = ($request->periodo)? $request->periodo: date('Y-m-d').'|'.date('Y-m-d');
            $periodo = explode('|',$arr_periodo);
            $from = $periodo[0];
            $to = $periodo[1];
            $this->tipo = ($request->type)? $request->type : NULL;
        }
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
        ->join('users', 'users.id', '=', 'clients.user_id')   
        ->join('partners', 'partners.id', '=', 'users.partners_id')
        ->join('properties__buy__statuses', 'properties__buy__statuses.properties_id', '=', 'properties.id')
        ->when($this->tipo, function ($query) {
            $query->where('properties.trade', '=', $this->tipo);
            return $query;
        })
        ->when(auth()->user()->level == "S" || auth()->user()->level == "A" || auth()->user()->level == "G", function ($query) {
            $query->where('partners.id', '=', intval(auth()->user()->partners_id));
            return $query;
        })
        ->when(auth()->user()->level == 'U', function ($query) {
            $query->join('users', 'users.id', '=', 'clients.user_id')
                    ->where('users.id', '=', auth()->user()->id)
                    ->where('partners.id', '=', intval(auth()->user()->partners_id));
            return $query;
        })
        ->whereBetween('clients.date', array($from, $to))
        ->get();
        
        // $clients = DB::table('clients')
        // ->select('clients.id',
        // 'clients.user_id',
        // 'clients.birth',
        // 'clients.contact',
        // 'clients.cpf_cnpj',
        // 'clients.date',
        // 'clients.email',
        // 'clients.name',
        // 'clients.n_officials',
        // 'clients.sex',
        // 'clients.type')
        // ->join('users', 'users.id', '=', 'clients.user_id')
        // ->join('partners', 'partners.id', '=', 'users.partners_id')
        // ->when($this->tipo !== NULL, function ($query) {
        //     $query->where('clients.type', '=', $this->tipo);
        //     return $query;
        // })
        // ->when(auth()->user()->level == "S" || auth()->user()->level == "A" || auth()->user()->level == "G", function ($query) {
        //     $query->where('partners.id', '=', intval(auth()->user()->partners_id));
        //     return $query;
        // })
        // ->when(auth()->user()->level == "U", function ($query) {
        //     $query->where('users.id', '=', intval(auth()->user()->id))
        //         ->where('partners.id', '=', intval(auth()->user()->partners_id));
        //     return $query;
        // })
        // ->whereBetween('clients.date', array($from, $to))
        // ->get();
        // dd($properties);
        return view('admin.relatorios.index', compact('properties'));
    }
}
