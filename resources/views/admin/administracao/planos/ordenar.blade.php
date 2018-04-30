@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script src="{{url('js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script>
 $(document).ready(function(){
    $( "#sortable" ).sortable({
      placeholder: "ui-state-highlight list-group-item",
      axis: "y",
      update: function (event, ui) {
        var data = $(this).sortable('serialize');
        var id = $(this).attr('data-id');
        var _token = $('input[name=_token]').val();
        $.post(_url+'/admin/administracao/ajuda/ordenar', {lista:data, category_id: id, _token: _token}, function(retorno){
            console.log(retorno);
        });
    }
    });
    $( "#sortable" ).disableSelection();

    $('.btn-filtar').click(function(e){
        e.preventDefault();
        var product_id = $('select[name=product_id]').val();
        var category_id = $('select[name=category_id]').val();
        
        if(product_id && category_id){
            carregaAjuda(product_id , category_id);
        }else if(product_id && !category_id){
            $.gNotify.danger(null, 'Selecione a categoria');
        }else if(!product_id && category_id){
            $.gNotify.danger(null, 'Selecione o produto');
        }else{
            $.gNotify.danger(null, 'Selecione o produto e a categoria');
        }
    });
 });
 function carregaAjuda(product_id, category_id){
    $('body').append('<div class="loader"><img src="{{ url("imagens/ajax-loader.gif")}}"></div>');
    $('.loader').fadeIn('fast');
    $.get(_url+'/admin/administracao/ajuda/lista-por-categoria/' + category_id +'/'+ product_id, {}, function(retorno){

        if(Object.keys(retorno).length > 0){
            var html = '';
            $( "#sortable" ).attr('data-id', category_id);
            $.each(retorno, function(i, e){
                html += '<li class="list-group-item" id="item-'+e.id+'">'+e.name+'</li>';
            });
            $('.content_lista').html(html);
        }else{
            $('.content_lista').empty();
            $( "#sortable" ).prepend('<p>Nenhum resultado encontrado.</p>');
        }
        $('.loader').fadeOut('fast').remove();
        return false;
    });
}
</script>
@stop
@section('css')
<link rel="stylesheet" href="{{url('js/plugins/jquery-ui/jquery-ui.min.css')}}">
<style>
.content_lista li{
    cursor:move;
}
.ui-sortable-helper{
}
.ui-state-highlight{
    height:42px;
    background: #e1e2e2;
    border-color: transparent;
}
</style>
@stop
@stop @section('content_header')
<h1>Ajuda</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>Administração</li>
  <li class="active">Ajuda</li>
</ol>
@stop @section('content')
<div class="alert alert-info">
    <p>Para organizar os artigos arraste-os para organiza-los. </p>
</div>

<div class="box box-solid">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                <select name="product_id"  style="width:200px">
                    <option value="" selected>.: Selecione o produto :.</option>
                    @forelse($produtos as $produto)
                        <option value="{{$produto->id}}" {{ (!isset($help->product_id))? '': ($help->product_id == $produto->id)? ' selected':''}}>{{$produto->name}}</option>
                    @empty @endforelse
                </select>
            </div>
            <div class="col-md-3">
                <select name="category_id"  style="width:200px">
                    <option value="" selected>.: Selecione a categoria :.</option>
                    @forelse($categories as $categorie)
                        <option value="{{$categorie->id}}" {{ (!isset($help->category_id))? '': ($help->category_id == $categorie->id)? ' selected':''}}>{{$categorie->name}}</option>
                    @empty @endforelse
                </select>
            </div>
            <div class="col-md-3">
                <a href="#" class="btn btn-primary btn-filtar"><i class="fa fa-search" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</div>
{!! csrf_field() !!}
<ul id="sortable" class="list-group content_lista" data-id="">
</ul>
  <!-- /.box-body -->
</div>

@stop

