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
<script>
$(document).ready(function(){
    $('textarea').wysihtml5();
    // Nova categoria
    $('form[name=nova_categoria]').submit(function(e){
      e.preventDefault();
      $.post(_url+'/admin/administracao/planos/categoria', $(this).serialize(), function(retorno){
          if(retorno.success){
            $.gNotify.success('<strong>Sucesso</strong> ', retorno.message);
            atualizaCategoria();
          }else{
            $.gNotify.danger('<strong>Erro</strong> ', retorno.message);
          }
       
      });
    });

    // Cadastra / Edita
    $('form[name=novo_plano]').submit(function(e){
        e.preventDefault();
        var id = $('form[name=novo_plano] input[name=id]').val();
      if(id){
        console.log('Editar');
        $.gAjax.execCallback(_url + "/admin/administracao/planos/update", $(this).serialize(), false, function(retorno){
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
      }else{
        console.log('Novo');
        $.gAjax.execCallback(_url + "/admin/administracao/planos/novo", $(this).serialize(), false, function(retorno){
          if(retorno.success){
            $.gNotify.success(null, retorno.message);
            $('form[name=novo_plano]')[0].reset();
          }else{
            $.gNotify.danger(null, retorno.message);
          }
        }, true, false, false, function(erro, payload, msg){
          console.log(erro);
          console.log(payload);
          console.log(msg);
        });
      }
    });

});
function atualizaCategoria(){
    $('body').append('<div class="loader"><img src="{{ url("imagens/ajax-loader.gif")}}"></div>');
    $('.loader').fadeIn('fast');
    $.get(_url+'/admin/administracao/planos/categoria', {}, function(retorno){
        var html = '<option value="" selected>.: Selecione :.</option>';
        $.each(retorno, function(i, e){
            html += '<option value="'+e.id+'">'+e.name+'</option>';
        });
        $('select[name=category_id]').empty().append(html);
        $('#categoriaModal').modal('hide');
        $('.loader').fadeOut('fast').remove();
    });
}
</script>
@stop
@stop @section('content_header')
<h1>Planos</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>Administração</li>
  <li class="active">Planos</li>
</ol>
@stop @section('content')

<form action="" name="novo_plano">
        {!! csrf_field() !!}
<div class="row">
    <div class="col-md-9">
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Título</label>
                        <input type="text" name="name" class="form-control" placeholder="Nome" value="{{ (isset($planos->name))? $planos->name : '' }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Informações</label>
                            <textarea name="description" id="" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px;">{{ (isset($planos->description))? $planos->description : '' }}</textarea>
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
                            <input type="checkbox" name="status" data-toggle="toggle" data-on="Ativo" data-off="Inativo" data-onstyle="success" data-width="100%" {{ (!isset($help->status))? 'checked' : ($help->status == 'A')? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div><label>Categoria</label> <a href="#" class="btn-link pull-right"   data-toggle="modal" data-target="#categoriaModal">adicionar</a></div>
                            <select name="category_id" style="width:100%">
                                <option value="" selected>.: Selecione :.</option>
                                @forelse($categories as $categorie)
                            <option value="{{$categorie->id}}" {{ (!isset($planos->planos_category_id))? '': ($planos->planos_category_id == $categorie->id)? ' selected':''}}>{{$categorie->name}}</option>
                                @empty @endforelse
                            </select>
                        </div>
                    </div>
                    <?php
                    if(isset($planos->id)){
                    ?>
                        <input type="hidden" name="id" value="{{$planos->id}}">
                    <?php 
                    }
                    ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-{{(isset($planos->id))? 'success': 'primary'}} btn-block btn_cadastrar">{{(isset($planos->id))? 'Salvar': 'Cadastrar'}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

<div id="categoriaModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Nova categoria</h4>
            </div>
            <form action="" name="nova_categoria">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" name="name" class="form-control" placeholder="Nome" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

