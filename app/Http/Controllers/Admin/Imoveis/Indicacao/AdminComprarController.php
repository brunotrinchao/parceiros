<?php

namespace App\Http\Controllers\Admin\Imoveis\Indicacao;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Imovel\Properties;
use App\Models\Contact;
use App\User;

class AdminComprarController extends AdminController
{

    private $totalPage = 2;

    public function index(){

        $clients = DB::table('clients')
        ->select('clients.id',
        'clients.user_id',
        'clients.birth',
        'clients.contact',
        'clients.cpf_cnpj',
        'clients.date',
        'clients.email',
        'clients.name',
        'clients.n_officials',
        'clients.sex',
        'clients.type')
        // ->selectRaw('GROUP_CONCAT(contacts.phone) AS phone')
        ->join('users', 'users.id', '=', 'clients.user_id')
        ->join('partners', 'partners.id', '=', 'users.partner_id')
        // ->join('contacts', 'clients.id', '=', 'contacts.client_id')
        ->where('users.id', '=', intval(auth()->user()->id))
        // ->groupBy('contacts.client_id')
        // ->toSql();
        ->get();

        return view('admin.imoveis.proprietario.comprar', compact('clients'));
    }

    public function insertBuy(Request $request, Response $response,Client $client, Properties $propertie, Contact $contact){
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:clients,email',
            'cpf_cnpj' => 'required|unique:clients,cpf_cnpj',
            'birth' => 'required'
        ]);
        // Cliente
        $client->user_id = auth()->user()->id;
        $client->name = $request->name;
        $client->email = $request->email;
        $client->birth = date('Y-m-d', strtotime(str_replace('/','-',$request->birth)));
        $client->sex = $request->sex;
        $client->cpf_cnpj = str_replace('-','',str_replace('.','',$request->cpf_cnpj));
        $client->contact = $request->contact;
        $client->date = date('Y-m-d');
        $client->type = 'F';
        $clientInsert = $client->insert($client);
        if($clientInsert['success']){
            // Imovel
            $propertie->client_id = $clientInsert['last_insert_id'];
            $propertie->amount = number_format($this->numberUnformat($request->amount), 2, '.', '');     
            $propertie->note = $request->note;
            $propertie->type_propertie = $request->type_propertie;
            $propertie->neighborhood = $request->neighborhood;
            $propertie->type = 'T';
            $propertieInsert = $propertie->insert($propertie);
            // Contato
            $contactInsert = $contact->insert($clientInsert['last_insert_id'], $request->phone);
        }
        return json_encode($clientInsert);
        
    }


    public function search(Request $request, Client $client){
        $dataFrom = $request->all();
        $clients = $client->search($dataFrom, $this->totalPage);
        $properties = $client->formatStatusPropertie(null);

        return view('admin.imoveis.proprietario.comprar', compact('clients', 'properties'));
    }

    public function getClient($id){
        $clients = Client::where('clients.id', $id)
                        ->first();
        $addresses = $clients->addresses;
        $contacts = $clients->contacts;
        $properties = $clients->properties;
        foreach ($properties as $key => $propertie) {
            $properties_status = $propertie->properties_status;
        }
        if(!empty($clients)){
            $data['clients'] = $clients; 
            $data['success'] = true; 
            $data['message'] = 'Cliente carregado com sucesso.'; 
        }else{
            $data['success'] = false; 
            $data['message'] = 'Erro ao carregar cliente.'; 
        }
        return $data;
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
