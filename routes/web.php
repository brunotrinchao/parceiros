<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Parceiros
$this->group(['middleware' => ['auth']], function(){
    $this->middleware('auth', 'check-permission:superadmin')
        ->get('admin/parceiros', 'Admin\AdminPartnerController@index')->name('admin.parceiros');
    $this->middleware('auth', 'check-permission:superadmin')
        ->get('admin/parceiros/{id}', 'Admin\AdminPartnerController@getPartner')->name('admin.parceiros');
    $this->middleware('auth', 'check-permission:superadmin')
        ->post('admin/parceiros/editar', 'Admin\AdminPartnerController@edit')->name('admin.parceiros.editar');
    $this->middleware('auth', 'check-permission:superadmin')
        ->post('admin/parceiros/novo', 'Admin\AdminPartnerController@create')->name('admin.parceiros.novo');
});
// Usuários
$this->group([], function(){
    $this->middleware('auth', 'check-permission:superadmin|admin|gerente')
        ->get('admin/usuarios', 'Admin\AdminUserController@index')->name('admin.usuarios');
    $this->middleware('auth', 'check-permission:superadmin|admin|gerente')
        ->get('admin/usuarios/{id}', 'Admin\AdminUserController@getUser')->name('admin.usuarios');
    $this->middleware('auth', 'check-permission:superadmin|admin|gerente')
        ->post('admin/usuarios/editar', 'Admin\AdminUserController@edit')->name('admin.usuarios.editar');
    $this->middleware('auth', 'check-permission:superadmin|admin|gerente')
        ->post('admin/usuarios/novo', 'Admin\AdminUserController@create')->name('admin.usuarios.novo');
    $this->post('usuario/login', 'Auth\CustonLoginController@loginUser')->name('usuario.login');
});
// Clientes
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->get('cliente/{id}', 'ClientController@get')->name('cliente.get');
    $this->post('cliente/editar', 'ClientController@edit')->name('cliente.editar');
});
// Imóveis
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin\Imoveis'], function(){
    session()->put('portalparceiros', [
        'url_produto' => 'imoveis',
        'name_produto' => 'Imóveis',
        'id_produto' => 1
    ]);
    // dashboard
    $this->get('admin/imoveis', 'AdminImoveisController@index')->name('admin.imoveis.home');
    // Indicações -> Proprietário
    $this->get('admin/imoveis/indicacao/comprar', 'Indicacao\AdminComprarController@index')->name('admin.imoveis.indicacao.comprar');
    // $this->get('admin/imoveis/indicacao/comprar/{id}', 'Indicacao\AdminComprarController@getClient')->name('admin.imoveis.indicacao.comprar');
    $this->post('admin/imoveis/indicacao/comprar', 'Indicacao\AdminComprarController@insertBuy')->name('admin.imoveis.indicacao.comprar');
    // $this->post('admin/imoveis/indicacao/filtro', 'Indicacao\AdminComprarController@search')->name('admin.imoveis.indicacao.comprar.filtro');
    // middleware('check-permission:usuario|superadmin|admin|gerente')->
    // Negocios
    $this->get('admin/imoveis/indicacao/negocios/comprar/{id}', 'AdminPropertiesController@getPropertiesClient')->name('admin.imoveis.indicacao.negocios.comprar');
    $this->post('admin/imoveis/indicacao/negocios/comprar', 'AdminPropertiesController@create')->name('admin.imoveis.indicacao.negocios.comprar');
    $this->post('admin/imoveis/indicacao/negocios/comprar/editar', 'AdminPropertiesController@update')->name('admin.imoveis.indicacao.negocios.comprar.editar');
});

// Arquivos | Ajuda
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    // Arquivos
    $this->middleware('check-permission:superadmin|admin|gerente')
         ->get('admin/{produto}/arquivos', 'AdminArchiveController@list')->name('admin.{produto}.arquivos');
    $this->middleware('check-permission:superadmin')
         ->get('admin/arquivos/novo', 'AdminArchiveController@add')->name('admin.arquivos.novo');
    $this->get('admin/{produto}/arquivos/{id}', 'AdminArchiveController@item')->name('admin.{produto}.arquivos.{id}');
    $this->get('admin/{produto}/arquivos/download/{id}', 'AdminArchiveController@download')->name('admin.{produto}.arquivos.download.{id}');
    $this->middleware('check-permission:superadmin')
         ->post('admin/arquivos/upload', 'AdminArchiveController@upload')->name('admin.arquivos.upload');

    //  Ajuda
});

// Administracao
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->middleware('check-permission:superadmin')
         ->get('admin/administracao/ajuda', 'AdminHelpController@index')->name('admin.administracao.ajuda');
    $this->middleware('check-permission:superadmin')
         ->any('admin/administracao/ajuda/novo', 'AdminHelpController@create')->name('admin.administracao.ajuda.novo');
    $this->middleware('check-permission:superadmin')
         ->get('admin/administracao/ajuda/editar/{id}', 'AdminHelpController@edit')->name('admin.administracao.ajuda.editar.{id}');
    $this->middleware('check-permission:superadmin')
         ->get('admin/administracao/ajuda/lista-por-categoria/{category_id}/{product_id}', 'AdminHelpController@get')->name('admin.administracao.ajuda.lista-por-categoria.{id}');
    $this->middleware('check-permission:superadmin')
         ->post('admin/administracao/ajuda/update', 'AdminHelpController@update')->name('admin.administracao.ajuda.update');
    $this->middleware('check-permission:superadmin')
        ->any('admin/administracao/ajuda/ordenar', 'AdminHelpController@order')->name('admin.administracao.ajuda.ordenar');
    // Parceiros
    $this->middleware('auth', 'check-permission:superadmin')
        ->get('admin/administracao/parceiros', 'AdminPartnerController@index')->name('admin.administracao.parceiros');
    $this->middleware('auth', 'check-permission:superadmin')
        ->get('admin/administracao/parceiros/{id}', 'AdminPartnerController@getPartner')->name('admin.administracao.parceiros');
    $this->middleware('auth', 'check-permission:superadmin')
        ->post('admin/administracao/parceiros/editar', 'AdminPartnerController@edit')->name('admin.administracao.parceiros.editar');
    $this->middleware('auth', 'check-permission:superadmin')
        ->post('admin/administracao/parceiros/novo', 'AdminPartnerController@create')->name('admin.administracao.parceiros.novo');
    // Categoria
    $this->middleware('check-permission:superadmin')
         ->get('admin/administracao/ajuda/categoria', 'AdminCategoryController@list');
    $this->middleware('check-permission:superadmin')
         ->post('admin/administracao/ajuda/categoria', 'AdminCategoryController@add');
});

// Ajuda
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->get('admin/{produto}/ajuda', 'AdminHelpController@indexUser')->name('admin.{produto}.ajuda');
    $this->get('admin/{produto}/ajuda/{categoria}', 'AdminHelpController@indexUser')->name('admin.{produto}.ajuda.{categoria}');
});

// Relatório
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->any('admin/{produto}/relatorios', 'AdminReportController@index')->name('admin.{produto}.relatorio');
});


$this->get('/', 'Site\SiteController@index')->name('home');
$this->get('/login', 'Site\SiteController@index')->name('login');

Auth::routes();

