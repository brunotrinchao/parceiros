<?php

namespace App\Http\Controllers\Admin\Consultoria;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Consultoria\Consultoria;
use App\Models\Client;
use App\Models\Contact;
use App\Helpers\Helper;

class AdminConsultoriaController extends AdminController
{
    public function index(Request $request, $type){
        $array['type'] = ($request->type == 'imovel')? 'I':'V';
        $array['type_name'] = ($request->type == 'imovel')? 'Imóvel':'Veículo';
        $array['type_slug'] = $type;
        
            $clients = DB::table('clients')
            ->select('clients.id',
            'clients.user_id',
            'clients.birth',
            DB::raw("DATE_FORMAT(clients.birth,'%d/%m/%Y' ) as birth_formatada"),
            'clients.contact',
            'clients.cpf_cnpj',
            'clients.date',
            'clients.email',
            'clients.name',
            'clients.n_officials',
            'clients.sex',
            'clients.type',
            DB::raw("DATE_FORMAT(clients.date,'%d/%m/%Y' ) as data_formatada"),
            DB::raw("GROUP_CONCAT(contacts.phone SEPARATOR ', ') AS phone"))
            ->leftJoin('contacts', 'clients.id', '=', 'contacts.client_id')
            ->when(auth()->user()->level == "S" || auth()->user()->level == "A" || auth()->user()->level == "G", function ($query) {
                $query->where('partners_id', '=', intval(auth()->user()->partners_id));
                return $query;
            })
            ->when(auth()->user()->level == "U", function ($query) {
                $query->where('users_id', '=', intval(auth()->user()->id))
                    ->where('partners_id', '=', intval(auth()->user()->partners_id));
                return $query;
            })
            ->groupBy('clients.id')
            ->get();
            
        return view('admin.consultoria-de-credito.indicacao.index', compact('clients', 'array'));
    }

    public function create(Request $request, Response $response, Consultoria $consultoria, Client $client, Contact $contact){
        
        $messagesRule = [
            'renda_comprovada.required' => 'Renda comprovada é obrigatório',
            'valor_bem.required' => 'Valor do bem é obrigatório',
            'valor_financiamento.required' => 'Valor do financiamento é obrigatório',
            'type.required' => 'Tipo é obrigatório',
        ];
        $validatedData = Validator::make($request->all(), [
            'renda_comprovada' => 'required',
            'valor_bem' => 'required',
            'valor_financiamento' => 'required',
            'type' => 'required'
        ], $messagesRule);

        if($validatedData->fails()){
            $arrMsg;
            foreach(json_decode($validatedData->messages()) as $t){
                $arrMsg[] = '- '. $t[0];
            }
            $retorno['message'] = implode('<br>', $arrMsg);
            $retorno['success'] = false; 
            return response()->json($retorno);
        }

        $consultoria->client_id = $request->client_id;
        $consultoria->partner_id = auth()->user()->partners_id;
        $consultoria->renda_comprovada = number_format($this->numberUnformat($request->renda_comprovada), 2, '.', '');
        $consultoria->valor_bem = number_format($this->numberUnformat($request->valor_bem), 2, '.', '');
        $consultoria->valor_financiado = number_format($this->numberUnformat($request->valor_financiamento), 2, '.', '');
        $consultoria->type = $request->type;
        $consultoria->note = $request->note;
        $consultoria->date = date('Y-m-d');
        
        $last_id = $consultoria->insert($consultoria);
        if($last_id['success']){
            $retorno['success'] = true;
            $retorno['message'] = 'Consultoria de crédito criado com sucesso.';
            $retorno['last_id'] = $last_id['last_insert_id'];
            return response()->json($retorno);
        }
        $retorno['success'] = false;
        $retorno['message'] = 'Erro ao criar consultoria de crédito.';
        return response()->json($retorno);
    }

    public function update(Request $request, Response $response, Consultoria $consultoria){
       
        $messagesRule = [
            'renda_comprovada.required' => 'Renda comprovada é obrigatório',
            'valor_bem.required' => 'Valor do bem é obrigatório',
            'valor_financiado.required' => 'Valor do financiamento é obrigatório',
            'status.required' => 'Status é obrigatório'
        ];
        $validatedData = Validator::make($request->all(), [
            'renda_comprovada' => 'required',
            'valor_bem' => 'required',
            'valor_financiado' => 'required',
            'status' => 'required'
        ], $messagesRule);

        if($validatedData->fails()){
            $arrMsg;
            foreach(json_decode($validatedData->messages()) as $t){
                $arrMsg[] = '- '. $t[0];
            }
            $retorno['message'] = implode('<br>', $arrMsg);
            $retorno['success'] = false; 
            return response()->json($retorno);
        }

        $consultoria = Consultoria::find($request->consultoria_id);
        $consultoria->id = $request->consultoria_id;
        $consultoria->renda_comprovada = Helper::unFormatMoney($request->renda_comprovada);
        $consultoria->valor_bem = Helper::unFormatMoney($request->valor_bem);
        $consultoria->valor_financiado = Helper::unFormatMoney($request->valor_financiado);
        $consultoria->note = $request->note;
        $consultoria->status = $request->status;

        if($consultoria->save()){
            $retorno['message'] = 'Negócio atualizado com sucesso.';
            $retorno['success'] = true;
            return response()->json($retorno);
        }

        $retorno['message'] = 'Erro ao atualizar negócio.';
        $retorno['success'] = false;
        return response()->json($retorno);
    }

    public function single(Request $request, $type, $id){
        $array['type'] = ($request->type == 'imovel')? 'I':'V';
        $array['type_name'] = ($request->type == 'imovel')? 'Imóvel':'Veículo';
        $array['type_slug'] = $type;
        
        $cliente =  $this->getCliente($id)[0];
        $consultorias =  $this->getConsultoria($id, $array['type']);
        
        return view('admin.consultoria-de-credito.indicacao.single', compact('cliente', 'consultorias', 'array'));
    }

    public function bancos(Request $request){
        $session = session()->get('portalparceiros');   
        $id_produto = $session['produtos']['id_produto']; 
        $bancos = [];
        $bancos_parceiros = DB::table('bancos_parceiros')
            ->select(
                'bancos_parceiros.id',
                'bancos_parceiros.category_id',
                'bancos_parceiros.product_id',
                'bancos_parceiros.name',
                'bancos_parceiros.description',
                'bancos_parceiros.status',
                'bancos_parceiros.image',
                'bancos_parceiros.created_at as date',
                'products.name as name_product',
                'products.slug as slug_product',
                'bancos_categorias.id as name_bancos_id',
                'bancos_categorias.name as name_bancos_categorias'
            )
            ->join('products', 'products.id', '=', 'bancos_parceiros.product_id')
            ->join('bancos_categorias', 'bancos_categorias.id', '=', 'bancos_parceiros.category_id')
            ->where('bancos_parceiros.product_id', $id_produto)
            ->where('bancos_parceiros.status', 'A')
            ->orderBy('bancos_parceiros.created_at')
            ->get();
            $array = json_decode(json_encode($bancos_parceiros));
            
            foreach($array as $key => $value){
                $bancos[$value->name_bancos_categorias][] = $array[$key];
            }
            
        return view('admin.consultoria-de-credito.bancos', compact('bancos'));
    }

    private function getCliente($id){
        $sql = 'SELECT clients.id,clients.partners_id, 
                        clients.user_id, 
                        clients.name, 
                        clients.email, 
                        clients.birth,
                        DATE_FORMAT(clients.birth,"%d/%m/%Y" ) as birth_formatada,
                        clients.sex,
                        CASE clients.sex 
                            WHEN "M" THEN "Masculino" 
                            ELSE "Feminino" END as sex_formatado,
                        clients.type,
                        clients.cpf_cnpj,
                        clients.contact,
                        clients.n_officials,
                        clients.date,
            DATE_FORMAT(clients.date,"%d/%m/%Y" ) as date_formatada,
            GROUP_CONCAT(contacts.phone) AS phone
            FROM clients 
            INNER JOIN contacts ON clients.id = contacts.client_id 
            WHERE 1 = 1 AND clients.id = ' . $id;
        return json_decode(json_encode(DB::select(DB::raw($sql))), true);
    }

    private function getConsultoria($client_id, $type){
        $sql = 'SELECT id,
                        type,
                        valor_bem,
                        renda_comprovada,
                        valor_financiado,
                        note,
                        date,
                        DATE_FORMAT(date,"%d/%m/%Y" ) as date_formatada,
                        status,
                        CASE status 
                            WHEN "A" THEN "Aguardando contato" 
                            WHEN "C" THEN "Contactado" 
                            WHEN "I" THEN "Inconsistente" 
                            WHEN "V" THEN "Visitado" 
                            WHEN "E" THEN "Em negociação" 
                            END as status_formatado
                        FROM consultorias
                        WHERE 1 = 1 
                        AND consultorias.client_id = ' . $client_id . ' 
                        AND consultorias.type = "' .$type. '"
                        ORDER BY created_at';
        return json_decode(json_encode(DB::select(DB::raw($sql))), true);
    }

    private static function numberUnformat($number)
    {
        $ret = null;
        if (!empty($number)) {
            $ret = str_replace(',', '.', str_replace('.', '', $number));
            $ret = str_replace('R$ ', '', $ret);
            $ret = str_replace('% ', '', $ret);
        }
        return $ret;
    }
}
