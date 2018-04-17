@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script>
</script>
@stop
@stop @section('content_header')
<h1>Relatórios</h1>
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
    <div class="row">
        <?php
        $session = session()->get('portalparceiros');
        $product_url = $session['url_produto'];    
    ?>
    <form action="{{ url('admin/'.$product_url.'/relatorios') }}" method="post" class="form-inline">
      {!! csrf_field() !!}
      <div class="col-md-12">
          <div class="form-group">
              <label>Período </label>
              <input type="text" name="periodo_range" class="form-control daterange" placeholder="Período" value="{{ date('d/m/Y') }}">
              <input type="hidden" name="periodo" class="daterange_hidden" value="{{ date('Y-m-m|Y-m-d') }}">
          </div>
          <div class="form-group">
              <label> Tipo </label>
              <select name="type" style="width:180px">
                  <option value="" selected>.: Selecione :.</option>
                  <option value="C">Compra</option>
                  <option value="V">Venda</option>
                  <option value="A">Aluguel</option>
              </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
          </div>
        </div>
        </div>
    </form>
    </div>
  </div>
</div>


<div class="box box-solid">
  <div class="box-body">
    <table class="table table-striped table-bordered dt-responsive nowrap datatables" width="100%">
      <thead>
        <tr>
          <th data-priority="1">Nome</th>
          <th class="hidden-sm">Perfil</th>
          <th class="hidden-sm">Status</th>
        </tr>
      </thead>
      <tbody>
            @forelse($properties as $propertie)
        <tr>
          <td>gghfhgfh</td>
          <td class="hidden-sm">gfhfgj</td>
          <td class="hidden-sm">hjghjk</td>
        </tr>
        @empty @endforelse
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->
</div>


@stop

