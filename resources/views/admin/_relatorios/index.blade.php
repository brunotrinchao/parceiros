@extends('adminlte::page') @section('title_prefix', 'Relatório') @section('js')
@section('js')
<script>
  $(document).ready(function(){
    $('body').addClass('sidebar-collapse');
  });
</script>
@stop
@stop @section('content_header')
<?php
    $session = session()->get('portalparceiros');
    $name_produto = $session['produtos']['name_produto'];    
    $url_produto = $session['produtos']['url_produto'];    
    $id_produto = $session['produtos']['id_produto'];    
?>
<h1>Relatórios</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
<li>{{ $name_produto }}</li>
<li class="active">Relatórios</li>
</ol>
@stop @section('content')

<div class="box box-solid">
  <div class="box-body">
    <div class="row">
    <form action="{{ url('admin/'.$url_produto.'/relatorios/resultado') }}" method="post">
      {!! csrf_field() !!}
      <input type="hidden" name="product_id" value="{{ $id_produto }}">
        <div class="col-md-3">
            <div class="form-group">
                <label>Período </label>
                <input type="text" name="periodo_range" class="form-control daterange" placeholder="Período" value="{{ date('d/m/Y') }}" style="width:100%">
                <input type="hidden" name="periodo" class="daterange_hidden" value="{{ date('Y-m-d|Y-m-d') }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {{(new \App\Helpers\Helper)->filtroRelatorio($url_produto)}}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
                <label>Status </label>
                <select name="status" style="width:100%">
                    <option value="">.: Selecione :.</option>
                    <?php 
                        foreach((new \App\Helpers\Helper)->listaStatus() as $key => $value){
                            echo ' <option value="'.$key.'">'.$value.'</option>';
                        }
                    ?>
                </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    </div>
  </div>
</div>

<?php
  if(count($relatorios) > 0){
?>
<div class="row">
  <?php echo $indicadores; ?>
</div>



<div class="box box-solid">
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Imóvel</th>
          <th class="hidden-sm">Cliente</th>
          <th class="hidden-sm">Usuário</th>
          <th class="hidden-sm">Valor</th>
          <th class="hidden-sm">Negócio</th>
          <th class="hidden-sm">Data</th>
        </tr>
      </thead>
      <tbody>
          @forelse($relatorios as $relatorio) 
        <tr>
          <td>{{ $relatorio->imovel }}</td>
          <td class="hidden-sm">{{ $relatorio->cliente }}</td>
          <td class="hidden-sm">{{ $relatorio->usuario }}</td>
          <td class="hidden-sm">{{ $relatorio->preco }}</td>
          <td class="hidden-sm">{{ $relatorio->negocio }}</td>
          <td class="hidden-sm">{{ date('d/m/Y', strtotime($relatorio->data)) }}</td>
        </tr>
         @empty @endforelse 
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>
<?php
  }else{
    echo '<p>Nenhum resultado encontrado.</p>';
  }
?>
@stop

