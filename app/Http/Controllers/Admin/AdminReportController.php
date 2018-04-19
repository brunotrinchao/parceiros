<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminReportController extends AdminController
{
    private $tipo = NULL;

    public function index(){
        return view('admin.relatorios.index');
    }

    public function search(Request $request){
        
        $title = ['titulo' => 'Filtro: '. $request->periodo_range];

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
        ->whereBetween('properties.created_at', array($from, $to))
        ->get();

        

        return view('admin.relatorios.search', compact('title', 'indicadores'));
    }
}
