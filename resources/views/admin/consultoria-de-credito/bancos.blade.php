@extends('adminlte::page') @section('title_prefix', 'Indicação | Comprar') @section('js')
@section('js')
<script>
 
</script>
@stop
@stop @section('content_header')
<h1>Bancos parceiros</h1>
<ol class="breadcrumb">
  <li>
    <a href="#">
      <i class="fa fa-dashboard"></i> Home</a>
  </li>
  <li>Consultoria de crédito</li>
  <li class="active">Bancos parceiro</li>
</ol>
@stop @section('content')
<div class="row">
    <div class="col-md-12">
        <?php if(isset($bancos)){ ?>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            @forelse($bancos as $key => $value)
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="collapse-{{ (new \App\Helpers\Helper)->createSlug($key) }}">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseContent-{{ (new \App\Helpers\Helper)->createSlug($key) }}" aria-expanded="true" aria-controls="collapseContent-{{ (new \App\Helpers\Helper)->createSlug($key) }}">
                        {{ $key}}
                        </a>
                    </h4>
                    </div>
                    <div id="collapseContent-{{ (new \App\Helpers\Helper)->createSlug($key) }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse-{{ (new \App\Helpers\Helper)->createSlug($key) }}">
                    <div class="panel-body">
                        <table class="table table-bordered">
                                <tbody>
                            @forelse($value as $k => $banco)
                                <tr>
                                    <td width="110">
                                        <img src="{{ url('storage/bancos/' . $banco->image) }}" style="width:auto; height:100px;">
                                    </td>
                    
                                    <td>
                                        {!! $banco->description !!}
                                    </td>
                                <!-- /.info-box-content -->
                                </tr>
                                                                
                            @empty @endforelse
                            </tbody>
                        </table>  
                    </div>
                    </div>
                </div>
            @empty @endforelse
        </div>
    <?php } ?>
    </div>
</div>


@stop

