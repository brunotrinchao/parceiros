@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
<script>
  $(document).ready(function () {
    // novo
    $('form[name=novo_imovel] select[name=type]').change(function () {
      var value = $(this).val();
      console.log(value);
    });
    // Add phone
    $('.add_phone').click(function (e) {
      e.preventDefault();
      var col = ($(this).attr('data-col').length > 0) ? $(this).attr('data-col') : 6;
      console.log(col);
      var numPhone = $('.clone_add_phone').length;
      var html = '<div class="col-md-' + col + ' clone_add_phone">';
      html +='<div class="input-group">';
      html +=
        '<input type="text" name="phone[]" class="form-control telefone" placeholder="Telefone">';
      html +='<span class="input-group-btn">';
      html +='<a class="btn btn-danger remove_phone" href="#"><i class="fa fa-minus"></i></a>';
      html +='</span>';
      html +='</div>';
      html +='<br>';
      html +='</div>';
      $('.v_content_phones').append(html);
      $('.telefone').mask("(99) 9999-9999?9", {
          'placeholder': '(  ) _____-____)'
        })
        .focusout(function (event) {
          var target, phone, element;
          target = (event.currentTarget) ? event.currentTarget : event.srcElement;
          phone = target.value.replace(/\D/g, '');
          element = $(target);
          element.unmask();
          if (phone.length > 10) {
            element.mask("(99) 99999-999?9");
          } else {
            element.mask("(99) 9999-9999?9");
          }
        });
    });

    // Remove phone
    $(document).on('click', '.remove_phone', function (e) {
      e.preventDefault();
      var container_phone = $(this).parents().eq(3);
      var total = $('#' + container_phone[0].id).find('.clone_add_phone').length;
      var disabled = $('#' + container_phone[0].id).find('input').attr('readonly');

      if(disabled != 'readonly'){
        if (total == 1) {
          $.gNotify.warning('<strong>Atenção</strong> ', 'É obrigatório um telefone de contato.');
          return
        }
        $(this).parent().parent().parent().remove();
      }
    });

    // Novo
    $('.btn_novo').click(function (e) {
      // $('#comprarModal .v_content_phones .clone_add_phone').remove();
    });
    $(document).on('submit', 'form[name=novo_imovel]', function (e) {
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
        beforeSend: function () {
          $('.v_content_msg').empty();
          $('body').append('<div class="loader"><img src="{{ url("imagens/ajax-loader.gif")}}"></div>');
          $('.loader').fadeIn('fast')
        },
        success: function (data) {
          $('.loader').fadeOut('fast', function () {
            var tipo = (data.success) ? 'success' : 'danger';
            var titulo = (data.success) ? 'Sucesso' : 'Erro';
            $.gNotify.tipo('<strong>' + titulo + '</strong> ', data.message);
            // $('.v_content_msg').append(html);
            $(this).remove();
          });
          if (data.success) {
            $('form[name=novo_imovel] input').val('');
            $('form[name=novo_imovel] textarea').val('');
            $('form[name=novo_imovel] select').val('');
            $('.v_content_phones').empty();

          }
        },
        error: function (data) {
          console.log(data.responseJSON.errors);
          $('.loader').fadeOut('fast', function () {
            $.each(data.responseJSON.errors, function (i, e) {
              $.gNotify.danger('<strong>Erro</strong> ', e[0]);
            });
          });
        }
      });
    });

    // Visualizar
    $(document).on('click', '.visualizarCompra', function (e) {
      e.preventDefault()
      var client_id = $(this).attr('data-id');
      $.get("./comprar/" + client_id, function (retorno) {
        if (retorno.success) {
          $('form[name=editClient] input, form[name=editClient] select').attr('readonly', 'readonly');
          $('form[name=editClient] input[name=id]').val(client_id);
          $('form[name=editClient] input[name=name]').val(retorno.clients.name);
          $('form[name=editClient] input[name=email]').val(retorno.clients.email);
          $('form[name=editClient] input[name=cpf_cnpj]').val(retorno.clients.cpf_cnpj);
          $('form[name=editClient] input[name=contact]').val(retorno.clients.contact);
          $('form[name=editClient] input[name=birth]').val(moment(retorno.clients.birth).format('DD/MM/YYYY'));
          $('form[name=editClient] select[name=sex]').val(retorno.clients.sex);
          $('form[name=editClient] .select2-selection__rendered').html((retorno.clients.sex == 'M') ? 'Masculino' : 'Feminino');
          $('form[name=editClient] .select2').attr("disabled", true);
          var v_phone = '';
          $.each(retorno.clients.contacts, function (i, e) {
            v_phone += '<div class="col-md-4 clone_add_phone">';
            v_phone += '<div class="input-group">';
            v_phone += '<input type="text" name="phone[]" class="form-control telefone" value="' + e.phone +
              '" placeholder="' + e.phone + '" readonly>';
            v_phone += '<span class="input-group-btn">';
            v_phone +=
              '<a class="btn btn-danger remove_phone" href="#"><i class="fa fa-minus"></i></a>';
            v_phone += '</span>';
            v_phone += '</div>';
            v_phone += '<br>';
            v_phone += '</div>';
          });
          $('#comprarEditarModal .v_content_phones').empty().append(v_phone);

          $('#comprarEditarModal').modal('show');
          loadNegocios(client_id)
          $('#comprarEditarModal #tab_negocios').attr('data-client-id',client_id);
        } else {
          $.gNotify.danger('', retorno.message);
        }
      });
    });

    $('#comprarEditarModal').on('show.bs.modal', function (e) {
      $('.cpf').mask('999.999.999-99', {
        'placeholder': '__.___.___-__'
      });
      $('.telefone').mask("(99) 9999-9999?9", {
          'placeholder': '(  ) _____-____)'
        })
        .focusout(function (event) {
          var target, phone, element;
          target = (event.currentTarget) ? event.currentTarget : event.srcElement;
          phone = target.value.replace(/\D/g, '');
          element = $(target);
          element.unmask();
          if (phone.length > 10) {
            element.mask("(99) 99999-999?9");
          } else {
            element.mask("(99) 9999-9999?9");
          }
        });
    });

    // Habilita edição dados pessoais
    $('#comprarEditarModal .btn_edita_dados').click(function(e){
      e.preventDefault();
      $('#comprarEditarModal form[name=editClient] input, #comprarEditarModal form[name=editClient] select').removeAttr('readonly').removeAttr('disabled');
      $(this).hide();
      $('.btn_salva_dados, .btn_cancela_dados').css('display', 'inline-block');
    });

    // Desabilita edição dados pessoais
    $('#comprarEditarModal .btn_cancela_dados').click(function(e){
      e.preventDefault();
      $(this).css('display', 'none');
      $('.btn_salva_dados').css('display', 'none');
      $('.btn_edita_dados').css('display', 'inline-block');
      $('#comprarEditarModal form[name=editClient] input').attr('readonly','readonly');
      $('#comprarEditarModal form[name=editClient] select').attr('disabled','disabled');

    });

    // Edita dados pessoais
    $('#comprarEditarModal form[name=editClient]').submit(function(e){
      e.preventDefault();
      var page = $(this).attr('action');
      var token = $('input[name="_token"]').val();
      var param = $(this).serialize();
      console.log(param);
      $.gAjax.execCallback(page, token, param, false, function(retorno){
        if(retorno.success){
        $.gNotify.success(null, retorno.message);
      }else{
        $.gNotify.danger(null, retorno.message);
      }
      }, true, false, false, function(erro, payload, msg){
        console.log(erro);
        console.log(payload);
        console.log(msg);
      });
    });

    // Habilita edição do negocio
    $(document).on('click', '#comprarEditarModal .btn_edita_negocio', function(e){
      e.preventDefault();
      var id = $(this).attr('href');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] input').removeAttr('disabled');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] textarea').removeAttr('disabled');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] select').removeAttr('disabled');
      $(this).hide();
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] .btn_salva_negocio, #comprarEditarModal form[name=edita_negocio_'+id+'] .btn_cancela_negocio').css('display', 'inline-block');
    });

    // Desabilita edição do negocio
    $(document).on('click', '#comprarEditarModal .btn_cancela_negocio', function(e){
      e.preventDefault();
      var id = $(this).attr('href');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] input').attr('disabled','disabled');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] textarea').attr('disabled','disabled');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] select').attr('disabled','disabled');
      $(this).css('display', 'none');
      $('.btn_salva_negocio').css('display', 'none');
      $('.btn_edita_negocio').css('display', 'inline-block');
    });

    // Edita negocio
    $(document).on('submit', '#comprarEditarModal form.edita_negocio_form', function(e){
      e.preventDefault();
      var page = './negocios/comprar/';
      var token = $('input[name="_token"]').val();
      var param = $(this).serialize();
      var idNegocio = $(this).find('input[name=id]').val();
      var valueTipoImovel = $('form[name=edita_negocio_'+idNegocio+']').find('input[name=type_propertie]').val();
      console.log(param);
      $.gAjax.execCallback(page, token, param, false, function(retorno){
        if(retorno.success){
          $('#negocio_'+idNegocio).find('.box-title .titulo-negocio').text(valueTipoImovel);
          $.gNotify.success(null, retorno.message);
        }else{
          $.gNotify.danger(null, retorno.message);
        }
      }, true, false, false, function(erro, payload, msg){
        console.log(erro);
        console.log(payload);
        console.log(msg);
      });
    });
    
    // Carrega negócios
   function loadNegocios(id){
    $.gAjax.load('./negocios/comprar/' + id, {type: 'T', trade: 'C'}, '.container_negocios', function(retorno){
      if(retorno.success){
        var arrStatus = {
          A: 'Aguardando contato',
          B: 'Telefone errado',
          C: 'Desistiu contato',
          D: 'Négocio fechado',
          E: 'Em andamento'
        }
        var html = ''
        $.each(retorno.data, function(i, e){
          var collapseIn = (i == 0)? ' in' : '';

          html += '<div class="panel box box-default" id="negocio_'+e.id+'">';
            html += '<div class="box-header with-border">';
              html += ' <h4 class="box-title" style="display:block">';
                html += '<a data-toggle="collapse" data-parent="#accordion" href="#collapse'+e.id+'">';
                  html += '<i class="fa fa-exchange"></i> <span class="titulo-negocio">'+e.type_propertie+'</span>';
                  html += '<span class="time pull-right"><i class="fa fa-calendar"></i> '+moment(e.date).format('DD/MM/YY H:mm:ss')+'</span>';
                html += '</a>';
              html += '</h4>';
            html += '</div>';
            html += '<div id="collapse'+e.id+'" class="panel-collapse collapse '+collapseIn+'">';
              html +='<form name="edita_negocio_'+e.id+'" class="edita_negocio_form">';
                  html +='{!! csrf_field() !!}';
                  html +='<input name="id" type="hidden" value="'+e.id+'">';
                  html += '<div class="box-body">';
                      html +='<div class="row">';
                        html +='<div class="col-md-4">';
                          html +='<div class="form-group">';
                            html +='<label>Tipo do imóvel</label>';
                            html +='<input type="text" name="type_propertie" class="form-control" disabled="disabled" value="'+e.type_propertie+'">';
                          html +='</div>';
                        html +='</div>';
                        html +='<div class="col-md-4">';
                          html +='<div class="form-group">';
                            html +='<label>Valor do imóvel</label>';
                            html +='<input type="text" name="amount" data-symbol="R$ " data-thousands="." data-decimal="," class="form-control valor" disabled="disabled" value="'+numberToReal(parseFloat(e.amount))+'">';
                          html +='</div>';
                        html +='</div>';
                        html +='<div class="col-md-4">';
                          html +='<div class="form-group">';
                            html +='<label>Bairro</label>';
                            html +='<input type="text" name="neighborhood" class="form-control" disabled="disabled" value="'+e.neighborhood+'">';
                          html +='</div>';
                      html +=' </div>';
                        html +='<div class="col-md-12">';
                          html +='<div class="form-group">';
                            html +='<label>Observação</label>';
                            html +='<textarea class="form-control" name="note" rows="3" disabled="disabled">'+e.note+'</textarea>';
                          html +='</div>';
                      html +=' </div>';
                        html +='<div class="col-md-12">';
                          html +='<div class="form-group">';
                            html +='<label style="display:block">Status</label>';
                            html +='<select name="status" id="" disabled="disabled" style="width:220px;">';
                              $.each(arrStatus, function(index, element){
                                var selected = (index == e.status)? 'selected':'';
                                html +='<option value="'+index+'" '+selected+'>'+element+'</option>';
                              });
                            html +='</select>';
                          html +='</div>';
                        html +='</div>';
                      html +='</div>';
                      html += '<div class="row">';
                      html +='<div class="col-md-12" style="text-align: right;">';
                          html +='<a href="'+e.id+'" class="btn btn-default btn_cancela_negocio" style="display:none"><i class="fa fa-ban"></i> Cancelar</a>';
                          html +='<button type="submit" class="btn btn-success btn_salva_negocio" style="display:none">Salvar</button>';
                          html +='<a href="'+e.id+'" class="btn text-red btn_edita_negocio"><i class="fa fa-pencil"></i> Editar</a>';
                        html +='</div>';
                      html += '</div>';
                  html += ' </div>';
              html +='</form>';
            html += '</div>';
          html += '</div>';
      });
      $('.container_negocios').append(html);
      $('select').select2();
      $('.valor').maskMoney({
          prefix: 'R$ ',
          decimal:",", 
          thousands:"."
        });
    }else{
      $('.container_negocios').html('<p>'+retorno.message+'</p>');
    }
    }, true, true);
   }

  });
</script>
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
  <li class="active">Comprar</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  {{--
  <div class="box-header with-border">
    <h3 class="box-title">
      <i class="fa fa-filter"></i> Filtrar</h3>
  </div> --}}
  <div class="box-body">

    {{--
    <form action="{{ route('admin.imoveis.indicacao.comprar.filtro')}}" method="POST" class="form form-inline">
      <input type="hidden" name="sessao" value="indicacao">
      <input type="hidden" name="acao" value="comprar"> {!! csrf_field() !!}
      <input type="text" name="name" class="form-control" placeholder="Nome">
      <input class="form-control telefone" name="phone" type="text" placeholder="Telefone">
      <input type="text" name="date" class="form-control" id="data_cadastro" value="{{ date('d/m/Y')}}" placeholder="data">
      <button type="submit" class="btn btn-primary">
        <i class="fa fa-search"></i>
      </button>
    </form> --}}
    <a href="#" class="btn btn-primary pull-right btn_novo" data-toggle="modal" data-target="#comprarModal">
      <i class="fa fa-plus"></i> Novo</a>

  </div>
</div>


<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">Comprar</h3>
  </div>
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Nome</th>
          <th class="hidden-sm">E-mail</th>
          <th class="hidden-sm">Telefone</th>
          <th class="hidden-sm">CPF</th>
          <th class="hidden-sm">Cdastrado em</th>
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
                  <a href="#">
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
        <h4 class="modal-title">Comprar</h4>
      </div>
      <form action="./comprar" name="novo_imovel">
        {!! csrf_field() !!}
        <input type="hidden" name="birth" class="form-control daterange" placeholder="Data de nascimento">
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
                <select class="form-control select2" name="sex" style="width: 100%">
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
<div id="comprarEditarModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-user"></i> Bruno José Souza Trinchão</h4>
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
                      <select class="form-control select2" name="sex" style="width: 100%">
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
@stop

