@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))
<?php 
$session = session()->get('portalparceiros');
?>
@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown products product-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <span><strong>{!! $session['produtos']['name_produto'] !!}</strong></span> <i class="fa fa-fw fa-angle-down"></i> 
                            </a>
                            
                            <ul class="dropdown-menu">
                                <?php foreach($session['lista_produto'][0] as $key => $produto){ 
                                    if($key != $session['produtos']['id_produto']){
                                ?>
                                <li class="user-body">
                                    <a href="{{ url('admin/'.$produto['slug'].'/dashboard') }}" style="color:#777;">{{ $produto['nome'] }}</a>
                                </li>
                            <?php 
                                    }
                                } 
                            ?>
                            </ul>
                        </li>
                        <?php
                                            $imagem = (isset(auth()->user()->partners->image))? auth()->user()->partners->image: 'default.jpg';
                                        ?>
                        <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <img src="{{asset('storage/parceiros/' . $imagem)}}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{auth()->user()->partners->name}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        
                                    <img src="{{asset('storage/parceiros/' . $imagem)}}" class="img-circle" alt="User Image">
                                    <p>
                                        {{auth()->user()->partners->name}}
                                    <small>Cadastrado em {{date('m/Y', strtotime(auth()->user()->partners->date))}}</small>
                                    </p>
                                    </li>
                                    <?php if(auth()->user()->level != 'U'){ ?>
                                        <!-- Menu Body -->
                                        <li class="user-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                            <a href="{{ url('admin/parceiros/editar/' . auth()->user()->partners->id) }}" class="btn btn-link"><i class="fa fa-pencil"></i> Editar</a>
                                            </div>
                                            <div class="col-xs-12">
                                            <a href="{{ url('admin/usuarios') }}" class="btn btn-link"><i class="fa fa-users"></i> Usuários</a>
                                            </div>
                                        </div>
                                        <!-- /.row -->
                                        </li>
                                    <?php } ?>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                    <div class="pull-right">
                                        @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                            <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" style="color:#333;">
                                                <i class="fa fa-fw fa-power-off"></i> Sair
                                            </a>
                                        @else
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:#333;">
                                                <i class="fa fa-fw fa-power-off"></i> Sair
                                            </a>
                                            <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                                @if(config('adminlte.logout_method'))
                                                    {{ method_field(config('adminlte.logout_method')) }}
                                                @endif
                                                {{ csrf_field() }}
                                            </form>
                                        @endif
                                        
                                    </div>
                                    </li>
                                </ul>
                           </li>
                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <?php $imagemUser = (auth()->user()->image)? 'thumb-'.auth()->user()->image: 'default.jpg';?>
                        <img src="{{asset('storage/parceiros/' . $imagemUser)}}" class="img-circle" alt="User Image">
                    </div>
                <div class="pull-left info">
                <p>{{auth()->user()->name}}</p>
                    <a href="#" data-toggle="modal" data-target="#perfilModal"><i class="fa fa-pencil-square-o"></i> Editar perfil</a>
                </div>
                </div>
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->
    <div id="novoClienteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Novo cliente</h4>
            </div>
            <form action="{{ url('admin/cliente/novo') }}" method="post" name="novo_cliente">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="row">
                    <div class="col-md-6 v_cpf_cnpj">
                        <label style="display:block">CPF</label>
                        <div class="input-group">
                        <input type="text" name="cpf_cnpj" class="form-control cpf" placeholder="CPF">
                        <span class="input-group-btn">
                            <a href="#" class="btn btn-default consulta_cpf"><i class="fa fa-search"></i></a>
                        </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Tipo</label>
                        <div class="input-group">
                            <input type="radio" name="client_type" value="F" checked> Pessoa Física
                            <input type="radio" name="client_type" value="J"> Pessoa Jurídica
                        </div>
                    </div>
                    <div class="load_cliente" style="clear:both"></div>
                    </div>
                </div>
                <div class="modal-footer">
                
                </div>
            </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="informacoesModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Informações</h4>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 content-note"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Edita merfil --}}
    <div id="perfilModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Editar perfil</h4>
                </div>
                <form action="{{url('admin/usuario/perfil')}}" method="POST" name="edita_perfil">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 hidden-sm hidden-xs">
                                <img src="{{asset('storage/parceiros/' . $imagemUser)}}" width="100%" class="img-circle" alt="User Image">
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                            <label>Foto <small>(máximo 2mb)</small></label>
                            <div class="input-group input-file" name="file">
                                <input type="text" class="form-control" placeholder='Selecionar arquivo...' />
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-choose" type="button"><i class="fa fa-upload"></i></button>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Nome</label>
                            <input type="text" name="name" class="form-control" placeholder="Nome" value="{{auth()->user()->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>E-mail</label>
                              <input type="text" name="email" class="form-control" placeholder="Nome" value="{{auth()->user()->email}}" readonly disabled>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
                </div>
                <!-- /.modal-content -->
            </div>
        <!-- /.modal-dialog -->
        </div>
@stop

@section('adminlte_js')
<script src="{{url('js/plugins/jqueryform/jquery.form.min.js')}}"></script>
<script>
        $(document).ready(function(){
          var bar = $('.bar');
          var percent = $('.percent');
          var progress = $('.progress');
          var status = $('#status');
            
          $('form[name=edita_perfil]').ajaxForm({
              beforeSend: function() {
                  status.empty();
                  progress.show();
                  var percentVal = '0%';
                  bar.width(percentVal)
                  percent.html(percentVal);
              },
              uploadProgress: function(event, position, total, percentComplete) {
                  var percentVal = percentComplete + '%';
                  bar.width(percentVal)
                  percent.html(percentVal);
              },
              success: function(responseText, statusText, xhr, $form) {
                  var percentVal = '100%';
                  bar.width(percentVal)
                  percent.html(percentVal);
                  if(responseText.success){
                    $.gNotify.success('<strong>Sucesso</strong> ', responseText.message);
                    setTimeout(function () { 
                    location.reload();
                    }, 600);
                  }else if(responseText.success = false){
                    $.gNotify.danger('<strong>Erro</strong> ', responseText.message);
                  }else{
                    var arr = [];
                    $.each(responseText.error, function(index, element){
                      arr.push(element);
                    });
                    
                    $.gNotify.warning(null, arr.join('<br>'));
                  }
              },
            complete: function(xhr) {
              progress.hide();
              // status.html(xhr.responseText);
            }
          }); 
      
        });
      </script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
