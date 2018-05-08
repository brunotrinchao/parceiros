<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminProdutoController extends AdminController
{
    private $indicador;
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

        switch ($produto) {
            case 'imoveis':
                $this->indicadoresImoveis();
                break;
            case 'financiamento':
                $this->indicadoresFinanciamentos();
                break;
            case 'consultoria-de-credito':
                $this->indicadoresConsultorias();
                break;
            case 'oi':
                $this->indicadoresOi();
                break;
        }
        $indicador = $this->indicador;
// dd($indicador);
        return view('admin.dashboard.index', compact('indicador'));
    }

    public function indicadoresImoveis(){
        $sql = '
        SELECT COUNT(*) as "Indicadores",
        IFNULL(SUM(CASE WHEN trade = "C" THEN 1 ELSE 0 END), 0) "Compras",
        IFNULL(SUM(CASE WHEN trade = "V" THEN 1 ELSE 0 END), 0) "Vendas",
        IFNULL(SUM(CASE WHEN trade = "A" THEN 1 ELSE 0 END), 0) "Alugueis",
        IFNULL(SUM(CASE WHEN status = "A" THEN 1 ELSE 0 END), 0) "Aguardando contato",
        IFNULL(SUM(CASE WHEN status = "C" THEN 1 ELSE 0 END), 0) "Contactado",
        IFNULL(SUM(CASE WHEN status = "E" THEN 1 ELSE 0 END), 0) "Em negociação",
        IFNULL(SUM(CASE WHEN status = "I" THEN 1 ELSE 0 END), 0) "Incosistente",
        IFNULL(SUM(CASE WHEN status = "V" THEN 1 ELSE 0 END), 0) "Visitado"
		FROM properties
		WHERE 1 = 1';

        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= 'AND partners.id = '. intval(auth()->user()->partners_id);
        }

        if(auth()->user()->level == 'U') {
            $sql .= ' AND users.id = '. auth()->user()->id;
        }
        $this->indicador = json_decode(json_encode(DB::select(DB::raw($sql))), true);  
    }
    public function indicadoresFinanciamentos(){
        $sql = "
        SELECT COUNT(*) as 'Indicadores',
            IFNULL(SUM(CASE WHEN type = 'T' THEN 1 ELSE 0 END), 0) 'Tradicional',
            IFNULL(SUM(CASE WHEN type = 'R' THEN 1 ELSE 0 END), 0) 'Refinanciamento',
            IFNULL(SUM(CASE
                WHEN status = 'A' THEN 1
                ELSE 0
            END), 0) 'Aguardando contato',
            IFNULL(SUM(CASE
                WHEN status = 'C' THEN 1
                ELSE 0
            END), 0) 'Contactado',
            IFNULL(SUM(CASE
                WHEN status = 'E' THEN 1
                ELSE 0
            END), 0) 'Em negociação',
            IFNULL(SUM(CASE
                WHEN status = 'I' THEN 1
                ELSE 0
            END), 0) 'Incosistente',
            IFNULL(SUM(CASE
                WHEN status = 'V' THEN 1
                ELSE 0
            END), 0) 'Visitado'
                    FROM financiamentos
            WHERE 1 = 1";

        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= 'AND partners.id = '. intval(auth()->user()->partners_id);
        }

        if(auth()->user()->level == 'U') {
            $sql .= ' AND users.id = '. auth()->user()->id;
        }
        $this->indicador = json_decode(json_encode(DB::select(DB::raw($sql))), true);  
    }
    public function indicadoresConsultorias(){
        $sql = '
        SELECT COUNT(*) as "Indicadores",
            IFNULL(SUM(CASE WHEN type = "I" THEN 1 ELSE 0 END), 0) "Imoveis",
            IFNULL(SUM(CASE WHEN type = "V" THEN 1 ELSE 0 END), 0) "Veiculos",
            IFNULL(SUM(CASE
                WHEN status = "A" THEN 1
                ELSE 0
            END), 0) "Aguardando contato",
            IFNULL(SUM(CASE
                WHEN status = "C" THEN 1
                ELSE 0
            END), 0) "Contactado",
            IFNULL(SUM(CASE
                WHEN status = "E" THEN 1
                ELSE 0
            END), 0) "Em negociação",
            IFNULL(SUM(CASE
                WHEN status = "I" THEN 1
                ELSE 0
            END), 0) "Incosistente",
            IFNULL(SUM(CASE
                WHEN status = "V" THEN 1
                ELSE 0
            END), 0)"Visitado"
                    FROM financiamentos
            WHERE 1 = 1';

        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= 'AND partners.id = '. intval(auth()->user()->partners_id);
        }

        if(auth()->user()->level == 'U') {
            $sql .= ' AND users.id = '. auth()->user()->id;
        }
        $this->indicador = json_decode(json_encode(DB::select(DB::raw($sql))), true);  
    }
    public function indicadoresOi(){
        $sql = '
        SELECT COUNT(*) as "Indicadores",
            IFNULL(SUM(CASE
                WHEN status = "A" THEN 1
                ELSE 0
            END), 0) "Aguardando contato",
            IFNULL(SUM(CASE
                WHEN status = "C" THEN 1
                ELSE 0
            END), 0) "Contactado",
            IFNULL(SUM(CASE
                WHEN status = "E" THEN 1
                ELSE 0
            END), 0) "Em negociação",
            IFNULL(SUM(CASE
                WHEN status = "I" THEN 1
                ELSE 0
            END), 0) "Incosistente",
            IFNULL(SUM(CASE
                WHEN status = "V" THEN 1
                ELSE 0
            END), 0) "Visitado"
                    FROM atendimentos
            WHERE 1 = 1';

        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= 'AND partners.id = '. intval(auth()->user()->partners_id);
        }

        if(auth()->user()->level == 'U') {
            $sql .= ' AND users.id = '. auth()->user()->id;
        }
        $this->indicador = json_decode(json_encode(DB::select(DB::raw($sql))), true);  
    }
    
}
