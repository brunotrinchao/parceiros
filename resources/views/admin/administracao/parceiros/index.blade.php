@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('css')
<link href="{{url('js/plugins/icheck/skins/all.css')}}" rel="stylesheet">
@stop
@section('js')
<script src="{{url('js/plugins/jqueryform/jquery.form.min.js')}}"></script>
<script src="{{url('js/plugins/icheck/icheck.min.js')}}"></script>
<script>
  $(document).ready(function(){
    $('.btn_novo').click(function(e){
      e.preventDefault();
      console.log('Novo');
      $('.content-status').empty();
      $('form[name=novo_parceiro] button').removeClass('btn-success')
                                          .addClass('btn-primary')
                                          .text('Cadastrar');
    });

    // Get Parceito
    $('.btn_parceiro').click(function(){
      var id = $(this).attr('data-id');
      var html = '';
      html += '<div class="form-group">';
      html += '<label>Status</label><br>';
      html += '<label>';
      html += '<div class="radio">';
      html += '<input type="radio" name="status" value="A"> <span style="margin-right:35px; margin-left:5px;">Ativo<span>';
      html += '</div>';
      html += '</label>';
      html += '<label>';
      html += '<div class="radio">';
      html += '<input type="radio" name="status" value="I"> <span style="margin-left:5px;">Inativo<span>';
      html += '</div>';
      html += '</label>';
      html += '</div>';      
      $('.content-status').empty().html(html);
      $('form[name=novo_parceiro]').find('input[name=id]').remove()
      $('form[name=novo_parceiro] button').removeClass('btn-primary')
                                          .addClass('btn-success')
                                          .text('Salvar');

      $.get(_url +"/admin/parceiros/" + id, {}, function(retorno){
        if(retorno.success){
          $('form[name=novo_parceiro]').find('input[name=name]').val(retorno.data.name);
          $.each($('form[name=novo_parceiro] input[name=status]'), function(i, element){
            if($(element).val() == retorno.data.status){
              $(element).attr('checked', 'checked');
            }
          })
          $('form[name=novo_parceiro]').append('<input name="id" type="hidden" value="'+retorno.data.id+'">');
        }else{
          $.gNotify.danger('<strong>Erro</strong> ', retorno.message);
        }
      })
    });

    $('#parceiroModal').on('hidden.bs.modal', function (e) {
      $('form[name=novo_parceiro]')[0].reset();
      $('form[name=novo_parceiro]').find('input[name=id]').remove()
    })

    // Edit
    $('form[name=novo_parceiro]').submit(function(e){
      e.preventDefault();
      var id = $('form[name=novo_parceiro] input[name=id]').val();

      if(id){
        console.log('Editar');
        $.gAjax.execCallback(_url + "/admin/parceiros/editar", $('form[name=novo_parceiro]').serialize(), false, function(retorno){
          if(retorno.success){
            $.gNotify.success(null, retorno.message);
            setTimeout(location.reload(), 500);
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
        $.gAjax.execCallback(_url + "/admin/parceiros/novo", $('form[name=novo_parceiro]').serialize(), false, function(retorno){
          if(retorno.success){
            $.gNotify.success(null, retorno.message);
            setTimeout(location.reload(), 500);
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
</script>
@stop
@stop @section('content_header')
<h1>Parceiros</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li class="active">Parceiros</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <a href="#" class="btn btn-primary pull-right btn_novo" data-toggle="modal" data-target="#parceiroModal">
      <i class="fa fa-plus"></i> Novo</a>
  </div>
</div>


<div class="box box-solid">
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Nome</th>
          <th class="hidden-sm">Status</th>
        </tr>
      </thead>
      <tbody>
            @forelse($partners as $partner)
        <tr>
          <td>
          <a href="#" class="btn_parceiro" data-id="{{ $partner->id }}" data-toggle="modal" data-target="#parceiroModal">{{ $partner->name }}</a>
          </td>
        <td class="hidden-sm">{{ $partner->status_format }}</td>
        </tr>
        @empty @endforelse
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>

<!-- MODAL | Comprar -->
<div id="parceiroModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Parceiro</h4>
        </div>
        <form action="{{ url('admin/parceiros/upload')}}" method="post"  name="novo_parceiro" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label>Nome</label>
                      <input type="text" name="name" class="form-control" placeholder="Nome" value="">
                    </div>
                </div>
                <div class="col-md-6 content-status">
                    
                </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
      <!-- /.modal -->

@stop

