@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script src="{{url('js/paginas/imovel_indicacao_comprar.js')}}"></script>
@stop
@stop @section('content_header')
<h1>Indicação</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>
    <a href="#">Indicação</a>
  </li>
<li class="active">{{ $arr['titulo']}}</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <a href="#" class="btn btn-primary pull-right btn_novo" data-toggle="modal" data-target="#comprarModal">
      <i class="fa fa-plus"></i> Novo</a>

  </div>
</div>


<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">{{ $arr['titulo']}}</h3>
  </div>
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Nome</th>
          <th class="hidden-sm">E-mail</th>
          <th class="hidden-sm">Telefone</th>
          <th class="hidden-sm">CPF</th>
          <th>Cdastrado em</th>
          <th style="width: 40px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($clients as $client)

        <tr>
          <td>
            <a href="#" data-id="{{ $client->id }}" class="btn-link visualizarCompra">{{ $client->name }}</a>
          </td>
          <td class="hidden-sm">{{ $client->email }}</td>
          <td class="hidden-sm"></td>
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
        @empty @endforelse
      </tbody>
    </table>
    {{-- {!! $clients->links() !!} --}}
  </div>
  <!-- /.box-body -->
</div>

<!-- MODAL | Comprar -->
<div id="comprarModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">{{ $arr['titulo']}}</h4>
      </div>
      <form action="./comprar" name="novo_imovel">
        {!! csrf_field() !!}
        <input type="hidden" name="birth" class="form-control daterange" placeholder="Data de nascimento">
        <input type="hidden" name="trade" value="<?php echo $_GET['trade'] ?>">
        <input type="hidden" name="type" value="<?php echo $_GET['type'] ?>">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 v_content_msg"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nome</label>
                <input type="text" name="name" class="form-control" placeholder="Nome">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="E-mail">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Sexo</label>
                <select class="form-control selected" name="sex" style="width: 100%">
                  <option value="" selected>.: Selecione :.</option>
                  <option value="M">Masculino</option>
                  <option value="F">Feminino</option>
                </select>
              </div>
            </div>
            <div class="col-md-4" class="v_cpf_cnpj">
              <div class="form-group">
                <label>CPF</label>
                <input type="text" name="cpf_cnpj" class="form-control cpf" placeholder="CPF">
              </div>
            </div>
            <div class="col-md-4">
              <label>Data de nascimento</label>
              <div class="pull-right datetimepicker" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <input type="hidden" name="birth">
                <i class="fa fa-calendar"></i>
                <span><input type="text" name="birth" style="border: none;" value="{{date('d/m/Y')}}" disabled></span>
                <b class="caret pull-right"></b>
              </div>
            </div>
            <!-- contato -->
            <div class="col-md-12">
              <div class="form-group">
                <label>Contato</label>
                <input type="text" name="contact" class="form-control" placeholder="Contato">
              </div>
            </div>
            <div class="col-md-12">
              <a href="#" data-col="4" class="btn btn-link add_phone">
                <i class="fa fa-plus"></i> Adicionar telefone</a>
            </div>
            <!-- Container pphones -->
            <div class="v_content_phones" id="container_novo">
              <div class="col-md-4 clone_add_phone">
                <div class="input-group">
                  <input type="text" name="phone[]" class="form-control telefone" value="" placeholder="Telefone">
                  <span class="input-group-btn">
                    <a class="btn btn-danger remove_phone" href="#">
                      <i class="fa fa-minus"></i>
                    </a>
                  </span>
                </div>
                <br>
              </div>
            </div>
            <div class="col-md-12">
              <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                Informações do negocio
              </h4>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Valor do imóvel</label>
                <input type="text" name="amount" class="form-control valor" placeholder="Valor do crédito">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tipo do imóvel</label>
                <input type="text" name="type_propertie" class="form-control" placeholder="Tipo do imóvel">
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
<!-- /.modal -->


<!-- MODAL | Comprar (Editar - Vizualizar) -->
<div id="comprarEditarModal" class="modal fade" tabindex="-2" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-user"></i> <span></span></h4>
      </div>
      <div class="modal-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_1" data-toggle="tab" aria-expanded="true">Dados pessoais</a>
            </li>
            <li class="">
              <a href="#tab_2" id="tab_negocios" data-toggle="tab" aria-expanded="false">Negócios</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <form action="{{ url('/cliente/editar') }}" name="editClient">
                  {!! csrf_field() !!}
                  <input name="id" type="hidden">
                <div class="row">
                  <div class="col-md-12 v_content_msg"></div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nome</label>
                      <input type="text" name="name" class="form-control" placeholder="Nome">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>E-mail</label>
                      <input type="email" name="email" class="form-control" placeholder="E-mail">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Sexo</label>
                      <select class="form-control selected" name="sex" style="width: 100%">
                        <option value="" selected>.: Selecione :.</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4" class="v_cpf_cnpj">
                    <div class="form-group">
                      <label>CPF</label>
                      <input type="text" name="cpf_cnpj" class="form-control cpf" placeholder="CPF">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Data de nascimento</label>
                      <div class="datetimepicker" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                          <i class="fa fa-calendar"></i>&nbsp;
                          <span><input type="text" name="birth" style="border: none;" value="{{date('d/m/Y')}}" disabled></span>
                          <b class="caret pull-right"></b>
                      </div>
                    </div>
                  </div>
                  <!-- contato -->
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Contato</label>
                      <input type="text" name="contact" class="form-control" placeholder="Contato">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label>Telefones</label>
                    <a href="#" data-col="4" class="btn btn-link add_phone" style="display:none">
                      <i class="fa fa-plus"></i> Adicionar telefone</a>
                  </div>
                  <div class="v_content_phones" id="container_editar">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12" style="text-align:right">
                    <a href="" class="btn text-red btn_edita_dados"><i class="fa fa-pencil"></i> Editar</a>
                    <a href="" class="btn btn-default btn_cancela_dados" style="display:none"><i class="fa fa-ban"></i> Cancelar</a>
                    <button type="submit" class="btn btn-success btn_salva_dados" style="display:none">Salvar</button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane container_negocios box-group" id="tab_2">
            </div>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- MODAL | Novo negócio -->
<div id="novoNegocioModal" class="modal fade" tabindex="-3" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Comprar</h4>
        </div>
        <form action="" name="novo_negocio">
          <input type="hidden" name="id">
          <input type="hidden" name="trade" value="C">
          <input type="hidden" name="type" value="T">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 v_content_msg"></div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nome</label>
                  <input type="text" name="name" class="form-control" placeholder="Nome" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>E-mail</label>
                  <input type="email" name="email" class="form-control" placeholder="E-mail" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Sexo</label>
                  <select class="form-control selected" name="sex" style="width: 100%">
                    <option value="" selected>.: Selecione :.</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4" class="v_cpf_cnpj">
                <div class="form-group">
                  <label>CPF</label>
                  <input type="text" name="cpf_cnpj" class="form-control cpf" placeholder="CPF" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label>Data de nascimento</label>
                    <input type="text" name="birth" class="form-control cpf" placeholder="Aniversário" readonly>
                  </div>
              </div>
              <div class="col-md-12">
                <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                  Informações do negocio
                </h4>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Valor do imóvel</label>
                  <input type="text" name="amount" class="form-control valor" placeholder="Valor do crédito">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tipo do imóvel</label>
                  <input type="text" name="type_propertie" class="form-control" placeholder="Tipo do imóvel">
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

