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
// Dashboard
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->get('admin/{produto}/dashboard', 'AdminProdutoController@index')->name('admin.{produto}');
});
// Imóveis
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin\Imoveis'], function(){
    // Indicações -> Proprietário
    $this->get('admin/imoveis/indicacao', 'Indicacao\AdminComprarController@index')->name('admin.imoveis.indicacao');
    $this->post('admin/imoveis/indicacao/novo', 'Indicacao\AdminComprarController@insertBuy')->name('admin.imoveis.indicacao.novo');
    // Negocios
    $this->get('admin/imoveis/indicacao/negocios/{id}', 'AdminPropertiesController@getPropertiesClient')->name('admin.imoveis.indicacao.negocios.{id}');
    $this->post('admin/imoveis/indicacao/negocios/comprar', 'AdminPropertiesController@create')->name('admin.imoveis.indicacao.negocios.comprar');
    $this->post('admin/imoveis/indicacao/negocios/comprar/editar', 'AdminPropertiesController@update')->name('admin.imoveis.indicacao.negocios.comprar.editar');
});
// Oi
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin\Oi'], function(){
    $this->get('admin/oi/indicacao/solicitar-atendimento', 'AdminAtendimentoController@index')->name('admin.oi.indicacao.atendimento');
    $this->post('admin/oi/indicacao/solicitar-atendimento', 'AdminAtendimentoController@create')->name('admin.oi.indicacao.atendimento');
    $this->get('admin/oi/planos/{plano}', 'AdminPlanosController@planos')->name('admin.oi.planos.{plano}');
});
// Financiamento
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin\Financiamento'], function(){
    $this->get('admin/financiamento/indicacao/{type}', 'AdminFinanciamentoController@index')->name('admin.oi.indicacao.atendimento');
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
    // Ajuda
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
    // Categoria
    $this->middleware('check-permission:superadmin')
            ->get('admin/administracao/ajuda/categoria', 'AdminCategoryController@list');
    $this->middleware('check-permission:superadmin')
            ->post('admin/administracao/ajuda/categoria', 'AdminCategoryController@add');
    // Parceiros
    $this->middleware('auth', 'check-permission:superadmin')
        ->get('admin/administracao/parceiros', 'AdminPartnerController@index')->name('admin.administracao.parceiros');
    $this->middleware('auth', 'check-permission:superadmin')
        ->get('admin/administracao/parceiros/{id}', 'AdminPartnerController@getPartner')->name('admin.administracao.parceiros');
    $this->middleware('auth', 'check-permission:superadmin')
        ->post('admin/administracao/parceiros/editar', 'AdminPartnerController@edit')->name('admin.administracao.parceiros.editar');
    $this->middleware('auth', 'check-permission:superadmin')
        ->post('admin/administracao/parceiros/novo', 'AdminPartnerController@create')->name('admin.administracao.parceiros.novo');
    // Panos
    $this->middleware('check-permission:superadmin')
         ->get('admin/administracao/planos', 'AdminPlanosController@index')->name('admin.administracao.planos');
    $this->middleware('check-permission:superadmin')
        ->any('admin/administracao/planos/novo', 'AdminPlanosController@create')->name('admin.administracao.planos.novo');
    $this->middleware('check-permission:superadmin')
        ->get('admin/administracao/planos/editar/{id}', 'AdminPlanosController@edit')->name('admin.administracao.planos.editar.{id}');
    $this->middleware('check-permission:superadmin')
        ->post('admin/administracao/planos/update', 'AdminPlanosController@update')->name('admin.administracao.ajuda.update');
    // Categoria Planos
    $this->middleware('check-permission:superadmin')
    ->get('admin/administracao/planos/categorias', 'AdminPlanosCategoriaController@index')->name('admin.administracao.planos.categorias');
    $this->middleware('check-permission:superadmin')
        ->get('admin/administracao/planos/categoria', 'AdminPlanosCategoriaController@list');
    $this->middleware('check-permission:superadmin')
        ->post('admin/administracao/planos/categoria', 'AdminPlanosCategoriaController@add');

});

// Ajuda
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->get('admin/{produto}/ajuda', 'AdminHelpController@indexUser')->name('admin.{produto}.ajuda');
    $this->get('admin/{produto}/ajuda/{categoria}', 'AdminHelpController@indexUser')->name('admin.{produto}.ajuda.{categoria}');
});

// Relatório
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->get('admin/{produto}/relatorios', 'AdminReportController@index')->name('admin.{produto}.relatorio');
    $this->any('admin/{produto}/relatorios/resultado', 'AdminReportController@search')->name('admin.{produto}.relatorio.resultado');
});

// Cliente
$this->group(['middleware' => ['auth'], 'namespace' => 'Admin'], function(){
    $this->get('/admin/clientes', 'AdminClienteController@get');
});


$this->get('/', 'Site\SiteController@index')->name('home');
$this->get('/login', 'Site\SiteController@index')->name('login');

Auth::routes();

