@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('css')
<link rel="stylesheet" href="{{url('js/plugins/toggle/bootstrap-toggle-master/css/bootstrap-toggle.min.css')}}">
<link href="{{url('js/plugins/icheck/skins/all.css')}}" rel="stylesheet">
@stop
@section('js')
<script src="{{url('js/paginas/imovel_indicacao_comprar.js')}}"></script>
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
    {{$array['type_name']}} / {{$array['trade_name']}}
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
        <a href="#" class="btn btn-primary pull-right btn_novo_negocio" data-toggle="modal" data-target="#imoveisModal">
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
                            <th width="15%">Valor do imóvel</th>
                            <th width="15%">Tipo de imóvel</th>
                            <th width="15%">Bairro pretendido</th>
                            <th width="30%">Informações</th>
                            <th width="10%">Data</th>
                            <th width="15%">Status</th>
                            <th width=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($imoveis as $imovel)
                        <tr id="negocio-{{ $imovel['id'] }}"
                        data-amount="{{ $imovel['amount'] }}"
                        data-neighborhood="{{ $imovel['neighborhood'] }}"
                        data-type-propertie="{{ $imovel['type_propertie'] }}"
                        data-note="{{ $imovel['note'] }}"
                        data-status="{{ $imovel['status'] }}">
                            <td>{{ $imovel['amount'] }}</td>
                            <td>{{ $imovel['type_propertie'] }}</td>
                            <td>{{ $imovel['neighborhood'] }}</td>
                            <td>{{ $imovel['note'] }}</td>
                            <td>{{ $imovel['date_formatada'] }}</td>
                            <td>{{ $imovel['status_formatado'] }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="left: auto">
                                        <li>
                                        <a href="{{ $imovel['id'] }}" class="btn_edita_negocio">
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

<div id="imoveisModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Informações do negócio</h4>
            </div>
            <form action="{{ url('admin/imoveis/indicacao/' . $array['type_slug'] . '/' . $array['trade_slug'] . '/novo') }}" method="post" name="novo_imoveis">
              {!! csrf_field() !!}
            <input name="type" type="hidden" value="{{$array['type']}}">
            <input name="trade" type="hidden" value="{{$array['trade']}}">
            <input name="client_id" type="hidden" value="{{$cliente['id']}}">
            <input name="imoveis_id" type="hidden" value="">
              <div class="modal-body">
                  <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                  <label>Valor do imóvel</label>
                                  <input type="text" name="amount" class="form-control valor" placeholder="Valor do crédito">
                                </div>
                              </div>
                              <?php
                              $listaImoveis = (new \App\Helpers\Helper)->listaTipoImoveis();
                            ?>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Tipo do imóvel</label>
                                  <select name="type_propertie" style="width:100%">
                                      <option value="">.: Selecione :.</option>
                                      <?php 
                                            foreach($listaImoveis as $key => $value){
                                                echo '<optgroup label="'.$key.'">';
                                                foreach($value as $k => $v){
                                                    echo '<option value="'.$key.' - '.$v.'">'.$v.'</option>';
                                                }
                                            }
                                        ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Bairro de preferência</label>
                                  <input type="text" name="neighborhood" class="form-control" placeholder="Bairro de preferência">
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Observações</label>
                                  <textarea class="form-control" name="note" rows="3"></textarea>
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

