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
$this->group(['middleware' => ['auth']], function(){
    $this->post('cliente/editar', 'Auth\EditController@edit')->name('cliente.editar');
});
// Imóveis
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin\Imoveis'], function(){
    // dashboard
    $this->get('admin/imoveis', 'AdminImoveisController@index')->name('admin.imoveis.home');
    // Indicações -> Proprietário
    $this->get('admin/imoveis/indicacao/comprar', 'Indicacao\AdminComprarController@index')->name('admin.imoveis.indicacao.comprar');
    $this->get('admin/imoveis/indicacao/comprar/{id}', 'Indicacao\AdminComprarController@getClient')->name('admin.imoveis.indicacao.comprar');
    $this->post('admin/imoveis/indicacao/comprar', 'Indicacao\AdminComprarController@insertBuy')->name('admin.imoveis.indicacao.comprar');
    $this->post('admin/imoveis/indicacao/filtro', 'Indicacao\AdminComprarController@search')->name('admin.imoveis.indicacao.comprar.filtro');
    // Negocios
    $this->get('admin/imoveis/indicacao/negocios/comprar/{id}', 'AdminPropertiesController@getPropertiesClient')->name('admin.imoveis.indicacao.negocios.comprar');
});

$this->get('/', 'Site\SiteController@index')->name('home');

Auth::routes();

