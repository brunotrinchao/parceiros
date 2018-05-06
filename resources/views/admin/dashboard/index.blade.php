@extends('adminlte::page')

@section('title_prefix', 'Dashboard')
@section('js')

</script>
@stop
@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-9">
            
        </div>
        <div class="col-md-3">
            <?php
            $arr = [
                [
                    "name" => "Indicados",
                    "value" => 1000
                ],
                [
                    "name" => "Contactados",
                    "value" => 450
                ],
                [
                    "name" => "Inconsistentes",
                    "value" => 120
                ],
                [
                    "name" => "Visitados",
                    "value" => 0
                ],
                [
                    "name" => "Propostas",
                    "value" => 43
                ],
                [
                    "name" => "Em negociação",
                    "value" => 20
                ],
                [
                    "name" => "Vendas",
                    "value" => 5
                ],
        ];
            foreach($arr as $item){
            ?>
                <div class="small-box bg-primary">
                    <div class="inner">
                    <h3>{{ $item['value']}}</h3>

                        <p>{{ $item['name']}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-signal"></i>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
@stop