@extends('layouts.masterForm',["widthh"=>12])

@section('titulo', 'Rpte. Tickets Usuario')
@section('cuerpo')

<div>
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->
        @csrf
        <div style="margin-left:6px;margin-bottom: 12px;">
            <a href="{{ url('ticketSoporte/listadoTicketSoporte') }}" class="btn btn-danger"><span class="fa fa-file" title="Limpiar"></span></a>
            <a id="btnConsultar" title="Consultar" href="javascript:consultar()" class="btn btn-success"><span class="fa fa-search" title="Consultar"></span></a>
            <button data-toggle="collapse" class="btn btn-warning" data-target="#filtro"><i class="fas fa-caret-down"></i> Filtros</button>
        </div>
        
        <div id="filtro" class="collapse rounded-top" style="padding: 5px; border: 1px #ccc solid;">
            <div class="row">
                <div class="col-md-2">
                    <label for="fechaInicial"><b>Fecha Inicial</b></label>
                    <input id="fechaInicial" name="fechaInicial" id="fechaInicial" class="form-control datepicker" value="{{old('fechaInicial', Carbon\Carbon::now()->format('d/m/Y'))}}"></input>
                    <div class="text-danger">{{$errors->error->first('fechaInicial')}}</div>
                </div>
                <div class="col-md-2">
                    <label for="fechaFinal"><b>Fecha Final</b></label>
                    <input name="fechaFinal" id="fechaFinal" class="form-control datepicker" value="{{old('fechaFinal', Carbon\Carbon::now()->format('d/m/Y'))}}"></input>
                    <div class="text-danger">{{$errors->error->first('fechaFinal')}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    @include("controles.empresa.busquedaEmpresa",["name"=> "empresa", "titulo"=> "Búsqueda Empresa"])
                </div>
            </div>
        </div>
        <br>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="tabForm">
            <li class="nav-item"><a class="nav-link active" role="tab" href="#form" aria-controls="home" role="tab" data-toggle="tab">Listado</a></li>
            <li class="nav-item"><a class="nav-link" role="tab" href="#grafico" aria-controls="grafico" role="tab" data-toggle="tab">Gráfica</a></li>
        </ul>
        <div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
                <br>
                <div class="row">
                    <table id="ticket_table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Empresa</th>
                                <th>Cantidad Tickets</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>
                    </table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="grafico">
                <button data-toggle="collapse" class="btn btn-default" id="btnDescargarGrafica"><i class="fas fa-caret-down"></i> Descargar</button>
                <div class="container">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        
                        </div>
                        <div class="panel-body" align="center">
                        <div id="pie_chart" style="width:850px; height:450px;">
                    
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@stop

@section('script')
<script type="text/javascript" charset="utf-8" >
    var analytics = null;
    var imgUri = null;
    campoFecha();
    fecha = new Date();
    $("#fechaInicial").val("01/"+(fecha.getMonth() != 10 && fecha.getMonth() != 11 ? ("0"+(fecha.getMonth()+1)) : (echa.getMonth()+1))+"/"+fecha.getFullYear());
    $(function() {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            if(target == "#form") {
                $('#tblItems > tbody').html("");
                $("#guidInf").val("");
                $("#empresaInf").val("");
                $("#clienteInf").val("");
                $("#fechaSolicitudInf").val("");
                $("#btnNuevaTarea").hide();
                $("#guid").val("");
            }
        });

        $("#btnDescargarGrafica").click(function () {
            if(imgUri != null) {
                filee(imgUri, "rptUsuarioTicket.png", "image/png");
            }
        });

        consultar();
    });

    function consultar()
	{
        var fechaInicio = $("#fechaInicial").val().replaceAll("/", "-");
        var fechaFin = $("#fechaFinal").val().replaceAll("/", "-");

        var idCategoria = $("#categoria").val();
        if(idCategoria == "" || idCategoria == null || idCategoria == "null") {
            idCategoria = 0;
        }

        var idEmpresa = $("input[name=empresa]").val();
        if(idEmpresa == "" || idEmpresa == null || idEmpresa == "null") {
            idEmpresa = 0;
        }
        
        $.get('{{ url("/ticketSoporte/rptUsuarioCliente")}}/1/'+ fechaInicio+ '/'+ fechaFin + '/' + idEmpresa,function(data)
        {
            analytics = data.grafica;
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            if ($.fn.DataTable.isDataTable('#ticket_table')) {
                $('#ticket_table').DataTable().destroy();
            }
            $('#ticket_table > tbody').html("");
            $.each(data.reporte,function(index, result) {
                var html = "<tr>"
                                + "<td>" + result.usuario +"</td>"
                                + "<td>" + result.empresa +"</td>"
                                + "<td>"+ result.cantidad + "</td>"
                            +"</tr>";
                $('#ticket_table > tbody:last-child').append(html);
            });
            $("#ticket_table").DataTable({
                "language": {
                    "url": "{{ asset('js/Spanish.json') }}"
                },
                "order": [[ 0, "asc" ]],
                // "ordering": false, deshabilitar ordenamiento
                "dom": 'Bfrtip',
                "buttons": [
                    'csv', 'excel', 'pdf'
                ]
            });
        });
    };

    function drawChart()
    {
        var data = google.visualization.arrayToDataTable(analytics);
        var options = {
            title : 'Usuario x cantidad de tickets',
            width:900,
            height:500
        };
        var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
        google.visualization.events.addListener(chart, 'ready', function () {
            imgUri = chart.getImageURI();
        });
        chart.draw(data, options);
    }
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop