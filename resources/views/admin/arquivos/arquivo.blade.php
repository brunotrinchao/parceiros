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
    <a href="{{ url('admin/'.$archive->product) }}">{{ $archive->product_format }}</a>
  </li>
  <li>
    <a href="{{ url('admin/imoveis/arquivos') }}">Arquivos</a>
  </li>
  <li class="active">Novo</li>
</ol>
@stop @section('content')




<div class="box box-solid">
  <div class="box-header with-border">
      <h3 class="box-title">{{ $archive->name }}</h3>
      <span class="pull-right">Válido até: {{ date('d/m/Y', strtotime($archive->date)) }}</span>
  </div>
  <div class="box-body no-padding">
    <div class="mailbox-read-info text-center">
        <a href="{{ url('admin/'.$archive->product.'/arquivos/download/' . $archive->id) }}" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i> Download</a>
    </div>
    <div class="mailbox-read-message">
            {!! $archive->text !!}
    </div>
  </div>
  <!-- /.box-body -->
</div>

@stop

