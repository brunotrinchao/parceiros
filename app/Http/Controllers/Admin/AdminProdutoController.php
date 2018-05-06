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
                'slug_produto' => 'imoveis',
                'id_produto' => 1
            ],
            'oi' => [
                'url_produto' => $produto,
                'name_produto' => 'Oi',
                'slug_produto' => 'oi',
                'id_produto' => 2
            ],
            'financiamento' => [
                'url_produto' => $produto,
                'name_produto' => 'Financiamento',
                'slug_produto' => 'financiamento',
                'id_produto' => 3
            ],
            'consultoria-de-credito' => [
                'url_produto' => $produto,
                'name_produto' => 'Consultoria de crédito',
                'slug_produto' => 'consultoria-de-credito',
                'id_produto' => 4
            ],
        ];
        session()->put('portalparceiros.produtos', $produtos[$produto]);
        
        return view('admin.dashboard.index');
    }
    
}
