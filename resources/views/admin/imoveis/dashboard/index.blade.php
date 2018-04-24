@extends('adminlte::page')

@section('title_prefix', 'Dashboard')
@section('js')
<script>
var areaChartOptions = {
    //Boolean - If we should show the scale at all
    showScale               : true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : false,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - Whether the line is curved between points
    bezierCurve             : true,
    //Number - Tension of the bezier curve between points
    bezierCurveTension      : 0.3,
    //Boolean - Whether to show a dot for each point
    pointDot                : false,
    //Number - Radius of each point dot in pixels
    pointDotRadius          : 4,
    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth     : 1,
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,
    //Boolean - Whether to show a stroke for datasets
    datasetStroke           : true,
    //Number - Pixel width of dataset stroke
    datasetStrokeWidth      : 2,
    //Boolean - Whether to fill the dataset with a color
    datasetFill             : true,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio     : true,
    //Boolean - whether to make the chart responsive to window resizing
    responsive              : true
}

var areaChartData = {
      labels  : ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho'],
      datasets: [
        {
          label: 'Indicações',
          backgroundColor: [
                'rgba(232, 157, 73, 0.2)'
            ],
            borderColor: [
                'rgba(232, 157, 73,1)'
            ],
          data                : [65, 59, 80, 81, 56, 55, 40]
        }
      ]
    }
//-------------
//- LINE CHART -
//--------------
var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
var myChart = new Chart(lineChartCanvas, {
    type: 'line',
    data: areaChartData,
    options: areaChartOptions
});
</script>
@stop
@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-line-chart" aria-hidden="true"></i> Últimos 6 meses</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                    <canvas id="lineChart" style="height: 250px; width: 640px;" width="640" height="250"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
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