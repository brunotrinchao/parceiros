<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProdutoController extends AdminController
{

    public function index($produto){
        $produtos = [
            'imoveis' => [
                'url_produto' => $produto,
                'name_produto' => 'Imóveis',
                'id_produto' => 1
            ],
            'oi' => [
                'url_produto' => $produto,
                'name_produto' => 'Oi',
                'id_produto' => 2
            ],
            'financiamento' => [
                'url_produto' => $produto,
                'name_produto' => 'Financiamento',
                'id_produto' => 3
            ],
            'consultoria-de-credito' => [
                'url_produto' => $produto,
                'name_produto' => 'Consultoria',
                'id_produto' => 4
            ],
        ];
        session()->put('portalparceiros.produtos', $produtos[$produto]);
        
        return view('admin.'.$produtos[$produto]['url_produto'].'.dashboard.index');
    }
    
}
