<?php

namespace App\Http\Controllers\Admin\Imoveis\Indicacao\Proprietario;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Imovel\Buy;

class AdminComprarController extends AdminController
{
    public function index(){

        $users = DB::table('users')
                    ->select(	
                        'users.name AS user_name',
                        'users.level AS user_level',
                        'users.status AS user_status',
                        'users.image AS user_image',
                        'users.email AS user_email',
                        'clients.name AS client_name',
                        'clients.id AS client_id',
                        'clients.email AS client_email',
                        'clients.type AS client_type',
                        'clients.sex AS client_sex',
                        'clients.n_officials AS client_n_officials',
                        'clients.date AS client_date',
                        'clients.cpf_cnpj AS client_cpf_cnpj',
                        'clients.contact AS client_contact',
                        'clients.birth AS client_birth',
                        'addresses.street AS address_street',
                        'addresses.neighborhood AS address_neighborhood',
                        'addresses.zip_code AS address_zip_code',
                        'addresses.state AS address_state',
                        'addresses.number AS address_number',
                        'addresses.complement AS address_complement',
                        'addresses.city AS address_city',
                        'contacts.phone AS contact_phone' )
                        ->join('clients', 'clients.id_user', '=', 'users.id')
                        ->join('addresses', 'addresses.id_client', '=', 'clients.id')
                        ->join('contacts', 'contacts.id_client', '=', 'clients.id');


        
        if(auth()->user()->level != 'S' || auth()->user()->level != 'P' || auth()->user()->level != 'G'){
            $users->where('users.id', auth()->user()->id);
        }

        $clients = $users->get();
        return view('admin.imoveis.proprietario.comprar', compact('clients'));
    }

    public function insertBuy(Request $request, Client $client){
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required',
            'email' => 'required|email|unique:users,email',
            'cpf_cnpj' => 'required|unique:clients,cpf_cnpj'
        ]);
        
        $client->id_user = auth()->user()->id;
        $client->name = $request->name;
        $client->email = $request->email;
        $client->birth = $request->birth;
        $client->sex = $request->sex;
        $client->type = $request->type;
        $client->cpf_cnpj = $request->cpf_cnpj;
        $client->contact = $request->contact;
        $client->n_officials = $request->n_officials;
        $client->date = date('Y-m-d');

        $clientInsert = $client->insert($client);

        return $clientInsert;
    }
}
