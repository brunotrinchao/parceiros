(function($) {
        // Notificações
        $.notifyDefaults({
                z_index: 99999999,
                animate:{
                        enter: "animated fadeInUp",
                        exit: "animated fadeOutDown"
                }
        });
        $.gNotify = {
                success: function(title, msg){
                        $.notify({
                                icon: "fa fa-check",
                                title: title,
                                message: msg
                        },{
                           type: 'success',
                        });
                },
                info: function(title, msg){
                        $.notify({
                                icon: "fa fa-info",
                                title: title,
                                message: msg
                        },{
                                type: 'info',
                        });
                },
                warning: function(title, msg){
                        $.notify({
                                icon: 'fa fa-exclamation',
                                title: title,
                                message: msg
                        },{
                                type: 'warning',
                        });
                },
                danger: function(title, msg){
                        $.notify({
                                icon: 'fa fa-ban',
                                title: title,
                                message: msg
                        },{
                                type: 'danger',
                        });
                }
        }

        // Ajax
        $.send = function(token, dados){
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
                          });
                          if (data.success) {
                            $('form[name=novo_imovel] input').val('');
                            $('form[name=novo_imovel] textarea').val('');
                            $('form[name=novo_imovel] select').val('');
                            $('.v_content_phones').empty();
                
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
        }
})(jQuery);