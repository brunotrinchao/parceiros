@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar')@section('js')
@section('css')
<link rel="stylesheet" href="{{url('js/plugins/editor/bootstrap3-wysihtml5.min.css')}}">
@stop
@section('js')
<script src="{{url('js/plugins/editor/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script src="{{url('js/plugins/jqueryform/jquery.form.min.js')}}"></script>
<script>
  $(document).ready(function(){
    $('textarea').wysihtml5();

    // Upload

    var bar = $('.bar');
    var percent = $('.percent');
    var progress = $('.progress');
    var status = $('#status');
      
    $('form[name=upload_archive]').ajaxForm({
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
</script>
@stop
@stop @section('content_header')
<h1>Material promocional</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>
    <a href="{{ url('admin/imoveis') }}">Imóveis</a>
  </li>
  <li>
    <a href="{{ url('admin/imoveis/arquivos') }}">Arquivos</a>
  </li>
  <li class="active">Novo</li>
</ol>
@stop @section('content')




<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">Novo</h3>
  </div>
  <div id="status"></div>
<form action="{{ url('admin/arquivos/upload')}}" method="post" name="upload_archive"  enctype="multipart/form-data">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
          <div class="progress" style="display:none">
            <div class="progress-bar progress-bar-aqua bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
              <span class="sr-only percent">0%</span>
            </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" placeholder="Nome" >
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group">
            <label>Válido até</label>
              <div class="pull-right datecalendar" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <i class="fa fa-calendar"></i>
                <span><input type="text" name="date" style="border: none;" value="" readonly></span>
                <b class="caret pull-right"></b>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group">
            <label>Produto</label>
              <select name="product_id" style="width:100%">
                <option value="" selected>.: Selecione :.</option>
                @forelse($products as $product)
                  <option value="{{$product->id}}">{{$product->name}}</option>
                @empty @endforelse
              </select>
          </div>
      </div>
      <div class="col-md-4">
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
           <textarea name="text" id="" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px;"></textarea>
          </div>
      </div>
    </div>
    <div class="box-footer">
      <button name="upload" type="submit" class="btn btn-primary pull-right"> Cadastrar</button>
    </div>
  </form>
  </div>
  <!-- /.box-body -->
</div>

@stop

