(function($) {
    $.gAjax = {
        load: function(page, param, target, callback, async, preloader) {

            if (async === undefined)
                async = true;
            jQuery.ajax({
                type: "GET",
                url: page,
                data: param,
                async: async,
                beforeSend: function() {
                    if (preloader === undefined || preloader == true)
                    $('body').append('<div class="loader"><img src="../../../imagens/ajax-loader.gif"></div>');
                    $('.loader').fadeIn('fast')
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (preloader === undefined || preloader == true)
                        $('.loader').fadeOut('fast').remove();
                        
                    $.gNotify.danger('Carregamento interrompido...');

                    var debug = {
                        page: page,
                        status: xhr.status,
                        statusText: xhr.statusText,
                        params: param
                    }

                    console.warn('Erro ao carregar ajax: ', debug);
                },
                success: function(resp) {
                    if (preloader === undefined || preloader == true)
                    $('.loader').fadeOut('fast').remove();

                    jQuery(target).html(resp);

                    if (typeof callback === 'function') {
                        callback.call(this, resp);
                    }
                }
            });
        },
        exec: function(page, param, token, success, error, alert, async, preloader) {

            if (async === undefined)
                async = true;
            jQuery.ajax({
                headers: {
                    'X-CSRF-Token': token
                },
                type: "POST",
                url: page,
                data: param,
                dataType: 'json',
                async: async,
                beforeSend: function() {
                    if (preloader === undefined || preloader == true)
                        $('body').append('<div class="loader"><img src="{{ url("imagens/ajax-loader.gif")}}"></div>');
                        $('.loader').fadeIn('fast');
                },
                error: function() {
                    if (preloader === undefined || preloader == true)
                    $.gNotify.danger(null, "Erro ao carregar. Por favor recarregue a página e tente novamente.");
                },
                success: function(json) {
                    if (preloader === undefined || preloader == true)
                        $('.loader').fadeOut('fast').remove();

                        if (json.status)
                            eval(success);
                        else
                            $.gNotify.danger(null, json.message);
                }
            });
        },
        execCallback: function(page, token, param, alert, callback, async, preloader, alertError, errorCallBack) {

            if (async === undefined)
                async = true;
            jQuery.ajax({
                headers: {
                    'X-CSRF-Token': token
                },
                type: "POST",
                url: page,
                data: param,
                dataType: 'json',
                async: async,
                beforeSend: function() {
                    if (preloader === undefined || preloader === true)
                        $('body').append('<div class="loader"><img src="{{ url("imagens/ajax-loader.gif")}}"></div>');
                        $('.loader').fadeIn('fast');
                },
                error: function(error, payload, msg) {
                    if (preloader === undefined || preloader === true)
                    $('.loader').fadeOut('fast').remove();
                    $.gNotify.danger(null,"Erro ao carregar. Por favor recarregue a página e tente novamente.");
                    if (errorCallBack !== undefined)
                        errorCallBack.call(this, error, payload, msg);
                },
                success: function(json) {
                    if (preloader === undefined || preloader === true)
                        $('.loader').fadeOut('fast').remove();

                    if (typeof callback === 'function') {
                        callback.call(this, json);
                    }

                    
                    if (!json.status && (alertError === undefined || alertError === true)) {
                        $.gNotify.danger(null, json.message);
                    }

                }
            });
        }
    }
})(jQuery);