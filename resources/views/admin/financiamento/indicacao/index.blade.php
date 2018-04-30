@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('css')
<link rel="stylesheet" href="{{url('js/plugins/toggle/bootstrap-toggle-master/css/bootstrap-toggle.min.css')}}">
<link href="{{url('js/plugins/icheck/skins/all.css')}}" rel="stylesheet">
@stop
@section('js')
<script src="{{url('js/paginas/financiamento_indicacao.js')}}"></script>
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
    Indicação
  </li>
  <li>
    {{ucfirst($type)}}
  </li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <a href="#" class="btn btn-primary pull-right btn_novo" data-toggle="modal" data-target="#financiamentoModal">
      <i class="fa fa-plus"></i> Novo</a>

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
              <th style="width: 40px"></th>
            </tr>
      </thead>
      <tbody>
        {{-- @forelse($clients as $client)

        <tr>
          <td>
          <a href="{{ url('admin/imoveis/indicacao/negocios/' . $client->id) }}" data-id="{{ $client->id }}" class="btn-link visualizarCompra">{{ $client->name }}</a>
          </td>
          <td class="hidden-sm">{{ $client->email }}</td>
          <td class="hidden-sm">{{ $client->phone }}</td>
          <td class="hidden-sm">{{ $client->cpf_cnpj }}</td>
          <td clss="hidden-sm">{{ date('d/m/Y', strtotime($client->date)) }}</td>
          <td width="50px">
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right" style="left: auto">
                <li>
                  <a href="{{ $client->id }}" class="btn_novo_negocio">
                    <i class="fa fa-dollar"></i> Gerar novo negócio</a>
                </li>
              </ul>
            </div>
          </td>
        </tr>
        @empty @endforelse --}}
      </tbody>
    </table>
    {{-- {!! $clients->links() !!} --}}
  </div>
  <!-- /.box-body -->
</div>


<!-- MODAL | Comprar -->
<div id="financiamentoModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Nova indicação</h4>
        </div>
        <form action="{{ url('admin/oi/indicacao/solicitar-atendimento') }}" method="post" name="novo_financiamento">
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo</label>
                        <input type="checkbox" name="client_type" data-toggle="toggle" data-on="Pessoa Física" data-off="Pessoa Jurídica" data-onstyle="success" data-offstyle="info" data-width="100%" checked>
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

