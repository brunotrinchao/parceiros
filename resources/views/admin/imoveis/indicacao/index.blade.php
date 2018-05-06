@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('css')
<link rel="stylesheet" href="{{url('js/plugins/toggle/bootstrap-toggle-master/css/bootstrap-toggle.min.css')}}">
<link href="{{url('js/plugins/icheck/skins/all.css')}}" rel="stylesheet">
@stop
@section('js')
{{-- <script src="{{url('js/paginas/financiamento_indicacao.js')}}"></script> --}}
<script src="{{url('js/plugins/toggle/bootstrap-toggle-master/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{url('js/plugins/icheck/icheck.min.js')}}"></script>

@stop
@stop @section('content_header')
<h1>Indicação</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>
    Imóveis
  </li>
  <li>
    Indicação
  </li>
  <li>
    {{ucfirst($array['type_name'])}}
  </li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <a href="#" data-toggle="modal" data-target="#novoClienteModal" class="btn btn-primary pull-right novo_cliente" data-toggle="modal" data-target="#financiamentoModal">
      <i class="fa fa-plus"></i> Novo cliente</a>
  </div>
</div>

<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">Lista</h3>
  </div>
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
          <tr>
              <th data-priority="1">Nome</th>
              <th class="hidden-sm">E-mail</th>
              <th>Telefone</th>
              <th class="hidden-xs hidden-sm hidden-md">CPF</th>
              <th class="hidden-xs hidden-sm hidden-md">Cadastrado em</th>
            </tr>
      </thead>
      <tbody>
        @forelse($clients as $client)

        <tr>
          <td>
          <a href="{{ url('admin/imoveis/indicacao/'. $array['type_slug'] .'/'. $array['trade_slug'] .'/' . $client->id) }}" data-id="{{ $client->id }}" class="btn-link visualizarCompra">{{ $client->name }}</a>
          </td>
          <td class="hidden-sm">{{ $client->email }}</td>
          <td>{{ $client->phone }}</td>
          <td class="hidden-xs hidden-sm hidden-md">{{ $client->cpf_cnpj }}</td>
          <td class="hidden-xs hidden-sm hidden-md">{{ date('d/m/Y', strtotime($client->date)) }}</td>
        </tr>
        @empty @endforelse
      </tbody>
    </table>
    {{-- {!! $clients->links() !!} --}}
  </div>
  <!-- /.box-body -->
</div>

@stop

