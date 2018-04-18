$(document).ready(function () {
    // novo
    // $('form[name=novo_imovel] select[name=type]').change(function () {
    //   var value = $(this).val();
    //   console.log(value);
    // });
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
    // $('.btn_novo').click(function (e) {
    //   $('form[name=novo_imovel]').append('<input name="type" value="'+ <?php echo $_GET['TYPE']; ?> +'"></input>');
    // });
    $(document).on('submit', 'form[name=novo_imovel]', function (e) {
      e.preventDefault();
      var url = $(this).attr('action');
      $.ajax({
        headers: {
          'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
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
      var type = $(this).attr('input[name=trade]');
      var trade = $(this).attr('input[name=type]');
      $.get(_url+"/cliente/" + client_id, function (retorno) {
        if (retorno.success) {
          $('#comprarEditarModal .modal-title span').text(retorno.clients.name);
          $('form[name=editClient] input, form[name=editClient] select').attr('readonly', 'readonly');
          $('form[name=editClient] input[name=id]').val(client_id);
          $('form[name=editClient] input[name=name]').val(retorno.clients.name);
          $('form[name=editClient] input[name=email]').val(retorno.clients.email);
          $('form[name=editClient] input[name=cpf_cnpj]').val(retorno.clients.cpf_cnpj);
          $('form[name=editClient] input[name=contact]').val(retorno.clients.contact);
          $('form[name=editClient] input[name=birth]').val(moment(retorno.clients.birth).format('DD/MM/YYYY'));
          $('form[name=editClient] select[name=sex]').val(retorno.clients.sex);
        //   $('form[name=editClient] .select2-selection__rendered').html((retorno.clients.sex == 'M') ? 'Masculino' : 'Feminino');
        //   $('form[name=editClient] .select2').attr("disabled", true);
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
          loadNegocios(client_id, type, trade)
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
      var param = $(this).serialize();
      console.log(param);
      $.gAjax.execCallback(page, param, false, function(retorno){
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
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] input').removeAttr('readonly');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] textarea').removeAttr('readonly');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] select').removeAttr('disabled');
      $(this).hide();
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] .btn_salva_negocio, #comprarEditarModal form[name=edita_negocio_'+id+'] .btn_cancela_negocio').css('display', 'inline-block');
      
    });

    // Desabilita edição do negocio
    $(document).on('click', '#comprarEditarModal .btn_cancela_negocio', function(e){
      e.preventDefault();
      var id = $(this).attr('href');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] input').attr('readonly','readonly');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] textarea').attr('readonly','readonly');
      $('#comprarEditarModal form[name=edita_negocio_'+id+'] select').attr('disabled','disabled');
      $(this).css('display', 'none');
      $('.btn_salva_negocio').css('display', 'none');
      $('.btn_edita_negocio').css('display', 'inline-block');
    });

    // Edita negocio
    $(document).on('submit', '#comprarEditarModal form.edita_negocio_form', function(e){
      e.preventDefault();
      var page = '/admin/imoveis/indicacao/negocios/comprar/editar';
      var param = $(this).serialize();
      var idNegocio = $(this).find('input[name=id]').val();
      var valueTipoImovel = $('form[name=edita_negocio_'+idNegocio+']').find('input[name=type_propertie]').val();
      console.log(param);
      $.gAjax.execCallback(page, param, false, function(retorno){
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

    // Carrega modal novo negocio
    $(document).on('click','.btn_novo_negocio',function(e){
        e.preventDefault();
        loadCliente(1);
    });

    $('form[name=novo_negocio]').submit(function(e){
        e.preventDefault();
        var page = _url+'/admin/imoveis/indicacao/negocios/comprar';
        var param = $(this).serialize();
        $.gAjax.execCallback(page, param, false, function(retorno){
            if(retorno.success){
                $.gNotify.success(null, retorno.message);
                $('#novoNegocioModal input[name=amount]').val('');
                $('#novoNegocioModal input[name=type_propertie]').val('');
                $('#novoNegocioModal input[name=neighborhood]').val('');
                $('#novoNegocioModal textarea[name=note]').val('');
              }else{
                $.gNotify.danger(null, retorno.message);
              }
        }, true, false, false, function(erro, payload, msg){
            console.log(erro);
            console.log(payload);
            console.log(msg);
          });
    });
});
    
    // Carrega negócios
   function loadNegocios(id, type, trade){
    $.gAjax.load('./negocios/comprar/' + id, {type: type, trade: trade}, '.container_negocios', function(retorno){
      if(retorno.success){
        var arrStatus = {
          A: 'Aguardando contato',
          B: 'Telefone errado',
          C: 'Desistiu contato',
          D: 'Négocio fechado',
          E: 'Em andamento'
        }
        var html = '';
        var selectHtml = [];
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
                  html +='<input name="id" type="hidden" value="'+e.id+'">';
                  html += '<div class="box-body">';
                      html +='<div class="row">';
                        html +='<div class="col-md-4">';
                          html +='<div class="form-group">';
                            html +='<label>Tipo do imóvel</label>';
                            html +='<input type="text" name="type_propertie" class="form-control" readonly="readonly" value="'+e.type_propertie+'">';
                          html +='</div>';
                        html +='</div>';
                        html +='<div class="col-md-4">';
                          html +='<div class="form-group">';
                            html +='<label>Valor do imóvel</label>';
                            html +='<input type="text" name="amount" data-symbol="R$ " data-thousands="." data-decimal="," class="form-control valor" readonly="readonly" value="'+numberToReal(parseFloat(e.amount))+'">';
                          html +='</div>';
                        html +='</div>';
                        html +='<div class="col-md-4">';
                          html +='<div class="form-group">';
                            html +='<label>Bairro</label>';
                            html +='<input type="text" name="neighborhood" class="form-control" readonly="readonly" value="'+e.neighborhood+'">';
                          html +='</div>';
                      html +=' </div>';
                        html +='<div class="col-md-12">';
                          html +='<div class="form-group">';
                            html +='<label>Observação</label>';
                            html +='<textarea class="form-control" name="note" rows="3" readonly="readonly">'+e.note+'</textarea>';
                          html +='</div>';
                      html +=' </div>';
                        html +='<div class="col-md-12">';
                          html +='<div class="form-group">';
                            html +='<label style="display:block">Status</label>';
                            html +='<select name="status" id="select_'+e.id+'" style="width:220px;" disabled>';
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
        $('select[name=status]').select2();
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
        if(retorno.success){
            notify.update({
                'type': 'success', 
                'message': retorno.message, 
                'progress': 100});
                
                $('#novoNegocioModal input[name=id]').val(retorno.clients.id);
                $('#novoNegocioModal input[name=name]').val(retorno.clients.name).attr('readonly', 'readonly');
                $('#novoNegocioModal input[name=email]').val(retorno.clients.email).attr('readonly', 'readonly');
                $('#novoNegocioModal input[name=cpf_cnpj]').val(retorno.clients.cpf_cnpj).attr('readonly', 'readonly');
                $('#novoNegocioModal input[name=birth]').val(moment(retorno.clients.birth).format('DD/MM/YYYY'));
                $('#novoNegocioModal select[name=sex]').val(retorno.clients.sex);
                // $('#novoNegocioModal .select2').attr("disabled", true);
                // $('#novoNegocioModal .select2-selection__rendered').html((retorno.clients.sex == 'M') ? 'Masculino' : 'Feminino');

            $('#novoNegocioModal').modal('show');
            
        }else{
            notify.update({
                'type': 'danger', 
                'message': retorno.message, 
                'progress': 100});
        }
    }, true, true);
   }

