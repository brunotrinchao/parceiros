@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('css')
<link rel="stylesheet" href="{{url('js/plugins/toggle/bootstrap-toggle-master/css/bootstrap-toggle.min.css')}}">
<link href="{{url('js/plugins/icheck/skins/all.css')}}" rel="stylesheet">
@stop
@section('js')
<script src="{{url('js/paginas/oi_indicacao_atendimento.js')}}"></script>
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
    Oi
  </li>
  <li>
    Indicação
  </li>
  <li>
    Solicitar atendimento
  </li>
</ol>
@stop @section('content')

<div class="box box-solid">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                        {{$cliente['name']}}
                <small class="pull-right">Cadastrado em: {{$cliente['date_formatada']}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
            <strong>E-mail:</strong> {{$cliente['email']}}<br>
                <strong>Sexo:</strong> {{$cliente['sex_formatado']}}<br>
            </div>
            <div class="col-sm-4 invoice-col">
                <strong>CPF:</strong> {{$cliente['cpf_cnpj']}}<br>
                <strong>Data de nascimento:</strong> {{$cliente['birth_formatada']}}<br>
            </div>
            <div class="col-sm-4 invoice-col">
                    <strong>Telefone:</strong> {{$cliente['phone']}}<br>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <a href="#" class="btn btn-primary pull-right btn_novo_negocio" data-toggle="modal" data-target="#oiModal">
            <i class="fa fa-plus"></i> Novo
        </a>
    </div>
</div><br>
<div class="box box-solid">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="70%">Informações</th>
                            <th width="15%">Data</th>
                            <th width="15%">Status</th>
                            <th width=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitacoes as $solicitacao)
                        <tr id="negocio-{{ $solicitacao['id'] }}"
                        data-note="{{ $solicitacao['note'] }}"
                        data-status="{{ $solicitacao['status'] }}">
                            <td>{{ $solicitacao['note'] }}</td>
                            <td>{{ $solicitacao['date_formatada'] }}</td>
                            <td>{{ $solicitacao['status_formatado'] }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="left: auto">
                                        <li>
                                        <a href="{{ $solicitacao['id'] }}" class="btn_edita_negocio">
                                            <i class="fa fa-pencil"></i> Editar</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty @endforelse
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
    </div>
</div>

<div id="oiModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Informações do negócio</h4>
            </div>
            <form action="{{ url('admin/oi/indicacao/' . $array['type_slug'] . '/novo') }}" method="post" name="novo_oi">
              {!! csrf_field() !!}
            <input name="type" type="hidden" value="{{$array['type']}}">
            <input name="client_id" type="hidden" value="{{$cliente['id']}}">
            <input name="oi_id" type="hidden" value="">
              <div class="modal-body">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Informações</label>
                                <textarea class="form-control" name="note" rows="5"></textarea>
                            </div>
                        </div>
                      <div class="col-md-6 box-status" style="display:none">
                        <div class="form-group">
                          <label>Status</label>
                          <div class="form-group">
                          <?php
                            $listaStatus = (new \App\Helpers\Helper)->listaStatus();
                          ?>
                            <select name="status" style="width:100%" disabled readonly>
                                <option value="">.: Selecione :.</option>
                                <?php 
                                    foreach($listaStatus as $key => $value){
                                        echo ' <option value="'.$key.'">'.$value.'</option>';
                                    }
                                ?>
                            </select>
                            </div>
                        </div>
                      </div>
                    </div>
              </div>
              <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

@stop

