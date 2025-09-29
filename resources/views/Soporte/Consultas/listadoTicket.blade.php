@extends('layouts.masterForm',["widthh"=>12])

@section('titulo', 'Listado Ticket')
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
			<a href="{{ url('ticket') }}" class="btn btn-info"><span style="color: white" class="fa fa-file" title="Nuevo Ticket"> Nuevo Ticket</span></a>
		</div>
        <br>
        <div class="row">
            @if(isset($data))
                <table id="ticket_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>GUID</th>
                            <th>Fecha Solicitud</th>
                            <th>Contacto</th>
                            <th>Problema</th>
                            <th>Movimientos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $dt)
                        <tr>
                            <td style="font-weight: bold; background-color: {{$dt->color}}">{{$dt->estado}}</td>
                            <td>{{$dt->guid}}</td>
                            <td>{{$dt->fechasolicitud}}</td>
                            <td>{{$dt->contacto}}</td>
                            <td>{{ strip_tags($dt->asunto)}}</td>
                            <td style="text-align: center;"><a href="javascript:verMovimiento({{$dt->idTicket}},{{$dt->finalizado}},{{$dt->usuarioClienteRating}}, '{{$dt->guid}}')" class="btn btn-success btn-xs"><span class="fa fa-list"></span></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @include("Soporte.Ticket.movimientoTareaTicket")
</div>
@stop

@section('script')
<script type="text/javascript" charset="utf-8" >
    $(function() {
       $("#ticket_table").DataTable({
            "language": {
                "url": "{{ asset('js/Spanish.json') }}"
            },
            "order": [[ 2, "desc" ]]
       });
    });

    function verMovimiento(idTicket, finalizado, usuarioClienteRating, guid) {
        $("#lblTitulo").html("");
        $("#divContenido").html("");
        abrirModal('#modalMovimientoTareaTicket');

        $.get('{{ url("/ticket/movimientoTareaTicket")}}/'+idTicket,function(data)
        {
            calificacion = 0;
            if(data != null) {
                if(finalizado == 1) { // Estado Terminado
                    $("#divFinalizado").show();
                } else {
                    $("#divFinalizado").hide();
                }

                if(usuarioClienteRating > 0) { // Archivado
                    $("#divCalificacionFinal").show();
                    $("#divFinalizado").hide();
                    cargarCalificacion(usuarioClienteRating);
                } else {
                    $("#divCalificacionFinal").hide();
                }

                var tabla = '<table id="ticket_table" class="table table-bordered table-hover">';
                    tabla += '<thead>';
                        tabla += '<tr>';
                            tabla += '<th>Funcionario</th>';
                            tabla += '<th>Fecha Tarea</th>';
                            tabla += '<th>Contenido Inicio</th>';
                            tabla += '<th>Fecha Solución</th>';
                            tabla += '<th>Solución</th>';
                        tabla += '</tr>';
                    tabla += '</thead>';
                tabla += '<tbody>';
                $.each(data, function (index, row) {
                    tabla += '<tr>';
                        tabla += "<td>"+row.funcionario+"</td>";
                        tabla += "<td>"+row.fechaInicio+"</td>";
                        tabla += "<td>"+row.contenidoInicio+"</td>";
                        tabla += "<td>"+(row.fechaFin != null ? row.fechaFin : '')+"</td>";
                        tabla += "<td>"+(row.contenidoFin != null ? row.contenidoFin : '')+"</td>";
                    tabla += '</tr>';
                });
                tabla += '</tbody>';
                $("#lblTitulo").html(guid);
                $("#divContenido").html(tabla);
            }
        });
    }
</script>
@stop