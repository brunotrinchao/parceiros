@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script>
 
</script>
@stop
@stop @section('content_header')
<h1>Ajuda</h1>
<?php
    $session = session()->get('portalparceiros');
    $name_produto = $session['produtos']['name_produto'];    
    $url_produto = $session['produtos']['url_produto'];    
    $id_produto = $session['produtos']['id_produto'];    
?>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>{{ $name_produto }}</li>
  <li class="active">Sobre</li>
</ol>
@stop @section('content')
<div class="row">
    <div class="col-md-12">
        <p>Em breve.</p>
    </div>
</div>


@stop

