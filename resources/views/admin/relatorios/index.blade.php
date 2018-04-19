@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script>
</script>
@stop
@stop @section('content_header')
<?php
    $session = session()->get('portalparceiros');
    $name_produto = $session['produtos']['name_produto'];    
    $url_produto = $session['produtos']['url_produto'];    
    $id_produto = $session['produtos']['id_produto'];    
?>
<h1>Relatórios</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>{{ $name_produto }}</li>
  <li class="active">Relatórios</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <div class="row">
    <form action="{{ url('admin/'.$url_produto.'/relatorios/resultado') }}" method="post" class="form-inline">
      {!! csrf_field() !!}
      <input type="hidden" name="product_id" value="{{ $id_produto }}">
      <div class="col-md-12">
          <div class="form-group">
              <label>Período </label>
              <input type="text" name="periodo_range" class="form-control daterange" placeholder="Período" value="{{ date('d/m/Y') }}" style="width:200px">
              <input type="hidden" name="periodo" class="daterange_hidden" value="{{ date('Y-m-m|Y-m-d') }}">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
          </div>
        </div>
        </div>
    </form>
    </div>
  </div>
</div>

@stop

