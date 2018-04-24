<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminReportController extends AdminController
{
    private $tipo = NULL;

    public function index(){

        $indicadores = DB::table('properties')
        ->select(DB::raw('COUNT(*) as indicadores,
        IFNULL(SUM(CASE WHEN trade = "C" THEN 1 ELSE 0 END), 0) as compra,
        IFNULL(SUM(CASE WHEN trade = "V" THEN 1 ELSE 0 END), 0) as venda,
        IFNULL(SUM(CASE WHEN trade = "A" THEN 1 ELSE 0 END), 0) as aluguel'))
        ->join('clients', 'clients.id', '=', 'properties.client_id')
        ->join('users', 'users.id', '=', 'clients.user_id')   
        ->join('partners', 'partners.id', '=', 'users.partners_id')
        ->whereBetween('properties.created_at', array(date('Y-m-d 00:00:01'), date('Y-m-d 23:59:59')))
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
        ->get();
        
        $relatorios = DB::table('properties')
        ->select(DB::raw('properties.type_propertie as imovel,
        properties.amount as preco,
        clients.name as cliente,
        users.name as usuario,
        (CASE WHEN trade = "C" THEN "Compra"
        WHEN trade = "A" THEN "Aluguel"
        WHEN trade = "V" THEN "Venda" END) as negocio,
        properties.created_at as data'))
        ->join('clients', 'clients.id', '=', 'properties.client_id')
        ->join('users', 'users.id', '=', 'clients.user_id')   
        ->join('partners', 'partners.id', '=', 'users.partners_id')
        ->whereBetween('properties.created_at', array(date('Y-m-d 00:00:01'), date('Y-m-d 23:59:59')))
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
        ->get();

        return view('admin.relatorios.index', compact('indicadores', 'relatorios'));
    }

    public function search(Request $request){
        
        if(!isset($request->periodo)){
            return view('admin.relatorios.index');
        }

        $title = [
            'titulo' => 'Filtro: '. $request->periodo_range,
            'periodo' => $request->periodo,
            'periodo_range' => $request->periodo_range
        ];

        $periodo = explode('|',$request->periodo);
        $from = $periodo[0];
        $to = $periodo[1];
        $this->tipo = ($request->type)? $request->type : NULL;

        $indicadores = DB::table('properties')
        ->select(DB::raw('COUNT(*) as indicadores,
        SUM(CASE WHEN trade = "C" THEN 1 ELSE 0 END) compra,
        SUM(CASE WHEN trade = "V" THEN 1 ELSE 0 END) venda,
        SUM(CASE WHEN trade = "A" THEN 1 ELSE 0 END) aluguel'))
        ->join('clients', 'clients.id', '=', 'properties.client_id')
        ->join('users', 'users.id', '=', 'clients.user_id')   
        ->join('partners', 'partners.id', '=', 'users.partners_id')
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
        ->when($from != $to, function ($query) use ($from, $to) {
            $query->whereBetween('properties.created_at', array($from, $to));
            return $query;
        })
        ->when($from == $to, function ($query) use ($from) {
            $query->where('properties.created_at', $from);
            return $query;
        })
        ->get();

        $relatorios = DB::table('properties')
        ->select(DB::raw('properties.type_propertie as imovel,
        properties.amount as preco,
        clients.name as cliente,
        users.name as usuario,
        (CASE WHEN trade = "C" THEN "Compra"
        WHEN trade = "A" THEN "Aluguel"
        WHEN trade = "V" THEN "Venda" END) as negocio,
        properties.created_at as data'))
        ->join('clients', 'clients.id', '=', 'properties.client_id')
        ->join('users', 'users.id', '=', 'clients.user_id')   
        ->join('partners', 'partners.id', '=', 'users.partners_id')
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
        ->when($from != $to, function ($query) use ($from, $to) {
            $query->whereBetween('properties.created_at', array($from, $to));
            return $query;
        })
        ->when($from == $to, function ($query) use ($from) {
            $query->where('properties.created_at', $from);
            return $query;
        })
        ->get();

        return view('admin.relatorios.search', compact('title', 'indicadores', 'relatorios'));
    }
}
