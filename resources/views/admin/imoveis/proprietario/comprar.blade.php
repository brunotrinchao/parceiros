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
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.27/daterangepicker.css"> 

@stop
@section('js')
<!-- bootstrap mask money -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<!-- bootstrap imnutmask -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
<!-- bootstrap datepicker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.27/daterangepicker.js"></script>    
<script>
$(function() {
      $('.select2').select2();

     //Date picker
     $('.datetimepicker').daterangepicker({
        "singleDatePicker": true,
        "autoApply": true,
        "locale": {
            "format": "MM/DD/YYYY",
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "Do",
                "Se",
                "Te",
                "Qu",
                "Qu",
                "Se",
                "Sa"
            ],
            "monthNames": [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 1
        },
        "alwaysShowCalendars": true,
        "startDate": "03/15/2018",
        "endDate": "03/21/2018",
        "opens": "left"
    }, function(start, end, label) {
      console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });


    var start = moment().subtract(29, 'days');
    var end = moment();
     $('#data_cadastro').daterangepicker({
        ranges: {
           'Hoje': [moment(), moment()],
           'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
           'Este mês': [moment().startOf('month'), moment().endOf('month')],
           'Mês anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "locale": {
            "format": "DD/MM/YYYY",
            "separator": " até ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "De",
            "toLabel": "Para",
            "customRangeLabel": "Selecionar período",
            "daysOfWeek": [
                "Do",
                "Se",
                "Te",
                "Qu",
                "Qu",
                "Se",
                "Sa"
            ],
            "monthNames": [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 1
        },
        "startDate": start,
        "endDate": end,
        "opens": "center",
        "endDate": end,
        "maxDate": end,
        "autoUpdateInput": false,
        "alwaysShowCalendars": false,
    }, function(start, end, label) {
      if (start.format('DD/MM/YYYY') !== end.format('DD/MM/YYYY')) {
          $('#data_cadastro').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
      } else {
          $('#data_cadastro').val(start.format('DD/MM/YYYY'));
      }
    });


    
    $('.cpf').mask('999.999.999-99', { 'placeholder': '__.___.___-__' });
    $('.telefone').mask("(99) 9999-9999?9", { 'placeholder': '(  ) _____-____)' })
        .focusout(function (event) {  
            var target, phone, element;  
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
            phone = target.value.replace(/\D/g, '');
            element = $(target);  
            element.unmask();  
            if(phone.length > 10) {  
                element.mask("(99) 99999-999?9");  
            } else {  
                element.mask("(99) 9999-9999?9");  
            }  
        });
    $('.valor').maskMoney({
      prefix: 'R$ ',
      decimal:",", 
      thousands:"."
    });

      // novo
      $('form[name=novo_imovel] select[name=type]').change(function(){
        var value = $(this).val();
        console.log(value);
      });
      // Add phone
      $('.add_phone').click(function(e){
        e.preventDefault();
        var col = ($(this).attr('data-col').length > 0)? $(this).attr('data-col') : 6;
        console.log(col);
        var numPhone = $('.clone_add_phone').length;
        var html = '<div class="col-md-'+col+' clone_add_phone">';
        html += '<div class="input-group">';
        html += '<input type="text" name="phone[]" class="form-control telefone" placeholder="Telefone" required>';
        html += '<span class="input-group-btn">';
        html += '<a class="btn btn-danger remove_phone" href="#"><i class="fa fa-minus"></i></a>';
        html += '</span>';
        html += '</div>';
        html += '<br>';
        html += '</div>';
        $('.v_content_phones').append(html);
        $('.telefone').mask("(99) 9999-9999?9", { 'placeholder': '(  ) _____-____)' })
        .focusout(function (event) {  
            var target, phone, element;  
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
            phone = target.value.replace(/\D/g, '');
            element = $(target);  
            element.unmask();  
            if(phone.length > 10) {  
                element.mask("(99) 99999-999?9");  
            } else {  
                element.mask("(99) 9999-9999?9");  
            }  
        });
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
            var tipo = (data.success)? 'success' : 'danger';
            var html = '<div class="alert alert-'+tipo+' alert-dismissible">';
                  html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                  html += data.message;
                  html += '</div>';
                $('.v_content_msg').append(html);
                $(this).remove();
            });
            if(data.success){
              $('form[name=novo_imovel] input').val('');
              $('form[name=novo_imovel] textarea').val('');
              $('form[name=novo_imovel] select').val('');
              $('.v_content_phones').empty();
            }
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

      // Visualizar
      $('.visualizarCompra').click(function(e){
        e.preventDefault()
        var compra_id = $(this).attr('data-id');

        $.get( "./comprar/"+compra_id, function(retorno){
          if(retorno.success){
            $.each(retorno.clients, function(i, e){
             $('#comprarEditarModal form[name=editClient] input[name=' + i +']').val(e).attr('readonly', 'readonly').focus();
            });
            $.each(retorno.clients.contacts, function(i, e){
              console.log(e.phone);
              var html = '<div class="col-md-4 clone_add_phone">';
              html += '<div class="input-group">';
              html += '<input type="text" name="phone[]" class="form-control telefone" val="'+e.phone+'" placeholder="'+e.phone+'" readonly>';
              html += '<span class="input-group-btn">';
              html += '<a class="btn btn-danger remove_phone" href="#"><i class="fa fa-minus"></i></a>';
              html += '</span>';
              html += '</div>';
              html += '<br>';
              html += '</div>';
              $('.v_content_phones').append(html);
            });
            $('form[name=editClient] .select2-selection__rendered').html((retorno.clients.sex == 'M')? 'Masculino' : 'Feminino' );
            $('.select2').attr("disabled", true);
            $('#comprarEditarModal').modal('show');
          }else{

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

  <form action="{{ route('admin.imoveis.indicacao.comprar.filtro')}}" method="POST" class="form form-inline">
        <input type="hidden" name="sessao" value="indicacao">
        <input type="hidden" name="acao" value="comprar">
        {!! csrf_field() !!}
            <input type="text" name="name" class="form-control" placeholder="Nome">
            <input class="form-control telefone" name="phone" type="text" placeholder="Telefone">
            <input type="text" name="date" class="form-control" id="data_cadastro" value="{{ date('d/m/Y')}}" placeholder="data">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#comprarModal"><i class="fa fa-plus"></i> Novo</a>
      </form>

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
          <th class="hidden-sm">E-mail</th>
          <th class="hidden-sm">Telefone</th>
          <th class="hidden-sm">Status</th>
          <th class="hidden-sm">Valor</th>
          <th style="width: 40px"></th>
        </tr>
      </thead>
      <tbody>
          
        @forelse($clients as $client)
        
        <tr>
          <td><a href="#" data-id="{{ $client->id }}" class="btn-link visualizarCompra">{{ $client->name }}</a></td>
          <td class="hidden-sm">{{ $client->email }}</td>
          <td class="hidden-sm">
              @if(count($client->contacts) > 0)
                @forelse($client->contacts as $contato)
                  {{ $contato->phone }} <br>
                @empty
                @endforelse
              @else
                -
              @endif
            </td>
          <td class="hidden-sm">
            @forelse($client->properties as $propertie)
            {{ $client->formatStatusPropertie($propertie->properties_status['status']) }}
            @empty
            @endforelse
          </td>
        <td clss="hidden-sm">
            @forelse($client->properties as $propertie)
            R$ {{ number_format($propertie->amount, 2, ',','.') }}
            @empty
            @endforelse
        </td>
          <td width="50px">
              <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" style="left: auto">
                    <li><a href="#"><i class="fa fa-dollar"></i> Gerar novo negócio</a></li>
                  </ul>
                </div>
          </td>
        </tr>
        @empty
        @endforelse
      </tbody>
    </table>
    {{--  {!! $clients->links() !!}  --}}
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
                        <input type="text" name="birth" class="form-control datetimepicker" placeholder="Data de nascimento">
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
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


<!-- MODAL | Comprar (Editar - Vizualizar) -->
<div id="comprarEditarModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Bruno José Souza Trinchão</h4>
        </div>
        <div class="modal-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Dados pessoais</a></li>
                  <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Negócios</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <form action="" name="editClient">  
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
                                <input type="text" name="birth" class="form-control datetimepicker" placeholder="Data de nascimento">
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
                            <a href="#" data-col="4" class="btn btn-link add_phone"><i class="fa fa-plus"></i> Adicionar telefone</a>
                          </div>
                          <div class="v_content_phones">
                          </div>
                        </div>
                      </form>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                    The European languages are members of the same family. Their separate existence is a myth.
                    For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                    in their grammar, their pronunciation and their most common words. Everyone realizes why a
                    new common language would be desirable: one could refuse to pay expensive translators. To
                    achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                    words. If several languages coalesce, the grammar of the resulting language is more simple
                    and regular than that of the individual languages.
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
@stop