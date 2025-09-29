@extends('layouts.masterForm',["widthh"=>12])

@section('titulo', 'Todos los Soportes')
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
            <a href="{{ url('ticketSoporte') }}" class="btn btn-info"><span style="color: white" class="fa fa-file" title="Nuevo Ticket"> Nuevo Ticket</span></a>
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
                <div class="col-md-2">
                    <label for="ticketEstado"><b>Estado del Ticket</b></label>
                    <select id="ticketEstado" name="ticketEstado" class="form-control">
                        <option value="">--Seleccione--</option>
                        @foreach($ticketEstado as $p)
                            @if (old('ticketEstado') == $p->idTicketEstado)
                                <option value="{{$p->idTicketEstado}}" selected> {{ $p->nombre}}</option>
                            @else
                                <option value="{{$p->idTicketEstado}}"> {{ $p->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="text-danger">{!!$errors->first('ticketEstado', '<small>:message</small>')!!}</div>
                </div>
                <div class="col-md-2">
                    <label for="categoria"><b>Categoría</b></label>
                    <select id="categoria" name="categoria" class="form-control">
                        <option value="">--Seleccione--</option>
                        @foreach($categoria as $p)
                            @if (old('categoria') == $p->idCategoria)
                                <option value="{{$p->idCategoria}}" selected> {{ $p->nombre}}</option>
                            @else
                                <option value="{{$p->idCategoria}}"> {{ $p->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="text-danger">{!!$errors->first('categoria', '<small>:message</small>')!!}</div>
                </div>
                <div class="col-md-4">
                    <label for="ticketPrioridad"><b>Prioridad del Ticket</b></label>
                    <select id="ticketPrioridad" name="ticketPrioridad" class="form-control">
                        <option value="">--Seleccione--</option>
                        @foreach($ticketPrioridad as $p)
                            @if (old('ticketPrioridad') == $p->idTicketPrioridad)
                                <option value="{{$p->idTicketPrioridad}}" selected> {{ $p->nombre}}</option>
                            @else
                                <option value="{{$p->idTicketPrioridad}}"> {{ $p->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="text-danger">{!!$errors->first('ticketPrioridad', '<small>:message</small>')!!}</div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    @include("controles.empresa.busquedaEmpresa",["name"=> "empresa", "titulo"=> "Búsqueda Empresa"])
                </div>
                <div class="col">
                    @include("controles.usuario.busquedaCliente",["name"=> "cliente", "titulo"=> "Búsqueda Cliente"])
                </div>
                <div class="col">
                    @include("controles.funcionario.busquedaFuncionario",["name"=> "funcionario", "titulo"=> "Búsqueda Funcionario", "validar"=>"false"])
                </div>
            </div>
        </div>
        <br>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="tabForm">
            <li class="nav-item"><a class="nav-link active" role="tab" href="#form" aria-controls="home" role="tab" data-toggle="tab">Listado</a></li>
            <li class="nav-item"><a class="nav-link" role="tab" href="#detalle" aria-controls="consulta" role="tab" data-toggle="tab">Detalle</a></li>
            <li class="nav-item"><a style="display: none;" class="nav-link" role="tab" id="navAccion" href="#cambiarAtencion" aria-controls="consulta" role="tab" data-toggle="tab">Cambiar</a></li>
        </ul>
        <div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
                <br>
                <div class="row">
                    @if(isset($data))
                        <table id="ticket_table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>GUID</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th>Ciudad</th>
                                    <th>Categoría</th>
                                    <th>Problema</th>
                                    <th>Atendido Por</th>
                                    @if (in_array('CAMBIARATENCION', $acciones) || in_array('CAMBIARPRIORIDADTICKET', $acciones))
                                    <th>Acción</th>
                                    @endif
                                    <th>Anexos</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{--@foreach($data as $dt)
                                <tr>
                                    <td>{{$dt->estado}}</td>
                                    <td><a href="javascript:cargarDetalle({{$dt->idTicket}}, '{{$dt->guid}}', '{{$dt->razonSocial}}', '{{$dt->contacto}}', '{{$dt->fechasolicitud}}', '{{$dt->estado}}')">{{$dt->guid}}</a></td>
                                    <td>{{$dt->fechasolicitud}}</td>
                                    <td>{{ $dt->razonSocial }}</td>
                                    <td>{{$dt->contacto}}</td>
                                    <td>{{$dt->ciudad}}</td>
                                    <td>{{$dt->categoria}}</td>
                                    <td>{{ strip_tags($dt->asunto)}}</td>
                                    <td>{{ $dt->usuarioSoporte }}</td>
                                    @if (in_array('CAMBIARATENCION', $acciones))
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if($dt->estado != "Finalizado" && $dt->estado != "Archivado")
                                                <a href="javascript:cambiarAtencion({{$dt->idTicket}}, '{{$dt->guid}}')" class="btn btn-success btn-xs"><span class="fas fa-headset"></span></a>
                                            @endif
                                        </td>
                                    @endif
                                    <td><a href="javascript:cargarAnexoTicket({{$dt->idTicket}})">Anexo</a></td>
                                </tr>
                                @endforeach--}}
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="detalle">
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <label for="guidInf"><b>Guid</b></label>
                        <input type="text" disabled id="guidInf" name="guidInf" id="guidInf" class="form-control" value="" />
                    </div>
                    <div class="col-md-2">
                        <label for="empresaInf"><b>Empresa</b></label>
                        <input type="text" disabled name="empresaInf" id="empresaInf" class="form-control" value="" />
                    </div>
                    <div class="col-md-2">
                        <label for="clienteInf"><b>Contacto</b></label>
                        <input type="text" disabled id="clienteInf" name="clienteInf" class="form-control" value="" />
                    </div>
                    <div class="col-md-4">
                        <label for="fechaSolicitudInf"><b>Fecha Solicitud</b></label>
                        <input type="text" disabled id="fechaSolicitudInf" name="fechaSolicitudInf" class="form-control" value="" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2">
                        <a id="btnNuevaTarea" style="display: none;" title="Nueva Tarea" href="javascript:nuevaTarea(1, 0)" class="btn btn-success"><span class="fa fa-search" title="Nueva Tarea"> Nueva Tarea</span></a>
                    </div>
                </div>
                <br>
                @include("Soporte.TicketTarea.nuevaTarea")
                <div class="row">
                    <table id="tblItems" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Atendido Por</th>
                                <th>Fecha Inicio</th>
                                <th>Detalle</th>
                                <th>Fecha Finalización</th>
                                <th>Solución</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="cambiarAtencion">
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <label for="guid"><b>Guid</b></label>
                        <input type="text" disabled id="guid" name="guid" id="guid" class="form-control" value="" />
                    </div>
                </div>
                <br>
                <div class="row" id="rowFuncionario">
                    <div class="col-md-4">
                    @include("controles.funcionario.busquedaFuncionario",["name"=> "funcionarioCambiarAtencion", "titulo"=> "Búsqueda Funcionario"])
                    </div>
                </div>
                <div class="row" id="rowPrioridadTicket">
                    <div class="col-md-4">
                        <label for="ticketPrioridadMod"><b>Prioridad del Ticket</b></label>
                        <select id="ticketPrioridadMod" name="ticketPrioridadMod" class="form-control">
                            <option value="">--Seleccione--</option>
                            @foreach($ticketPrioridad as $p)
                               <option value="{{$p->idTicketPrioridad}}"> {{ $p->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <a id="btnGuardarAtencion" title="Guardar" href="javascript:guardarCambiarAtencion()" class="btn btn-success"><span class="fas fa-save" title="Guardar"></span> Guardar</a>
                    </div>
                </div>
            </div>
        </div>
        @include("Soporte.Ticket.modalMostrarAnexo")
</div>
@stop

@section('script')
<script type="text/javascript" charset="utf-8" >
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
                $('a[data-toggle="tab"]:last').hide();
            }
        });
        consultar();
    });

    function guardarCambiarAtencion()
    {
        var accion = $("#navAccion").html();
        var guid = $("#guid").val();
        if(accion == "Cambiar Atención") {
            var idFuncionario = $("input[name=funcionarioCambiarAtencion]").val();
            if(idFuncionario == "" || idFuncionario == null || idFuncionario == "null") {
                idFuncionario = 0;
            }
            if(idFuncionario > 0 && guid != "") {
                $.ajax(
                {
                    "dataType": 'json',
                    "type": "POST",
                    "url": "{{ url('/usuario/cambiarAtencion') }}",
                    "data": {
                        'idFuncionario': idFuncionario,
                        'guid': guid,
                    },
                    "success": function (response) {
                        if(response.estado) {
                            $("#guid").val("")
                            $("input[name=funcionarioCambiarAtencion]").val("0");
                            $("input[name=inputTextfuncionarioCambiarAtencion]").val("");
                            $('#tabForm a[href=\"#form\"]').tab('show');
                            $('a[data-toggle="tab"]:last').hide();
                            toastr.success('La atención del Ticket se ha modificado correctamente.');
                            consultar();
                        } else {
                            alert("Se ha presentado un error al guardar.");
                        }
                    }
                });
            } else {
                alert("Seleccione un usuario.");
            }
        } else {
            var idTicketPrioridad = $("#ticketPrioridadMod").val();
            if(idTicketPrioridad != "" && idTicketPrioridad != null  && guid != "") {
                $.ajax(
                {
                    "dataType": 'json',
                    "type": "POST",
                    "url": "{{ url('/ticketSoporte/cambiarPrioridadTicket') }}",
                    "data": {
                        'idTicketPrioridad': idTicketPrioridad,
                        'guid': guid,
                    },
                    "success": function (response) {
                        if(response.estado) {
                            $("#guid").val("")
                            $("#ticketPrioridadMod").val("");
                            $('#tabForm a[href=\"#form\"]').tab('show');
                            $('a[data-toggle="tab"]:last').hide();
                            toastr.success('La prioridad del Ticket se ha modificado correctamente.');
                            consultar();
                        } else {
                            toastr.error('Se ha presentado un error al guardar.', 'SEOSoporte!');
                        }
                    }
                });
            } else {
                toastr.warning('Seleccione la prioridad del Ticket..');
            }
        }
    }
    
    function consultar()
	{
        var fechaInicio = $("#fechaInicial").val().replaceAll("/", "-");
        var fechaFin = $("#fechaFinal").val().replaceAll("/", "-");

        var idEstadoTicket = $("#ticketEstado").val();
        if(idEstadoTicket == "" || idEstadoTicket == null || idEstadoTicket == "null") {
            idEstadoTicket = 0;
        }

        var idCategoria = $("#categoria").val();
        if(idCategoria == "" || idCategoria == null || idCategoria == "null") {
            idCategoria = 0;
        }

        var idEmpresa = $("input[name=empresa]").val();
        if(idEmpresa == "" || idEmpresa == null || idEmpresa == "null") {
            idEmpresa = 0;
        }
        var idCliente = $("input[name=cliente]").val();
        if(idCliente == "" || idCliente == null || idCliente == "null") {
            idCliente = 0;
        }

        var idFuncionario = $("input[name=funcionario]").val();
        if(idFuncionario == "" || idFuncionario == null || idFuncionario == "null") {
            idFuncionario = 0;
        }

        var idTicketPrioridad = $("#ticketPrioridad").val();
        if(idTicketPrioridad == "" || idTicketPrioridad == null || idTicketPrioridad == "null") {
            idTicketPrioridad = 0;
        }

        var permiso = {!! json_encode($acciones) !!};
        $.get('{{ url("/ticketSoporte/listadoTicketSoporte")}}/'+ fechaInicio+ '/'+ fechaFin + '/' + idEmpresa + '/' + idCliente+ '/' + idEstadoTicket+ '/' + idFuncionario+"/"+idCategoria+"/"+idTicketPrioridad,function(data)
        {
            if ($.fn.DataTable.isDataTable('#ticket_table')) {
                $('#ticket_table').DataTable().destroy();
            }
            $('#ticket_table > tbody').html("");
            $.each(data,function(index, result) {
                var html = "<tr>"
                                + "<td style='font-weight: bold; background-color: "+result.color+"'>" + result.estado +"</td>"
                                + "<td><a href=\"javascript:cargarDetalle("+result.idTicket+", '"+result.guid+"', '"+result.razonSocial+"', '"+result.contacto+"', '"+result.fechasolicitud+"', '"+result.estado+"')\">" + result.guid + "</a></td>" 
                                + "<td>"+ result.fechasolicitud + "</td>"
                                + "<td>" +result.razonSocial +"</td>"
                                + "<td>" +result.contacto +"</td>"
                                + "<td>" +result.ciudad +"</td>"
                                + "<td>" +result.categoria +"</td>"
                                + "<td>" +result.asunto.replace( /<.*?>/g, '' ) +"</td>"
                                + "<td>" +result.usuarioSoporte +"</td>"
                                + ((permiso.includes('CAMBIARATENCION') || permiso.includes('CAMBIARPRIORIDADTICKET')) ?
                                        ("<td>"+"<div style=\"display: flex;justify-content: space-between;\">"+
                                             (result.estado != {!! json_encode($parametroDefectoFinalizar->nombre) !!} && result.estado != {!! json_encode($parametroDefectoArchivado->nombre) !!} && permiso.includes('CAMBIARATENCION') ? ("<a data-toggle=\"tooltip\" data-placement=\"top\" title=\"Cambiar Atención\" href=\"javascript:accion('atencion',"+result.idTicket+", '"+result.guid+"')\" class=\"btn btn-success btn-xs\"><span class=\"fas fa-headset\"></span></a>") : "") 
                                              + (result.estado != {!! json_encode($parametroDefectoFinalizar->nombre) !!} && result.estado != {!! json_encode($parametroDefectoArchivado->nombre) !!} && permiso.includes('CAMBIARPRIORIDADTICKET') ? ("<a style=\"flex: 1;margin-left: 2px;\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Cambiar Prioridad\" href=\"javascript:accion('prioridad',"+result.idTicket+", '"+result.guid+"')\" class=\"btn btn-success btn-xs\"><span class=\"fas fa-exclamation-circle\"></span></a>") : "") 
                                             + "</div></td>") 
                                    : "")
                                + "<td><a href=\"javascript:cargarAnexoTicket("+result.idTicket+")\">Anexo</a></td>"
                            +"</tr>";
                $('#ticket_table > tbody:last-child').append(html);
            });
            $('[data-toggle="tooltip"]').tooltip();
            $("#ticket_table").DataTable({
                "language": {
                    "url": "{{ asset('js/Spanish.json') }}"
                },
                "order": [[ 2, "desc" ]],
                // "ordering": false, deshabilitar ordenamiento
                "dom": 'Bfrtip',
                "buttons": [
                    'csv', 'excel', 'pdf'
                ]
            });
        });
    };
    
    function ajaxListadoDetalle(idTicket) {
        $.get('{{ url("/ticketSoporte/listadoTicketTarea")}}/'+ idTicket,function(data)
        {
            var contador = 0;
            $('#tblItems > tbody').html("");
            $.each(data,function(index, result) {
                $('#tblItems > tbody:last-child').append("<tr>"
                                + "<td>" + result.funcionario +"</td>"
                                + "<td>" + result.fechaInicio +"</td>"
                                + "<td>" + result.contenidoInicio.replace( /<.*?>/g, '' ) + "</td>"
                                + "<td>" +(result.fechaFin != null ? result.fechaFin : "") +"</td>"
                                + "<td>" + (result.contenidoFin != null ? result.contenidoFin.replace( /<.*?>/g, '' ) : "") +"</td>"
                                + "<td>" + (result.contenidoFin == null ? "<a href=\"javascript:nuevaTarea(0, "+result.idTicketTarea+")\" class=\"btn btn-success btn-xs\"><span class=\"fas fa-edit\"></span></a>" : "")+"</td>"
                            +"</tr>");
                contador++;
            });
        });
    }

    function cargarDetalle(id, guid, empresa, cliente, fechaSolicitud, estado)
    {
        $("#guidInf").val(guid);
        $("#empresaInf").val(empresa);
        $("#clienteInf").val(cliente);
        $("#fechaSolicitudInf").val(fechaSolicitud);
        $('#tabForm a[href=\"#detalle\"]').tab('show');
        if(estado != {!! json_encode($parametroDefectoFinalizar->nombre) !!} && estado != {!! json_encode($parametroDefectoArchivado->nombre) !!}) {
            $("#btnNuevaTarea").show();
        } else {
            $("#btnNuevaTarea").hide();
        }
        ajaxListadoDetalle(id);
    }

    function accion(ac, id, guid) {
        $('a[data-toggle="tab"]:last').show();
        $('#tabForm a[href=\"#cambiarAtencion\"]').tab('show');
        $("#guid").val(guid);
        if(ac == "atencion") {
            $("#navAccion").html("Cambiar Atención");
            $("#rowFuncionario").show();
            $("#rowPrioridadTicket").hide();
        } else if(ac == "prioridad") {
            $("#navAccion").html("Cambiar Prioridad Ticket");
            $("#rowPrioridadTicket").show();
            $("#rowFuncionario").hide();
        }
    }

    function nuevaTarea(contenidoInicio, idTicketTarea) {
        if(contenidoInicio == 1) {
            $("#lblTitulo").html("Iniciar Tarea");
            cargarEstadoTarea(false);
        } else {
            $("#lblTitulo").html("Terminar Tarea");
            cargarEstadoTarea(true);
        }
        $("#hiddenContenidoInicio").val(contenidoInicio);
        $("#hiddenTicketTarea").val(idTicketTarea);
        $("#lblGuid").html(""+ $("#guidInf").val() +"");
        abrirModal('#modalNuevaTarea');
    }
</script>
<!--<script type="text/javascript" src=" asset('js/download.js')"></script> -->
@stop