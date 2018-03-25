$(document).ready(function(){
    initialize()
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
var notify;
    // login
    $('form[name=form_login]').submit(function(e){
        e.preventDefault();
        var dados = $(this).serializeArray();
        var token = $('input[name="_token"]').val();
        console.log(dados);
        console.log(token);
        jQuery.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            type: "POST",
            url: 'usuario/login',
            data: dados,
            dataType: 'json',
            async: true,
            beforeSend: function() {
                notify = $.notify({
                    icon: "fa fa-check",
                    title: '<strong>Login: </strong>',
                    message: 'Efetuando login...'
                },{
                    type: 'info',
                    allow_dismiss: false,
	                showProgressbar: true
                });
            },
            error: function(error, payload, msg) {
                $.notifyClose();
                $.notify({
                    icon: "fa fa-check",
                    message: msg
                },{
                    type: 'danger'
                });
            },
            success: function(json) {
                notify.update({
                    'type': 'success', 
                    'icon': "fa fa-check",
                    'message': json.message, 
                    'progress': 100});
                console.log(json);
                if(json.success){
                    $('.box_usuario strong').text(json.data.name);
                    $('form[name=form_login]').fadeOut(400, function(){
                        $('body').addClass('box_logado');
                    });
                    $('#banner h1').animate({
                        marginTop: -10,
                        opacity: 0
                    }, 500, "linear", function() {
                        $(this).html('Selecione um produto <i></i>').delay(300).animate({
                            marginTop: -70,
                            opacity: 1
                        }, 500, "linear");
                    });
                }else{
                    notify.update({
                        'type': 'danger', 
                        'icon': "fa fa-ban",
                        'message': json.message, 
                        'progress': 100});
                }
            }
        });
        
    });
    // logout
    $('.box_usuario a').click(function(e){
        e.preventDefault();
        $('.box_usuario').fadeOut(400, function(){
            $('body').removeAttr('id');  
            $('form[name=form_login]').fadeIn(400);        
        });
        $('#banner h1').animate({
            opacity: 0
          }, 500, "linear", function() {
            $(this).html('Seja bem vindo ao portal do parceiro').delay(300).animate({
                opacity: 1
              }, 500, "linear");
          });
    });

});

function initialize(){
    if($('body').hasClass('box_logado')){
        $('form[name=form_login]').css('display', 'none'); 
        $('#banner h1').html('Selecione um produto <i></i>');
    }else{
        $('form[name=form_login]').css('display', 'flex');       
        $('#banner h1').html('Seja bem vindo ao portal do parceiro');
    }
}