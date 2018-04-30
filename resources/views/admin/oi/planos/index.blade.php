@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script src="{{url('js/paginas/oi_indicacao_atendimento.js')}}"></script>
@stop
@stop @section('content_header')
<h1>Planos</h1>

<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>
      Planos
  </li>
  <li>
      {{ $categoria->name }}
  </li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-header with-border">
  <h3 class="box-title">{{ $categoria->name }}</h3>
  </div>
</div>
<div class="row">
    @forelse($planos as $plano)
    <div class="col-md-6">
      <div class="box box-default box-solid">
        <div class="box-header with-border">
        <h3 class="box-title">{{ $plano['name'] }}</h3>
        </div>
        <div class="box-body">
            <?php echo $plano['description']; ?>
        </div>
      </div>
    </div>
    @empty @endforelse
</div>
@stop

