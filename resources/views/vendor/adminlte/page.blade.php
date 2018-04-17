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
                                <span><strong>{!! $session['name_produto'] !!}</strong></span> <i class="fa fa-fw fa-angle-down"></i> 
                            </a>

                            <ul class="dropdown-menu">
                                <?php foreach($session['lista_produto'][0] as $key => $produto){ 
                                    if($key != $session['id_produto']){
                                ?>
                                <li class="user-body">
                                    <a href="{{ url('admin/'.$produto['slug']) }}" class="">{{ $produto['nome'] }}</a>
                                </li>
                            <?php 
                                    }
                                } 
                            ?>
                            </ul>
                        </li>
                        <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <img src="{{asset('storage/parceiros/' . auth()->user()->partners->image)}}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{auth()->user()->partners->name}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                    <img src="{{asset('storage/parceiros/' . auth()->user()->partners->image)}}" class="img-circle" alt="User Image">
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
                                            <a href="{{ url('admin/parceiro/editar') }}" class="btn btn-link"><i class="fa fa-pencil"></i> Editar</a>
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
                                    <?php if(auth()->user()->level != 'U'){ ?>
                                        <div class="pull-left">
                                            <a href="#">
                                                <i class="fa fa-fw fa-sliders"></i> Configurações
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="pull-right">
                                        @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                            <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                                <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                            </a>
                                        @else
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
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
                        <img src="{{asset('storage/parceiros/' . auth()->user()->image)}}" class="img-circle" alt="User Image">
                    </div>
                <div class="pull-left info">
                <p>{{auth()->user()->name}}</p>
                    <a href="#"><i class="fa fa-pencil-square-o"></i> Editar perfil</a>
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
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
