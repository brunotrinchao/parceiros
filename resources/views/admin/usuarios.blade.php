@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script>
  $(document).ready(function(){
    $('.btn_novo').click(function(e){
      e.preventDefault();
      console.log('Novo');
      $('form[name=novo_usuario]')[0].reset();
      $('form[name=novo_usuario]').find('select[name=level]').trigger('change');
      $('form[name=novo_usuario] button').removeClass('btn-success')
                                          .addClass('btn-primary')
                                          .text('Cadastrar');
    });
    // Get usuarios
    $('.btn_usuario').click(function(){
      var id = $(this).attr('data-id');
      $('form[name=novo_usuario]').find('input[name=id]').remove()
      $('form[name=novo_usuario] button').removeClass('btn-primary')
                                          .addClass('btn-success')
                                          .text('Salvar');
      $.get(_url +"/admin/usuarios/" + id, {}, function(retorno){
        if(retorno.success){
          $('form[name=novo_usuario]').find('input[name=name]').val(retorno.data.name);
          $('form[name=novo_usuario]').find('input[name=email]').val(retorno.data.email);
          $('form[name=novo_usuario]').append('<input name="id" type="hidden" value="'+retorno.data.id+'">');
          $('form[name=novo_usuario]').find('select[name=partners_id]').val(retorno.data.partners_id);
          $('form[name=novo_usuario]').find('select[name=partners_id]').trigger('change');
          $('form[name=novo_usuario]').find('select[name=level]').val(retorno.data.level);
          $('form[name=novo_usuario]').find('select[name=level]').trigger('change');
        }else{
          $.gNotify.danger('<strong>Erro</strong> ', retorno.message);
        }
      })
    });

  $('form[name=novo_usuario]').submit(function(e){
      e.preventDefault();
      var id = $('form[name=novo_usuario] input[name=id]').val();
      if(id){
        console.log('Editar');
        $.gAjax.execCallback(_url + "/admin/usuarios/editar", $('form[name=novo_usuario]').serialize(), false, function(retorno){
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
        $.gAjax.execCallback(_url + "/admin/usuarios/novo", $('form[name=novo_usuario]').serialize(), false, function(retorno){
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
    console.log($(this).serialize());
  });

  $('#usuarioModal').on('hidden.bs.modal', function (e) {
    $('form[name=novo_usuario]')[0].reset();
    $('form[name=novo_usuario]').find('input[name=id]').remove();
  });
});
</script>
@stop
@stop @section('content_header')
<h1>Usuários</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li class="active">Usuários</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <a href="#" class="btn btn-primary pull-right btn_novo"  data-toggle="modal" data-target="#usuarioModal">
      <i class="fa fa-plus"></i> Novo</a>
  </div>
</div>


<div class="box box-solid">
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Nome</th>
          <?php if(auth()->user()->level == 'S'){ ?>
            <th>Parceiro</th>
          <?php } ?>
          <th class="hidden-sm">Perfil</th>
          <th class="hidden-sm">Status</th>
        </tr>
      </thead>
      <tbody>
            @forelse($users as $user)
        <tr>
          <td>
          <a href="#" class="btn_usuario" data-id="{{ $user->id }}"  data-toggle="modal" data-target="#usuarioModal">{{ $user->name }}</a>
          </td>
          <?php if(auth()->user()->level == 'S'){ ?>
            <td class="hidden-sm">{{ $user->partner_name }}</td>
          <?php } ?>
          <td class="hidden-sm">{{ $user->level_format }}</td>
          <td class="hidden-sm">{{ $user->status_format }}</td>
        </tr>
        @empty @endforelse
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>

<!-- MODAL | Comprar -->
<div id="usuarioModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Usuário</h4>
        </div>
        <form action="" name="novo_usuario">
            {!! csrf_field() !!}
            <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label>Nome</label>
                      <input type="text" name="name" class="form-control" placeholder="Nome" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label>E-mail</label>
                      <input type="text" name="email" class="form-control" placeholder="Nome" value="">
                    </div>
                </div>
                <?php if(auth()->user()->level == 'S'){ ?>
                <div class="col-md-6">
                    <div class="form-group">
                      <label>Parceiros</label>
                        <select name="partners_id" style="width:100%">
                          <option value="" selected>.: Selecione :.</option>
                          @forelse($partners as $partner)
                            <option value="{{$partner->id}}">{{$partner->name}}</option>
                          @empty @endforelse
                        </select>
                    </div>
                </div>
              <?php }else{ ?>
                <input type="hidden" value="{{ auth()->user()->partners_id }}" name="partners_id">
              <?php } ?>
              <div class="col-md-6">
                  <div class="form-group">
                    <label>Nível de acesso</label>
                      <select name="level" style="width:100%">
                        <option value="" selected>.: Selecione :.</option>
                          <option value="A">Admin</option>
                          <option value="G">Gestor</option>
                          <option value="U">Usuário</option>
                      </select>
                  </div>
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

