<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Jquery UI -->
    <link rel="stylesheet" href="{{ asset('js/plugins/jquery-ui/jquery-ui.min.css')}}">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <!-- Animate -->
    <link rel="stylesheet" href="{{asset('js/plugins/animate/animate.css')}}"> 
     <!-- daterangepicker -->
    <link rel="stylesheet" href="{{asset('js/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- datatable -->
    <link rel="stylesheet" href="{{asset('js/plugins/datatable/datatables.css')}}"> 
    <link rel="stylesheet" href="{{asset('js/plugins/datatable/responsive.bootstrap.min.css')}}"> 
    <!-- select -->
    <link rel="stylesheet" href="{{asset('js/plugins/select/bootstrap-select.css')}}"> 
    <!-- input tags -->
    <link rel="stylesheet" href="{{asset('js/plugins/input-tags/bootstrap-tagsinput.css')}}"> 
    @if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
    <link rel="stylesheet" href="{{url('js/plugins/toggle/bootstrap-toggle-master/css/bootstrap-toggle.min.css')}}">
    <link href="{{url('js/plugins/icheck/skins/all.css')}}" rel="stylesheet">
    @endif
    
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">
    
    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    @endif
    
    @yield('adminlte_css')
    <link rel="stylesheet" href="{{asset('css/geral.css')}}"> 

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <script>
            var _url = "{!! url('') !!}";
        </script>
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- maskmoney -->
<script src="{{asset('js/plugins/maskmoney/jquery.maskMoney.min.js')}}"></script>
<!-- maskinput -->
<script src="{{asset('js/plugins/maskinput/jquery.maskedinput.min.js')}}"></script>
<!-- moment -->
<script type="text/javascript" src="{{asset('js/plugins/moment/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/plugins/moment/moment-with-locales.min.js')}}"></script>
<!-- daterangepicker -->
<script type="text/javascript" src="{{asset('js/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- datatable -->
<script type="text/javascript" src="{{asset('js/plugins/datatable/datatables.js')}}"></script>
<!-- notify -->
<script type="text/javascript" src="{{asset('js/plugins/notify/bootstrap-notify.min.js')}}"></script>
<!-- select -->
<script type="text/javascript" src="{{asset('js/plugins/select/bootstrap-select.min.js')}}"></script>
<!-- input tags -->
<script type="text/javascript" src="{{asset('js/plugins/input-tags/bootstrap-tagsinput.js')}}"></script>
<!-- input toggle -->
<script src="{{url('js/plugins/toggle/bootstrap-toggle-master/js/bootstrap-toggle.min.js')}}"></script>
<!-- input icheck -->
<script src="{{url('js/plugins/icheck/icheck.min.js')}}"></script>
<script src="{{ asset('js/gNotify.js')}}"></script>
<script src="{{ asset('js/gAjax.js')}}"></script>
<script src="{{ asset('js/geral.js')}}"></script>
<script>
$(document).ready(function(){
    $('input[name=client_type]').iCheck({
        checkboxClass: 'icheckbox_square',
        radioClass: 'iradio_square',
        increaseArea: '20%' // optional
    });
    $('input[name=client_type]').on('ifChecked', function(event){
        if(event.target.value == 'F'){
            $('input[name=cpf_cnpj]').attr('placeholder', 'CPF');
            $('.v_cpf_cnpj label').text('CPF');
            $('.cpf').mask('999.999.999-99', { 'placeholder': '__.___.___-__' });
        }else{
            $('input[name=cpf_cnpj]').attr('placeholder', 'CNPJ');
            $('.v_cpf_cnpj label').text('CNPJ');
            $('.cpf').mask('99.999.999/9999-99', { 'placeholder': '__.___.___/____-__' });
        }
    });

    $('.novo_cliente').click(function(e){
      e.preventDefault();
      $('form[name=novo_cliente] input[name=cpf_cnpj]').val('');
      $('.load_cliente').empty();
      $('#novoClienteModal .modal-footer').empty();
    });

    // Consulta CPF
    $('.consulta_cpf').click(function(e){
        e.preventDefault();
        var _cpf = $('form[name=novo_cliente] input[name=cpf_cnpj]').val();
        var _client_type = $('form[name=novo_cliente] input[name=client_type]').val();
        console.log(_cpf);
        console.log(_client_type);
        $('#novoClienteModal .load_cliente').empty();
        $('#novoClienteModal .modal-footer').empty()
        $.gAjax.load(_url+'/admin/clientes', {cpf: _cpf, client_type: _client_type}, null, function(retorno){
        if(!retorno.success){
            $.gNotify.danger('<strong>Erro</strong> ', retorno.message);
            return;
        }
        if(retorno.data.length > 0){
            $('.load_cliente').empty().html(loadFormCliente(true));      
            if(retorno.data[0].phone){
            var _phones = retorno.data[0].phone.split(',');
            var i = 0;
            $.each(_phones, function(i, e){
                remover = (i == 0)? false: true;
                $('.v_content_phones').append(htmlPhone(e, false, remover));
                i++;
            });
            }
            var cliente = retorno.data[0];
            
            $('#novoClienteModal form[name=novo_cliente]').find('input[name=name]').val(cliente.name);
            $('#novoClienteModal form[name=novo_cliente]').find('input[name=email]').val(cliente.email);
            $('#novoClienteModal form[name=novo_cliente]').find('select[name=sex]').val(cliente.sex);
            $('#novoClienteModal form[name=novo_cliente]').find('input[name=birth]').val(cliente.birth_formatada);
            $('#novoClienteModal form[name=novo_cliente]').find('input[name=client_id]').val(cliente.id);
            $('#novoClienteModal .modal-footer').html('<a class="btn btn-link" href="'+window.location.href+'/'+cliente.id+'">Ver cliente</a>');
        }else{
            $('.load_cliente').empty().html(loadFormCliente(false));
            $('.v_content_phones').append(htmlPhone('', true, false));
            $('#novoClienteModal .modal-footer').html('<button type="submit" class="btn btn-primary">Cadastrar</button>');
        }
        $('.telefone').mask("(99) 9999-9999?9", {
            'placeholder': '(  ) _____-____)'
        });
        $('.valor').maskMoney({
            prefix: 'R$ ',
            decimal:",", 
            thousands:"."
        });
        var data = new Date();
        $('.calendario').datepicker($.extend({}, $.datepicker.regional['pt-BR'], {
            changeMonth: true,
            changeYear: true,
            yearRange: "1960:" + data.getFullYear(),
            maxDate: "0m"
        }));
        }, true, false);
    });

    // Cadastra cliente
    $(document).on('submit', 'form[name=novo_cliente]', function (e) {
      e.preventDefault();
      var url = $(this).attr('action');
      $.ajax({
        type: 'POST',
        url: url,
        data: $(this).serialize(),
        dataType: 'json',
        beforeSend: function () {
          $('.v_content_msg').empty();
          $('body').append('<div class="loader"><img src="'+_url+'/imagens/ajax-loader.gif"></div>');
          $('.loader').fadeIn('fast')
        },
        success: function (data) {
          $('.loader').fadeOut('fast', function () {
            $(this).remove();
          });
          if (data.success) {
            $.gNotify.success('<strong>Sucesso</strong> ', data.message)
            var redirect = window.location.href + "/" + data.last_id;
            console.log(redirect);
            window.location = redirect;
          }else{
            $.gNotify.danger('<strong>Erro</strong> ', data.message)
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

    // // Add phone
$(document).on('click','.add_phone',function (e) {
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
  $('.telefone').mask("(99) 99999-9999?9", {
      'placeholder': '(  ) _____-____'
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

  // Ver informações
  $('.btn_ver_informacoes').click(function(e){
    e.preventDefault();
    var info = $(this).parents().eq(1).attr('data-note');
    $('#informacoesModal').find('.content-note').empty().text(info);
    $('#informacoesModal').modal('show');

  });
});


function loadFormCliente(disable){
  var disabled = (disable)? 'disabled readonly' : '';
  var html = "";
  html +='<input type="hidden" name="client_id" value="">';
  html +='<div class="col-md-6">';
    html +='<div class="form-group">';
      html +='<label>Nome</label>';
      html +='<input type="text" name="name" class="form-control" placeholder="Nome" '+disabled+'>';
    html +='</div>';
  html +='</div>';
  html +='<div class="col-md-6">';
    html +='<div class="form-group">';
      html +='<label>E-mail</label>';
      html +='<input type="email" name="email" class="form-control" placeholder="E-mail" '+disabled+'>';
    html +='</div>';
  html +='</div>';
  html +='<div class="col-md-6">';
    html +='<div class="form-group">';
      html +='<label>Sexo</label>';
      html +='<select class="form-control '+disabled+'" name="sex" style="width: 100%" '+disabled+'>';
        html +='<option value="" selected>.: Selecione :.</option>';
        html +='<option value="M">Masculino</option>';
        html +='<option value="F">Feminino</option>';
      html +='</select>';
    html +='</div>';
  html +='</div>';
  html +='<div class="col-md-6">';
    html +='<div class="form-group">';
      html +='<label>Data de nascimento</label>';
      html +='<input type="text" name="birth" class="form-control calendario" '+disabled+'>';
    html +='</div>';
  html +='</div>';
  html +='<div class="col-md-12">';
  html +='<label>Telefone</label>';
  if(!disable){
      html +='<a href="#" data-col="4" class="btn btn-link add_phone">';
        html +='<i class="fa fa-plus"></i> Adicionar telefone</a>';
      }
  html +='</div>';
  html +='<div class="v_content_phones" id="container_novo">';
  html +='</div>';

  return html;
}
function htmlPhone(telefone, disable, remover){
  var disabled = (disable)? '': 'disabled readonly';
  var html ='<div class="col-md-4 clone_add_phone">';
    html +='<div class="input-group">';
      html +='<input type="text" name="phone[]" class="form-control telefone" value="'+telefone+'" placeholder="Telefone" '+disabled+'>';
      if(remover){
        html +='<span class="input-group-btn">';
          html +='<a class="btn btn-danger remove_phone" href="#">';
            html +='<i class="fa fa-minus"></i>';
          html +='</a>';
        html +='</span>';
      }
    html +='</div>';
    html +='<br>';
  html +='</div>';
  return html;
}
</script>
@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endif

@if(config('adminlte.plugins.datatables'))
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
@endif

@if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
@endif

@yield('adminlte_js')

</body>
</html>
