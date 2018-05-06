@extends('adminlte::page') @section('title_prefix', 'Planos') @section('js')
@section('js')
<script>
$(document).ready(function(){
  $('form[name=nova_categoria]').submit(function(e){
    e.preventDefault();
    var url = $(this).attr('action');
    var produto = $(this).find('input[name=product_id]').val($(this).text());
    var nome = $(this).find('input[name=name]').val();
    $.post(url, $(this).serialize(), function(retorno){
        if(retorno.success){
          $.gNotify.success('<strong>Sucesso</strong> ', retorno.message);
          $('.lista_categorias').append(nome + '- '+ produto);
        }else{
          $.gNotify.danger('<strong>Erro</strong> ', retorno.message);
        }
      
    });
  });
});
</script>
@stop
@stop @section('content_header')
<h1>Banco Parceiro</h1>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
      <div class="pull-right" role="group">
        <a href="#" class="btn btn-default" data-toggle="modal" data-target="#bancosparceirosModal"><i class="fa fa-plus"></i> Categoria</a>
        {{-- <a href="{{ url('admin/administracao/planos/ordenar') }}" class="btn btn-warning"><i class="fa fa-sort"></i> Ordenar</a> --}}
        <a href="{{ url('admin/administracao/bancos-parceiros/novo') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo</a>
      </div>
  </div>
</div>

<div class="box box-solid">
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th>Imagem</th>
          <th data-priority="1">Título</th>
          <th class="hidden-sm">Produto</th>
          <th class="hidden-sm">Categoria</th>
          <th class="hidden-sm">Cadastrado</th>
          <th class="hidden-sm">Status</th>
        </tr>
      </thead>
      <tbody>
          @forelse($bancos_parceiros as $bancos)
          <tr>
            <td>
                <img src="{{ url('storage/bancos/' . $bancos->image) }}" height="25">
            </td>
            <td>{{ $bancos->name }}</td>
            <td class="">{{$bancos->name_product}}</td>
            <td class="">{{$bancos->name_bancos_categorias}}</td>
            <td class="hidden-sm">{{ date('d/m/Y', strtotime($bancos->date))}}</td>
            <td class="hidden-sm">{{ ($bancos->status == 'A')? 'Ativo': 'Inativo'}}</td>
          </tr>
        @empty @endforelse
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>


<div id="bancosparceirosModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Nova categoria</h4>
            </div>
            <form action="{{ url('admin/administracao/bancos-parceiros/categoria/novo') }}" name="nova_categoria">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Produto</label>
                                <select name="product_id"  style="width:100%">
                                    <option value="">.: Selecione :.</option>
                                    @forelse($produtos as $produto)
                                    <option value="{{$produto->id}}">{{$produto->name}}</option>
                                  @empty @endforelse
                                </select>
                            </div>
                        </div>
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

