@extends('adminlte::page') 
@section('title_prefix', 'Indicação | Comprar') 
@section('css')
<style>
.loader{
  position:fixed;
  left:0;
  top:0;
  width: 100%;
  height: 100%;
  z-index: 999999;
  background-color: rgba(0,0,0,.5);
  display:none;
}
.loader img{
  position: absolute; 
  top:50%; 
  margin-top:-20px; 
  left:50%; 
  margin-left:-20px;
  background: #fff; 
  padding: 4px; 
  border-radius: 50%; 
  opacity: .9;
}
</style>
@stop
@section('js')
  <script>
    $(function() {
      $('.select2').select2()
      // novo
      $('form[name=novo_imovel] select[name=type]').change(function(){
        var value = $(this).val();
        console.log(value);
      });
      // Add phone
      $('.add_phone').click(function(e){
        e.preventDefault();
        var numPhone = $('.clone_add_phone').length;
        var html = '<div class="col-md-6 clone_add_phone">';
        html += '<div class="input-group">';
        html += '<input type="text" name="phone[]" class="form-control" placeholder="Telefone" required>';
        html += '<span class="input-group-btn">';
        html += '<a class="btn btn-danger remove_phone" href="#"><i class="fa fa-minus"></i></a>';
        html += '</span>';
        html += '</div>';
        html += '<br>';
        html += '</div>';
        $('.v_content_phones').append(html);
      });

      // Remove phone
      $(document).on('click', '.remove_phone', function(e){
        e.preventDefault();
        $(this).parent().parent().parent().remove();
      });

      // Novo
      $(document).on('submit','form[name=novo_imovel]', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        $.ajax({
          headers: {
              'X-CSRF-Token': $('input[name="_token"]').val()
          },
          type: 'POST',
          url: url,
          data: $(this).serialize(),
          dataType: 'json',
          beforeSend: function(){
            $('.v_content_msg').empty();
            $('body').append('<div class="loader"><img src="{{ url("imagens/ajax-loader.gif")}}"></div>');
            $('.loader').fadeIn('fast')          
          },
          success: function(data){
            $('.loader').fadeOut('fast', function(){
            console.log(data);
              $(this).remove();
            });
          },
          error: function(data){
             console.log(data.responseJSON.errors);
             $('.loader').fadeOut('fast', function(){
               var html = '<div class="alert alert-danger alert-dismissible">';
                  html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                  $.each(data.responseJSON.errors, function(i, e){
                    html += e[0] + '<br>';
                  });
                  html += '</div>';
                $('.v_content_msg').append(html);
                $(this).remove();
             });
          }
      });
      });
    });
  </script>
@stop

@section('content_header')
<h1>Indicação</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>
    <a href="#">Indicação</a>
  </li>
  <li class="active">Comprar</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">
      <i class="fa fa-filter"></i> Filtrar</h3>
  </div>
  <div class="box-body">
    <div class="row">
      <form action="">
        <input type="hidden" name="sessao" value="indicacao">
        <input type="hidden" name="tipo" value="proprietario">
        <input type="hidden" name="acao" value="comprar">
        <div class="col-md-2">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Nome">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <input class="form-control" type="text" placeholder="Telefone"> </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <input type="text" class="form-control pull-right" placeholder="data">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#comprarModal"><i class="fa fa-plus"></i> Novo</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">Comprar</h3>
    </div>
  <div class="box-body">
      <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Status</th>
          <th>Valor</th>
          <th style="width: 40px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($clients as $client)
        <tr>
          <td><a href="#" class="btn-link">{{ $client->client_name }}</a></td>
          <td>{{ $client->client_email }}</td>
          <td>{{ $client->contact_phone }}</td>
          <td>Cliente sem perfil</td>
        <td>valor</td>
          <td width="50px">
              <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#"><i class="fa fa-dollar"></i> Gerar novo negócio</a></li>
                  </ul>
                </div>
          </td>
        </tr>
        @empty
        @endforelse
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>

<!-- MODAL | Comprar -->
<div id="comprarModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Comprar</h4>
        </div>
        <form action="./comprar" name="novo_imovel">
          {!! csrf_field() !!}
        <div class="modal-body">
                <div class="row">
                  <div class="col-md-12 v_content_msg"></div>
                  <div class="col-md-12">
                      <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                     Dados Pessoais
                      </h4>
                  </div>
                  <div class="col-md-6">
                    <label>Tipo</label>
                    <select class="form-control select2" name="type" style="width: 100%">
                      <option value="J" selected>Pessoa Juridica</option>
                      <option value="F">Pessoa Física</option>
                    </select>
                  </div>
                  <div class="col-md-6 clearfix"></div>
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
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Nº Funcionários</label>
                        <input type="text" name="n_officies" class="form-control" placeholder="Nº Funcionários">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                        <label>Sexo</label>
                        <select class="form-control select2" name="sex" style="width: 100%">
                            <option value="" selected>.: Select :.</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6" class="v_cpf_cnpj">
                      <div class="form-group">
                        <label>CNPJ</label>
                        <input type="text" name="cpf_cnpj" class="form-control" placeholder="CNPJ">
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
                    <a href="#" class="btn btn-link add_phone"><i class="fa fa-plus"></i> Adicionar telefone</a>
                  </div>
                  <!-- Container pphones -->
                  <div class="v_content_phones"></div>
                  <div class="col-md-12">
                      <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                    Informações do negocio
                      </h4>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Valor do crédito</label>
                        <input type="text" name="amount" class="form-control" placeholder="Valor do crédito">
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Valor do lance</label>
                        <input type="text" name="input" class="form-control" placeholder="Valor do lance">
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Parcela pretendida</label>
                        <input type="text" name="plots" class="form-control" placeholder="Parcela pretendida">
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                        <label>Prazo de compra</label>
                        <input type="text" name="deadline" class="form-control" placeholder="Prazo de compra">
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                        <label>Observações</label>
                        <textarea class="form-control" rows="3"></textarea>
                      </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
              </div>
          </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
@stop