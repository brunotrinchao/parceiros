@extends('adminlte::page') @section('title_prefix', 'Planos') @section('js')
@section('js')
<script>
 
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
  <li class="active">planos</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
      <div class="pull-right" role="group">
        <a href="{{ url('admin/administracao/planos/categorias') }}" class="btn btn-default"><i class="fa fa-list"></i> Categorias</a>
        <a href="{{ url('admin/administracao/planos/ordenar') }}" class="btn btn-warning"><i class="fa fa-sort"></i> Ordenar</a>
        <a href="{{ url('admin/administracao/planos/novo') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo</a>
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
          @forelse($planos as $plano)
          <tr>
            <td>
            <a href="{{ url('admin/administracao/planos/editar/' . $plano->id) }}" class="btn_usuario">{{ $plano->name }}</a>
            </td>
          <td class="">{{$plano->name_category}}</td>
            <td class="hidden-sm">{{ date('d/m/Y', strtotime($plano->date))}}</td>
            <td class="hidden-sm">{{ ($plano->status == 'A')? 'Ativo': 'Inativo'}}</td>
          </tr>
        @empty @endforelse
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>

@stop

