<?php

namespace App\Http\Controllers\Admin\Financiamento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Financiamento\Financiamento;

class AdminFinanciamentoController extends AdminController
{
    public function index(Request $request, $type){
        $indicacao = Financiamento::where('type', $type)->get();

        return view('admin.financiamento.indicacao.index', compact('indicacao', 'type'));
    }
}
