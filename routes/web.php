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
// Imóveis
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin\Imoveis'], function(){
    // dashboard
    $this->get('admin/imoveis', 'AdminImoveisController@index')->name('admin.imoveis.home');
    // Indicações -> Proprietário
    $this->get('admin/imoveis/indicacao/proprietario/comprar', 'Indicacao\Proprietario\AdminComprarController@index')->name('admin.imoveis.indicacao.proprietario.comprar');
    $this->post('admin/imoveis/indicacao/proprietario/comprar', 'Indicacao\Proprietario\AdminComprarController@insertBuy')->name('admin.imoveis.indicacao.proprietario.comprar');
});

$this->get('/', 'Site\SiteController@index')->name('home');

Auth::routes();

