@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script src="{{url('js/paginas/oi_indicacao_atendimento.js')}}"></script>
@stop
@stop @section('content_header')
<h1>Indicação</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>
    Consultoria de crédito
  </li>
  <li>
    Indicação
  </li>
  <li>
    {{$array['type_name']}}
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
          <th class="hidden-sm">Telefone</th>
          <th class="hidden-sm">CPF</th>
          <th>Cadastrado em</th>
        </tr>
      </thead>
      <tbody>
        @forelse($clients as $client)

        <tr>
          <td>
          <a href="{{ url('admin/consultoria-de-credito/indicacao/'. $array['type_slug'] .'/' . $client->id) }}" data-id="{{ $client->id }}" class="btn-link visualizarCompra">{{ $client->name }}</a>
          </td>
          <td class="hidden-sm">{{ $client->email }}</td>
          <td class="hidden-sm">{{ $client->phone }}</td>
          <td class="hidden-sm">{{ $client->cpf_cnpj }}</td>
          <td clss="hidden-sm">{{ date('d/m/Y', strtotime($client->date)) }}</td>
        </tr>
        @empty @endforelse
      </tbody>
    </table>
    {{-- {!! $clients->links() !!} --}}
  </div>
  <!-- /.box-body -->
</div>

<!-- MODAL | Comprar -->
<div id="solicitarModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Nova indicação</h4>
        </div>
        <form action="{{ url('admin/consultoria-de-credito/indicacao/novo') }}" method="post" name="novo_atendimento">
          {!! csrf_field() !!}
          <div class="modal-body">
              <div class="row">
              <div class="col-md-12">
                <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                  Informações do cliente
                </h4>
              </div>
                <div class="col-md-6 v_cpf_cnpj">
                  <label style="display:block">CPF</label>
                  <div class="input-group">
                    <input type="text" name="cpf_cnpj" class="form-control cpf" placeholder="CPF">
                    <span class="input-group-btn">
                      <a href="#" class="btn btn-default consulta_cpf"><i class="fa fa-search"></i></a>
                    </span>
                  </div>
                </div>
                <div class="load_cliente"></div>
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
  <!-- /.modal -->

@stop

