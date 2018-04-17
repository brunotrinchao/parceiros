@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script>
 
</script>
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

<div class="box box-solid">
  <div class="box-body">
      <div class="btn-group  pull-right" role="group">
        <a href="{{ url('admin/administracao/ajuda/ordenar') }}" class="btn btn-warning"><i class="fa fa-sort"></i> Ordenar</a>
        <a href="{{ url('admin/administracao/ajuda/novo') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo</a>
      </div>
  </div>
</div>


<div class="box box-solid">
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Título</th>
          <th class="hidden-sm">Categoria</th>
          <th class="hidden-sm">Cadastrado</th>
          <th class="hidden-sm">Status</th>
        </tr>
      </thead>
      <tbody>
          @forelse($helps as $help)
          <tr>
            <td>
            <a href="{{ url('admin/administracao/ajuda/editar/' . $help->id) }}" class="btn_usuario">{{ $help->name }}</a>
            </td>
          <td class="">{{$help->name_category}}</td>
            <td class="hidden-sm">{{ date('d/m/Y', strtotime($help->date))}}</td>
            <td class="hidden-sm">{{ ($help->status == 'A')? 'Ativo': 'Inativo'}}</td>
          </tr>
        @empty @endforelse
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>

@stop

