@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
@stop
@stop @section('content_header')
<h1>Material promocional</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>
    <a href="#">Imóveis</a>
  </li>
  <li class="active">Arquivos</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <a href="{{ url('admin/arquivos/novo') }}" class="btn btn-primary pull-right">
      <i class="fa fa-plus"></i> Novo</a>
  </div>
</div>


<div class="box box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">Arquivos</h3>
  </div>
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Nome</th>
          <th class="hidden-sm" width="15%">Produto</th>
          <th class="hidden-sm" width="15%">Tipo</th>
          <th class="hidden-sm" width="15%">Válido até</th>
        </tr>
      </thead>
      <tbody>
            @forelse($archives as $archive)
        <tr>
          <td>
          <a href="{{ url('admin/'.$archive->product_slug.'/arquivos/' . $archive->id) }}" class="btn_arquivo" data-id="{{ $archive->id}}"><i class="fa fa-{!! App\Helpers\Helper::getIcon($archive->file)!!}"></i> {{ $archive->name }}</a>
          </td>
        <td>{{ $archive->product_name }}</td>
        <td class="hidden-sm">{!! App\Helpers\Helper::getExtension($archive->file)!!}</td>
          <td class="hidden-sm">{{ date('d/m/Y', strtotime($archive->date)) }}</td>
        </tr>
        @empty @endforelse
      </tbody>
    </table>
    {{-- {!! $clients->links() !!} --}}
  </div>
  <!-- /.box-body -->
</div>

@stop

