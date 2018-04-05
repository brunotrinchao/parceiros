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
// Usuários
$this->group([], function(){
    $this->post('usuario/login', 'Auth\CustonLoginController@loginUser')->name('usuario.login');
});
// Clientes
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->get('cliente/{id}', 'ClientController@get')->name('cliente.get');
    $this->post('cliente/editar', 'ClientController@edit')->name('cliente.editar');
});
// Imóveis
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin\Imoveis'], function(){
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

// Arquivos
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->middleware('check-permission:superadmin|admin|gerente')
         ->get('admin/{produto}/arquivos', 'AdminArchiveController@list')->name('admin.{produto}.arquivos');
    $this->middleware('check-permission:superadmin')
         ->get('admin/arquivos/novo', 'AdminArchiveController@add')->name('admin.arquivos.novo');
    $this->get('admin/{produto}/arquivos/{id}', 'AdminArchiveController@item')->name('admin.{produto}.arquivos.{id}');
    $this->get('admin/{produto}/arquivos/download/{id}', 'AdminArchiveController@download')->name('admin.{produto}.arquivos.download.{id}');
    $this->middleware('check-permission:superadmin')
         ->post('admin/arquivos/upload', 'AdminArchiveController@upload')->name('admin.arquivos.upload');
});

$this->get('/', 'Site\SiteController@index')->name('home');
$this->get('/login', 'Site\SiteController@index')->name('login');

Auth::routes();

