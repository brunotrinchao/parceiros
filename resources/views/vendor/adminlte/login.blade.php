@extends('adminlte::master')

<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('js/plugins/animate/animate.css')}}"> 
    <link rel="stylesheet" href="{{ url('css/site/geral.min.css') }}">
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
</head>

<body>
    <div id="banner">
        <h1 class="titulo_banner">Seja bem vindo ao portal do parceiro</h1>
        <div class="container">
            <div class="topo">
                <img src="{{ url('img/logo_branca.png')}}" class="logo">
                <div class="box_login">
                    <form name="form_login">
                        {!! csrf_field() !!}
                        <div class="input_group">
                            <label>Login</label>
                            <input type="text" id="usu_var_email" name="email" require autocomplete="off">
                        </div>
                        <div class="input_group">
                            <label>Senha</label>
                            <input type="password" id="usu_var_senha" name="password" require autocomplete="off">
                            <a href="#" class="btn_recover">Esqueci a senha</a>
                        </div>
                        <div class="input_group input_group_button">
                            <button type="submit">OK</button>
                        </div>
                    </form>
                    <form name="form_recover" style="display:none">
                            {!! csrf_field() !!}
                            <div class="input_group">
                                <label>E-mail</label>
                                <input type="text" name="email" require autocomplete="off">
                                <a href="#" class="btn_login">Fazer login</a>
                            </div>
                            <div class="input_group input_group_button">
                                <button type="submit">OK</button>
                            </div>
                    </form>
                    <div class="box_usuario" style="">
                        <h3>Olá,
                            <strong>{{(isset($user))?$user->name : ''}}</strong>
                        </h3>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
                        <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                            @if(config('adminlte.logout_method'))
                                {{ method_field(config('adminlte.logout_method')) }}
                            @endif
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="menu">
        <div class="row">
            <div class="col">
                <a href="admin/imoveis/dashboard" rel="IMOVEIS" class="btn btn_imovel btn_produto">
                    <h2>Imóvel</h2>
                </a>
            </div>
            <div class="col">
                <a href="admin/oi/dashboard" rel="OI" class="btn btn_oi btn_produto">
                    <h2>Oi</h2>
                </a>
            </div>
            <div class="col">
                <a href="admin/financiamento/dashboard" rel="FINANCIAMENTO" class="btn btn_financiamento btn_produto">
                    <h2>Financiamento</h2>
                </a>
            </div>
            <div class="col">
                <a href="admin/consultoria-de-credito/dashboard" rel="CONSULTORIA" class="btn btn_consultoria btn_produto">
                    <h2>Consultoria de crédito</h2>
                </a>
            </div>
        </div>
    </div>
    <form name="goto">
        <input type="hidden" name="acao" value="goto">
        <input type="hidden" name="par_var_produto">
        <input type="hidden" name="par_var_produto">
    </form>
    <div id="footer">
        <p>Supercredito - Todos os direitos reservados 2018</p>
    </div>
    <script src="{{ asset('js/plugins/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ url('js/site/script.js') }}"></script>
</body>

</html>