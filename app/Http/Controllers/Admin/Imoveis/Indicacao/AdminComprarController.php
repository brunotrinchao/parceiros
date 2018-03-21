<?php

namespace App\Http\Controllers\Admin\Imoveis\Indicacao;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Client;
use App\Models\Imovel\Properties;
use App\Models\Contact;

class AdminComprarController extends AdminController
{

    private $totalPage = 2;

    public function index(){
        $clients = Client::where('clients.user_id', auth()->user()->id)
                            ->paginate($this->totalPage);
        foreach ($clients as $key => $client) {
            $addresses = $client->addresses;
            $contacts = $client->contacts;
            $properties = $client->properties;
            foreach ($properties as $key => $propertie) {
                $properties_status = $propertie->properties_status;
            }
        }
        // dd($clients);
        return view('admin.imoveis.proprietario.comprar', compact('clients'));
    }

    public function insertBuy(Request $request, Response $response,Client $client, Properties $propertie, Contact $contact){
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
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


    public function search(Request $request, Client $clients){
        $dataFrom = $request->all();
        $searchClients = $clients->search($dataFrom, $this->totalPage);
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
