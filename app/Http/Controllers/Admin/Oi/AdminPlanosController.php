<?php

namespace App\Http\Controllers\Admin\Oi;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\DB;
use App\Models\Oi\PlanosCategoria;

class AdminPlanosController extends AdminController
{
    public function planos(Request $request, $plano){
        $categoria = PlanosCategoria::where('url', $plano)->first();

        $planos = DB::table('planos')
            ->select(
                'planos.id',
                'planos.planos_category_id',
                'planos.name',
                'planos.description',
                'planos.status',
                'planos.created_at as date',
                'planos_categorias.name as name_category',
                'planos_categorias.url as url_category'
            )
            ->join('planos_categorias', 'planos_categorias.id', '=', 'planos.planos_category_id')
            ->where('planos_categorias.url', $plano)
            ->orderby('planos_categorias.name')
            ->orderby('planos.created_at')
            ->get();
            $planos = json_decode(json_encode($planos), true);
        return view('admin.oi.planos.index', compact('planos', 'categoria'));
    }

}
