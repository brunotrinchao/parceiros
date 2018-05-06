@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('css')
<link rel="stylesheet" href="{{url('js/plugins/editor/bootstrap3-wysihtml5.min.css')}}">
<link rel="stylesheet" href="{{url('js/plugins/toggle/bootstrap-toggle-master/css/bootstrap-toggle.min.css')}}">
<link href="{{url('js/plugins/icheck/skins/all.css')}}" rel="stylesheet">
@stop
@section('js')
<script src="{{url('js/plugins/editor/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script src="{{url('js/plugins/toggle/bootstrap-toggle-master/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{url('js/plugins/icheck/icheck.min.js')}}"></script>
<script src="{{url('js/plugins/jqueryform/jquery.form.min.js')}}"></script>
<script>
$(document).ready(function(){
    $('textarea').wysihtml5();

    $('select[name=product_id]').change(function(){
        var product_id = $(this).val();
        atualizaCategoria(product_id);
    });

    // Upload

    var bar = $('.bar');
    var percent = $('.percent');
    var progress = $('.progress');
    var status = $('#status');
      
    $('form[name=novo_banco_parceiro]').ajaxForm({
        beforeSend: function() {
            status.empty();
            progress.show();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        success: function(responseText, statusText, xhr, $form) {
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
            if(responseText.success){
              $.gNotify.success('<strong>Sucesso</strong> ', responseText.message);
              $('form[name=novo_banco_parceiro]')[0].reset();
            }else if(responseText.success = false){
              $.gNotify.danger('<strong>Erro</strong> ', responseText.message);
            }else{
              var arr = [];
              $.each(responseText.error, function(index, element){
                arr.push(element);
              });
              
              $.gNotify.warning(null, arr.join('<br>'));
            }
        },
      complete: function(xhr) {
        progress.hide();
        // status.html(xhr.responseText);
      }
    }); 



});

function atualizaCategoria(product_id){
    $('body').append('<div class="loader"><img src="{{ url("imagens/ajax-loader.gif")}}"></div>');
    $('.loader').fadeIn('fast');
    var _token = $('input[name=_token]').val();
    $.get(_url+'/admin/administracao/bancos-parceiros/categoria', {product_id: product_id, _token: _token}, function(retorno){
        var html = '';
        console.log(retorno);
        if(retorno.length > 0){
            $.each(retorno, function(i, e){
                html += '<option value="'+e.id+'">'+e.name+'</option>';
            });
        }else{
            html += '<option value="">Sem categorias</option>';
        }
        $('select[name=category_id]').empty().append(html);
        $('.loader').fadeOut('fast').remove();
    });
}
</script>
@stop
@stop @section('content_header')
<h1>Banco Parceiro</h1>
<div id="status"></div>
@stop @section('content')

<form action="{{ url('admin/administracao/bancos-parceiros/novo')}}" method="post" name="novo_banco_parceiro" enctype="multipart/form-data">
        {!! csrf_field() !!}
<div class="row">
    <div class="col-md-9">
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Título</label>
                        <input type="text" name="name" class="form-control" placeholder="Nome" value="{{ (isset($bancosParceiros->name))? $bancosParceiros->name : '' }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                            <div class="form-group">
                              <label>Arquivo <small>(máximo 5mb)</small></label>
                              <div class="input-group input-file" name="file">
                                  <input type="text" class="form-control" placeholder='Selecionar arquivo...' />
                                  <span class="input-group-btn">
                                      <button class="btn btn-default btn-choose" type="button"><i class="fa fa-upload"></i></button>
                                  </span>
                                </div>
                            </div>
                        </div>
                  
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Informações</label>
                            <textarea name="description" id="" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px;">{{ (isset($bancosParceiros->description))? $bancosParceiros->description : '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <input type="checkbox" name="status" data-toggle="toggle" data-on="Ativo" data-off="Inativo" data-onstyle="success" data-width="100%" {{ (!isset($bancosParceiros->status))? 'checked' : ($bancosParceiros->status == 'A')? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div><label>Produto</label></div>
                            <select name="product_id" style="width:100%">
                                <option value="" selected>.: Selecione :.</option>
                                @forelse($produtos as $produto)
                            <option value="{{$produto->id}}" {{ (!isset($help->product_id))? '': ($help->product_id == $produto->id)? ' selected':''}}>{{$produto->name}}</option>
                                @empty @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div><label>Categoria</label></div>
                            <select name="category_id" style="width:100%">
                                <option value="" selected>.: Selecione um produto :.</option>
                            </select>
                        </div>
                    </div>
                    <?php
                    if(isset($bancosParceiros->id)){
                    ?>
                        <input type="hidden" name="id" value="{{$bancosParceiros->id}}">
                    <?php 
                    }
                    ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-{{(isset($bancosParceiros->id))? 'success': 'primary'}} btn-block btn_cadastrar">{{(isset($bancosParceiros->id))? 'Salvar': 'Cadastrar'}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

@stop

