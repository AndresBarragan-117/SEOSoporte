@extends('layouts.masterForm',["widthh"=>12])

@section('titulo', 'Base de Conocimiento')
@section('cuerpo')
<link rel="stylesheet" href="{{ asset('css/acordeon/style.css') }}">
<div>
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->
        @csrf
        
        
        <div class="row">
            <div class="col-md-2">
                <label for="busqueda"><b>Búsqueda</b></label>
                <input type="text" id="busqueda" name="busqueda" class="form-control" />
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <a id="btnConsultar" title="Consultar" href="javascript:consultar()" class="btn btn-success"><span class="fa fa-search" title="Consultar"> Consultar</span></a>
            </div>
        </div>
        <br>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="tabForm">
            <li class="nav-item"><a class="nav-link active" role="tab" href="#form" aria-controls="home" role="tab" data-toggle="tab">Listado</a></li>
        </ul>
        <div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
                <br>
                <div class="row">
                    <!--Accordion wrapper-->
                    <ul id="acordeon" class="cd-accordion cd-accordion--animated margin-top-lg margin-bottom-lg">
                        
                    </ul>
                    <!-- Accordion wrapper -->
                </div>
            </div>
        </div>
        @include("Soporte.KBArticulo.detalleArticulo")
</div>
@stop

@section('script')
<script type="text/javascript" charset="utf-8" >
    $(function() {
        //consultar();
    });

    
    function consultar()
	{
        var busqueda = $("#busqueda").val();
        if(busqueda == null || busqueda == "undefined" || busqueda == undefined || busqueda == "") {
            busqueda = "empty";
        }
        
        $.get('{{ url("/kbArticulo/listadoBaseConocimiento")}}/'+busqueda+"/1",function(data)
        {
            $('#acordeon').html("");
            var contadorGrupo = 0;
            $.each(data.carpetas,function(index, result) {
                var html = "<li class=\"cd-accordion__item cd-accordion__item--has-children\">" //group
                                + "<input class=\"cd-accordion__input\" type=\"checkbox\" name =\"group-1\" id=\"group-1\">"
                                + "<label class=\"cd-accordion__label cd-accordion__label--icon-folder\" for=\"group-1\"><span>"+result.carpeta+"</span></label>"
                                + "<ul id=\"sub"+contadorGrupo+"\" class=\"cd-accordion__sub cd-accordion__sub--l1\">";
                
                html += obtenerHijos(result.carpeta, data.baseConocimiento);
                
                html += "</ul>";
                html += "</li>"; // group
                contadorGrupo++;
                $('#acordeon').append(html);
            });
        });
    };
    
    function obtenerHijos(carpeta, result) {
        var sub = [];
        var html = "";
        $.each(result,function(index, result2) {
            $.each(result2,function(i, r2) {
                if(carpeta == index) {
                    html += "<li class=\"cd-accordion__item\"><a class=\"cd-accordion__label cd-accordion__label--icon-img\" href=\"javascript:cargarContenidoArticulo("+r2.idKbArticulo+", "+r2.calificado+", "+r2.calificacion+", "+r2.cliente+")\"><span>"+r2.asunto+"</span></a></li>";
                }
            });
        });

        var cont = 0;
        $.each(result, function(index, result2) {
            $.each(result2, function(i, r) {
                if(carpeta == r.padre) {
                    html += "<li id=\""+index+"\" class=\"cd-accordion__item cd-accordion__item--has-children\">";
                    html += "<input class=\"cd-accordion__input\" type=\"checkbox\" name =\"sub-group-"+result2.categoria+"-"+cont+"\" id=\"sub-group-"+result2.categoria+"-"+cont+"\">";
                    html += "<label class=\"cd-accordion__label cd-accordion__label--icon-folder\" for=\"sub-group-"+result2.categoria+"-"+cont+"\"><span>"+index+"</span></label>"; //nombre subCategoria
                    
                    html += "<ul class=\"cd-accordion__sub cd-accordion__sub--l2\">"; //item
                    html += obtenerHijos(index, result);
                    html += "</ul>"; // end item
                    html += "</li>";
                    return false;
                }
            });
            cont++;
        });
        return html;
    }

    function cargarContenidoArticulo(idKbArticulo, calificado, calificacion, cliente)
	{
        $("lblAsunto").html("");
        $("divContenido").html("");
        if(cliente == 1) {
            if(calificado == 0) {
                $("#divFinalizado").show();
            } else {
                $("#divFinalizado").hide();
            }

            if(calificacion > 0) { // Archivado
                $("#divCalificacionFinal").show();
                $("#divFinalizado").hide();
                cargarCalificacion(calificacion);
            } else {
                $("#divCalificacionFinal").hide();
            }
        }
        $("#hidIdArticulo").val(idKbArticulo);
        abrirModal('#modalKbArticulo');
        $.get('{{ url("/kbArticulo/consultarContenidoArticulo")}}/'+idKbArticulo,function(data)
        {
            if(data != null) {
                $("#lblAsunto").html(data.asunto);
                $("#divContenido").html(data.contenido);
            }
        });
	}
</script>
<script src="{{ asset('js/acordeon/util.js')}}"></script> <!-- util functions included in the CodyHouse framework -->
<script src="{{ asset('js/acordeon/main.js')}}"></script>
@stop