$(document).ready(function () {
  $('.valor').maskMoney({
    prefix: 'R$ ',
    decimal:",", 
    thousands:"."
  });

  // Novo negócio
  $(document).on('click','.btn_novo_negocio',function(e){
    e.preventDefault();
    $('#consultoriaModal .box-status').hide();
    $('#consultoriaModal .box-status').find('select').attr('disabled','disabled').attr('readonly','readonly');
    $('#consultoriaModal form[name=novo_consultoria]').find('input[name=consultoria_id]').val('');
    $('#consultoriaModal form[name=novo_consultoria]').attr('method', 'POST');
    $('#consultoriaModal form[name=novo_consultoria]').find('button').text('Cadastrar').removeClass('btn-success').addClass('btn-primary');
    $('#consultoriaModal').modal('show');
  });

  // Cria novo negócio
  // $('form[name=novo_negocio]').submit(function(e){
  //   e.preventDefault();
  //   var url = $(this).attr('action');
  //   var param = $(this).serialize();
  //   $.gAjax.execCallback(url, param, false, function(retorno){
  //       if(retorno.success){
  //           $.gNotify.success(null, retorno.message);
  //           $('#novoNegocioModal input[name=renda_comprovada]').val('');
  //           $('#novoNegocioModal input[name=valor_bem]').val('');
  //           $('#novoNegocioModal input[name=valor_consultoria]').val('');
  //           $('#novoNegocioModal').modal('hide');
  //         }else{
  //           $.gNotify.danger(null, retorno.message);
  //         }
  //   }, true, false, false, function(erro, payload, msg){
  //       console.log(erro);
  //       console.log(payload);
  //       console.log(msg);
  //     });
  // });

  // Edita negócio
  $(document).on('click','.btn_edita_negocio',function(e){
    e.preventDefault();
    var id = $(this).attr('href');
    var renda_comprovada = $('#negocio-'+id).attr('data-renda');
    var valor_bem = $('#negocio-'+id).attr('data-bem');
    var valor_financiado = $('#negocio-'+id).attr('data-financiamento');
    var status = $('#negocio-'+id).attr('data-status');
    var note = $('#negocio-'+id).attr('data-note');
    
    $('#consultoriaModal form[name=novo_consultoria]').find('input[name=renda_comprovada]').val(renda_comprovada);
    $('#consultoriaModal form[name=novo_consultoria]').find('input[name=valor_bem]').val(valor_bem);
    $('#consultoriaModal form[name=novo_consultoria]').find('input[name=valor_financiado]').val(valor_financiado);
    $('#consultoriaModal form[name=novo_consultoria]').find('select[name=status]').val(status);
    $('#consultoriaModal form[name=novo_consultoria]').find('input[name=consultoria_id]').val(id);
    $('#consultoriaModal form[name=novo_consultoria]').find('textarea[name=note]').val(note);
    $('#consultoriaModal .box-status').show();
    $('#consultoriaModal .box-status').find('select').removeAttr('disabled').removeAttr('readonly');
    $('#consultoriaModal form[name=novo_consultoria]').attr('method', 'PUT');
    $('#consultoriaModal form[name=novo_consultoria]').find('button').text('Salvar').removeClass('btn-primary').addClass('btn-success');

    $('#consultoriaModal').modal('show');
    
  });

  // Edita negócio
  $(document).on('submit', '#consultoriaModal form[name=novo_consultoria]', function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    var method = $(this).attr('method');
    // console.log($(this).serialize());
    $.ajax({
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
        $('.loader').fadeOut('fast', function () {
          $.each(data.responseJSON.errors, function (i, e) {
            $.gNotify.danger('<strong>Erro</strong> ', e[0]);
          });
        });
      }
    });
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

function loadCliente(id){
  $.notifyDefaults({
      z_index: 99999999,
      animate:{
              enter: "animated fadeInUp",
              exit: "animated fadeOutDown"
      },
      offset: {
          x: 50,
          y: 100
      },
      placement: {
          align: 'center'
      }
  });
  var notify = notify = $.notify({
      message: 'Obtendo dados, aguarde...'
  },{
      type: 'info',
      allow_dismiss: false,
      showProgressbar: true
  });
  $.gAjax.load(_url+'/cliente/'+id, {}, null, function(retorno){
    console.log(retorno);
      if(retorno.success){
          notify.update({
              'type': 'success', 
              'message': retorno.message, 
              'progress': 100});
          $('#novoNegocioModal input[name=client_id]').val(retorno.clients.id);
          $('#novoNegocioModal input[name=name]').val(retorno.clients.name).attr('readonly', 'readonly').attr('disabled', 'disabled');
          $('#novoNegocioModal input[name=email]').val(retorno.clients.email).attr('readonly', 'readonly').attr('disabled', 'disabled');
          $('#novoNegocioModal input[name=cpf_cnpj]').val(retorno.clients.cpf_cnpj).attr('readonly', 'readonly').attr('disabled', 'disabled');
          $('#novoNegocioModal input[name=birth]').val(moment(retorno.clients.birth).format('DD/MM/YYYY')).attr('disabled', 'disabled');
          $('#novoNegocioModal select[name=sex]').val(retorno.clients.sex).attr('readonly', 'readonly').attr('disabled', 'disabled').attr('disabled', 'disabled');
          $('#novoNegocioModal select[name=type]').attr('readonly', 'readonly');
          $('#novoNegocioModal').modal('show');
      }else{
          notify.update({
              'type': 'danger', 
              'message': retorno.message, 
              'progress': 100});
      }
  }, true, true);
 }
