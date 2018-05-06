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
<div class="row">
    <div class="col-md-9">
        <?php if(isset($helps)){ ?>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            @forelse($helps as $help)
            <?php $in = ''; ?>
            @if ($loop->first)
            <?php $in = 'in' ?>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="collapse-{{$help->id}}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseContent-{{$help->id}}" aria-expanded="true" aria-controls="collapseContent-{{$help->id}}">
                    {{ $help->name}}
                    </a>
                </h4>
                </div>
                <div id="collapseContent-{{$help->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse-{{$help->id}}">
                <div class="panel-body">
                        {!! $help->description !!}
                </div>
                </div>
            </div>
            @empty @endforelse
        </div>
    <?php } ?>
    </div>
    <div class="col-md-3">
        <div class="box box-solid">
            <div class="box-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Categorias</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php
                        $session = session()->get('portalparceiros');
                        $product_url = $session['produtos']['url_produto'];    
                    ?>
                    <ul class="list-unstyled">
                        @forelse($categories as $category)
                            <li><a href="{{ url('admin/'.$product_url.'/ajuda/' . $category->id) }}" class="btn btn-link">{{$category->name}}</a></li>
                        @empty @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


@stop

