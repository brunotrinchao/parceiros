<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminReportController extends AdminController
{
    private $tipo = NULL;
    private $trade = NULL;
    private $status = NULL;
    private $produto = NULL;
    private $titulo = NULL;
    private $to = NULL;
    private $from = NULL;
    private $relatorio = NULL;
    private $indicadores = NULL;
    private $produto_page = null;

    public function index(Request $request, $produto){
        $this->produto_page = $produto;
        if($request->method() == 'POST'){          
            $this->title = [
                'titulo' => 'Filtro: '. $request->periodo_range,
                'periodo' => $request->periodo,
                'periodo_range' => $request->periodo_range
            ];
            $periodo = explode('|',$request->periodo);
            $this->from = $periodo[0];
            $this->to = $periodo[1];
            $this->status = $request->status;

            switch ($produto) {
                case 'imoveis':
                    if($request->tipo){
                        $exp = explode('-',$request->tipo);
                        $this->tipo = $exp[0];
                        $this->trade = $exp[1];
                    }
                    $this->getImoveis();
                    $indicadores = $this->getIndicadoresImoveis(json_decode(json_encode($this->indicadores))[0]);
                    break;
                case 'financiamento':
                    $this->tipo = $request->tipo;
                    $this->getFinanciamentos();
                    $indicadores = $this->getIndicadoresFinanciamento($this->indicadores);
                    break;
                case 'consultoria-de-credito':
                    $this->tipo = $request->tipo;
                    $this->getConsultorias();
                    $indicadores = $this->getIndicadoresConsultorias($this->indicadores);
                    break;
                case 'oi':
                    $this->tipo = $request->tipo;
                    $this->getOi();
                    $indicadores = $this->getIndicadoresOi($this->indicadores);
                    break;
            }
            $relatorios = $this->relatorio;
            // dd($relatorios);
            return view('admin.'.$this->produto_page.'.relatorios.index', compact('relatorios','indicadores'));
        }
        $relatorios = null;
        $indicadores = null;
        return view('admin.'.$this->produto_page.'.relatorios.index', compact('relatorios','indicadores'));
    }

    // Imoveis
    public function getImoveis(){
        $this->indicadores = DB::table('properties')
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
        ->when($this->from != $this->to, function ($query) {
            $query->whereBetween('properties.created_at', array($this->from, $this->to));
            return $query;
        })
        ->when($this->from == $this->to, function ($query) {
            $query->where('properties.created_at', $this->from);
            return $query;
        })
        ->when($this->tipo != null, function ($query) {
            $query->where('properties.type', $this->tipo);
            return $query;
        })
        ->when($this->trade != null, function ($query) {
            $query->where('properties.trade', $this->trade);
            return $query;
        })
        ->when($this->status != null, function ($query) {
            $query->where('properties.status', $this->status);
            return $query;
        })
        ->get();

        $this->relatorio = DB::table('properties')
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
        ->when($this->from != $this->to, function ($query) {
            $query->whereBetween('properties.created_at', array($this->from, $this->to));
            return $query;
        })
        ->when($this->from == $this->to, function ($query) {
            $query->where('properties.created_at', $this->from);
            return $query;
        })

        ->when($this->tipo != null, function ($query) {
            $query->where('properties.type', $this->tipo);
            return $query;
        })
        ->when($this->trade != null, function ($query) {
            $query->where('properties.trade', $this->trade);
            return $query;
        })
        ->when($this->status != null, function ($query) {
            $query->where('properties.status', $this->status);
            return $query;
        })
        ->get();        
    }

    private function getIndicadoresImoveis($resultado){
        $html = '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box  bg-light-blue-active">
                <span class="info-box-icon" style="width:70px">'.$resultado->indicadores.'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Indicações</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        $html .= '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua-active">
                <span class="info-box-icon" style="width:70px">'.$resultado->compra.'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Compra</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        $html .= '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green-active">
                <span class="info-box-icon" style="width:70px">'.$resultado->venda.'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Venda</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        $html .= '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-yellow-active">
                <span class="info-box-icon" style="width:70px">'.$resultado->aluguel.'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Aluguel</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        return  $html;
    }

    // Financiamento
    public function getFinanciamentos(){
        $sqlIndicadores = '
            SELECT COUNT(*) as indicadores,
            SUM(CASE WHEN type = "T" THEN 1 ELSE 0 END) tradicional,
            SUM(CASE WHEN type = "R" THEN 1 ELSE 0 END) refinanciamento
            FROM financiamentos
            WHERE 1 = 1
        ';
        $sql = "
        SELECT financiamentos.id,
            financiamentos.client_id,
            financiamentos.partner_id,
            financiamentos.type,
            financiamentos.valor_bem,
            financiamentos.renda_comprovada,
            financiamentos.valor_financiamento,
            financiamentos.date,
            financiamentos.status,
            financiamentos.created_at,
            financiamentos.updated_at,
            (CASE financiamentos.status 
            WHEN 'A' THEN 'Aguardando contato'
            WHEN 'C' THEN 'Contactado'
            WHEN 'E' THEN 'Em negociação'
            WHEN 'I' THEN 'Incosistente'
            WHEN 'V' THEN 'Visitado'
            END) as status_formatado,
            (CASE financiamentos.type 
            WHEN 'T' THEN 'Tradicional'
            WHEN 'R' THEN 'Refinanciamento'
            END) as type_formatado,
            DATE_FORMAT(financiamentos.date,'%d/%m/%Y' ) as date_formatada,
            clients.name as client_name,
            partners.name as partner_name
        FROM supercredito_db.financiamentos
        LEFT JOIN clients ON financiamentos.client_id = clients.id
        LEFT JOIN users ON clients.user_id = users.id
        LEFT JOIN partners ON financiamentos.partner_id = partners.id
        WHERE 1= 1";

        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= 'AND partners.id = '. intval(auth()->user()->partners_id);
            $sqlIndicadores .= 'AND partners.id = '. intval(auth()->user()->partners_id);
        }

        if(auth()->user()->level == 'U') {
            $sql .= ' AND users.id = '. auth()->user()->id;
            $sqlIndicadores .= ' AND users.id = '. auth()->user()->id;
        }

        if($this->from != $this->to) {
            $sql .= ' AND financiamentos.date BETWEEN  "'.$this->from.'" AND "'.$this->to.'"';
            $sqlIndicadores .= ' AND financiamentos.date BETWEEN  "'.$this->from.'" AND "'.$this->to.'"';
        }else{
            $sql .= ' AND financiamentos.date = "'.$this->from.'"';
            $sqlIndicadores .= ' AND financiamentos.date = "'.$this->from.'"';
        }

        if($this->tipo != null) {
            $sql .= ' AND financiamentos.type = "' . $this->tipo . '"';
            $sqlIndicadores .= ' AND financiamentos.type = "' . $this->tipo . '"';
        }
        if($this->status != null) {
            $sql .= ' AND financiamentos.status = "' . $this->status .'"';
            $sqlIndicadores .= ' AND financiamentos.status = "' . $this->status .'"';
        }
        $this->relatorio =  json_decode(json_encode(DB::select(DB::raw($sql))), true);    
        $this->indicadores =  json_decode(json_encode(DB::select(DB::raw($sqlIndicadores))), true);    
    }

    private function getIndicadoresFinanciamento($resultado){
        $html = '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box  bg-light-blue-active">
                <span class="info-box-icon" style="width:70px">'.$resultado[0]['indicadores'].'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Indicações</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        $html .= '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua-active">
                <span class="info-box-icon" style="width:70px">'.$resultado[0]['tradicional'].'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Tradicional</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        $html .= '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green-active">
                <span class="info-box-icon" style="width:70px">'.$resultado[0]['refinanciamento'].'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Refinanciamento</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        return  $html;
    }

    // Consultorias
    public function getConsultorias(){
        $sqlIndicadores = '
            SELECT COUNT(*) as indicadores,
            SUM(CASE WHEN type = "I" THEN 1 ELSE 0 END) imoveis,
            SUM(CASE WHEN type = "V" THEN 1 ELSE 0 END) veiculos
            FROM consultorias
            WHERE 1 = 1
        ';
        $sql = "
        SELECT consultorias.id,
            consultorias.client_id,
            consultorias.partner_id,
            consultorias.renda_comprovada,
            consultorias.valor_bem,
            consultorias.valor_financiado,
            consultorias.note,
            consultorias.type,
            consultorias.date,
            consultorias.status,
            consultorias.created_at,
            consultorias.updated_at,
            (CASE consultorias.status 
            WHEN 'A' THEN 'Aguardando contato'
            WHEN 'C' THEN 'Contactado'
            WHEN 'E' THEN 'Em negociação'
            WHEN 'I' THEN 'Incosistente'
            WHEN 'V' THEN 'Visitado'
            END) as status_formatado,
            (CASE consultorias.type 
            WHEN 'T' THEN 'Tradicional'
            WHEN 'R' THEN 'Refinanciamento'
            END) as type_formatado,
            DATE_FORMAT(consultorias.date,'%d/%m/%Y' ) as date_formatada,
            clients.name as client_name,
            partners.name as partner_name
        FROM consultorias
        LEFT JOIN clients ON consultorias.client_id = clients.id
        LEFT JOIN users ON clients.user_id = users.id
        LEFT JOIN partners ON consultorias.partner_id = partners.id
        WHERE 1= 1";

        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= 'AND partners.id = '. intval(auth()->user()->partners_id);
        }

        if(auth()->user()->level == 'U') {
            $sql .= ' AND users.id = '. auth()->user()->id;
            $sqlIndicadores .= ' AND users.id = '. auth()->user()->id;
        }

        if($this->from != $this->to) {
            $sql .= ' AND consultorias.date BETWEEN  "'.$this->from.'" AND "'.$this->to.'"';
            $sqlIndicadores .= ' AND consultorias.date BETWEEN  "'.$this->from.'" AND "'.$this->to.'"';
        }else{
            $sql .= ' AND consultorias.date = "'.$this->from.'"';
            $sqlIndicadores .= ' AND consultorias.date = "'.$this->from.'"';
        }

        if($this->tipo != null) {
            $sql .= ' AND consultorias.type = "' . $this->tipo . '"';
            $sqlIndicadores .= ' AND consultorias.type = "' . $this->tipo . '"';
        }
        if($this->status != null) {
            $sql .= ' AND consultorias.status = "' . $this->status .'"';
            $sqlIndicadores .= ' AND consultorias.status = "' . $this->status .'"';
        }
        $this->relatorio =  json_decode(json_encode(DB::select(DB::raw($sql))), true);    
        $this->indicadores =  json_decode(json_encode(DB::select(DB::raw($sqlIndicadores))), true);    
    }

    private function getIndicadoresConsultorias($resultado){
        $html = '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box  bg-light-blue-active">
                <span class="info-box-icon" style="width:70px">'.$resultado[0]['indicadores'].'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Indicações</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        $html .= '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua-active">
                <span class="info-box-icon" style="width:70px">'.$resultado[0]['imoveis'].'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Imóveis</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        $html .= '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green-active">
                <span class="info-box-icon" style="width:70px">'.$resultado[0]['veiculos'].'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Veículos</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        return  $html;
    }

    // Oi
    public function getOi(){
        $sqlIndicadores = '
            SELECT COUNT(*) as indicadores
            FROM atendimentos
            WHERE 1 = 1
        ';
        $sql = "
        SELECT atendimentos.id,
            atendimentos.client_id,
            atendimentos.partner_id,
            atendimentos.note,
            atendimentos.status,
            atendimentos.date,
            atendimentos.created_at,
            atendimentos.updated_at,
            (CASE atendimentos.status 
            WHEN 'A' THEN 'Aguardando contato'
            WHEN 'C' THEN 'Contactado'
            WHEN 'E' THEN 'Em negociação'
            WHEN 'I' THEN 'Incosistente'
            WHEN 'V' THEN 'Visitado'
            END) as status_formatado,
            DATE_FORMAT(atendimentos.date,'%d/%m/%Y' ) as date_formatada,
            clients.name as client_name,
            partners.name as partner_name
        FROM atendimentos
        LEFT JOIN clients ON atendimentos.client_id = clients.id
        LEFT JOIN users ON clients.user_id = users.id
        LEFT JOIN partners ON atendimentos.partner_id = partners.id
        WHERE 1= 1";

        if(auth()->user()->level == "U" || auth()->user()->level == "A" || auth()->user()->level == "G"){
            $sql .= 'AND partners.id = '. intval(auth()->user()->partners_id);
        }

        if(auth()->user()->level == 'U') {
            $sql .= ' AND users.id = '. auth()->user()->id;
            $sqlIndicadores .= ' AND users.id = '. auth()->user()->id;
        }

        if($this->from != $this->to) {
            $sql .= ' AND atendimentos.date BETWEEN  "'.$this->from.'" AND "'.$this->to.'"';
            $sqlIndicadores .= ' AND atendimentos.date BETWEEN  "'.$this->from.'" AND "'.$this->to.'"';
        }else{
            $sql .= ' AND atendimentos.date = "'.$this->from.'"';
            $sqlIndicadores .= ' AND atendimentos.date = "'.$this->from.'"';
        }

        if($this->status != null) {
            $sql .= ' AND atendimentos.status = "' . $this->status .'"';
            $sqlIndicadores .= ' AND atendimentos.status = "' . $this->status .'"';
        }
        $this->relatorio =  json_decode(json_encode(DB::select(DB::raw($sql))), true);    
        $this->indicadores =  json_decode(json_encode(DB::select(DB::raw($sqlIndicadores))), true);    
    }
    
    private function getIndicadoresOi($resultado){
        $html = '
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box  bg-light-blue-active">
                <span class="info-box-icon" style="width:70px">'.$resultado[0]['indicadores'].'</span>
                <div class="info-box-content" style="margin-left:70px">
                <span class="info-box-text">Indicações</span>
                <span class="info-box-number"></span>
                </div>
            </div>
        </div>
        ';
        return  $html;
    }
}
