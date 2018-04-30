$(document).ready(function () {
    // Add phone
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

    // Add - Edit
    $(document).on('submit', 'form[name=novo_atendimento]', function (e) {
      e.preventDefault();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      $.ajax({
        headers: {
          'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        type: method,
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
            location.reload();
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

    // Consulta CPF
    $('.consulta_cpf').click(function(e){
      e.preventDefault();
      var _cpf = $('input[name=cpf_cnpj]').val();
      $('.load_cliente').empty();
      $('#solicitarModal .modal-footer').empty()
      $.gAjax.load(_url+'/admin/clientes', {cpf: _cpf}, null, function(retorno){
        if(!retorno.success){
          $.gNotify.danger('<strong>Erro</strong> ', retorno.message);
          return;
        }
        if(retorno.data.length > 0){
          $('.load_cliente').empty().html(loadFormCliente(true));          if(retorno.data[0].phone){
            var _phones = retorno.data[0].phone.split(',');
            var i = 0;
            $.each(_phones, function(i, e){
              remover = (i == 0)? false: true;
              $('.v_content_phones').append(htmlPhone(e, false, remover));
              i++;
            });
          }
          $('.telefone').mask("(99) 9999-9999?9", {
            'placeholder': '(  ) _____-____)'
          })
          var data = new Date();
          $('.calendario').datepicker($.extend({}, $.datepicker.regional['pt-BR'], {
              changeMonth: true,
              changeYear: true,
              yearRange: "1960:" + data.getFullYear(),
              maxDate: "0m"
          }));

          $('form[name=novo_atendimento]').find('input[name=name]').val(retorno.data[0].name);
          $('form[name=novo_atendimento]').find('input[name=email]').val(retorno.data[0].email);
          $('form[name=novo_atendimento]').find('select[name=sex]').val(retorno.data[0].sex);
          $('form[name=novo_atendimento]').find('input[name=birth]').val(retorno.data[0].birth_formatada);
          $('form[name=novo_atendimento]').find('input[name=client_id]').val(retorno.data[0].id);
        }else{
          $('.load_cliente').empty().html(loadFormCliente(false));
          $('.v_content_phones').append(htmlPhone('', true, false));
        }
        $('#solicitarModal .modal-footer').html('<button type="submit" class="btn btn-primary">Cadastrar</button>');

      }, true, false);
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
    html +='<a href="#" data-col="4" class="btn btn-link add_phone">';
      html +='<i class="fa fa-plus"></i> Adicionar telefone</a>';
  html +='</div>';
  html +='<div class="v_content_phones" id="container_novo">';
  html +='</div>';
  html +='<div class="col-md-12">';
    html +='<div class="form-group">';
      html +='<label>Informações</label>';
      html +='<textarea class="form-control" name="note" rows="3"></textarea>';
    html +='</div>';
  html +='</div>';

  return html;
}

function replaceAll(str, needle, replacement) {
  return str.split(needle).join(replacement);
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

