@extends('layouts.masterForm',["widthh"=>12])

@section('titulo', 'Registrar Implementación Empresa')
@section('cuerpo')
<div>
	{{ csrf_field() }}
	<!-- Acciones(COnsultar, Guardar, Nuevo) -->
	<div style="margin-left:6px;margin-bottom: 12px;">
		<a href="{{ url('implementacionEmpresa') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
		<a href="javascript:consultar()" class="btn btn-info" title="Consultar"><span class=" fa fa-search" ></span></a>
		<a href="javascript:guardar()" class="btn btn-info" title="Guardar"><span class="fa fa-save"></span></a>

	</div>
	<div class="row">
		<div class="col-md-4">
			<label for="listaEmpresa">Empresa</label>
			<select name="listaEmpresa" id="listaEmpresa" class="form-control">
				<option value="">--Seleccione--</option>
				@foreach($listaEmpresa as $p)
					<option value="{{$p->idEmpresa}}"> {{ $p->razonSocial}}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<label for="listadoTipoPlantilla">Tipo Plantilla</label>
			<select name="listadoTipoPlantilla" id="listadoTipoPlantilla" class="form-control">
				<option value="">--Seleccione--</option>
				@foreach($listadoTipoPlantilla as $p)
					@if (old('listadoTipoPlantilla') == $p->idTipoPlantilla)
						<option value="{{$p->idTipoPlantilla}}" selected> {{ $p->nombre}}</option>
					@else
						<option value="{{$p->idTipoPlantilla}}"> {{ $p->nombre}}</option>
					@endif
				@endforeach
				
			</select>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col">
			<ul class="nav nav-tabs" role="tablist" id="tabForm"></ul>
			<div class="tab-content" id="tabContent"></div>
		</div>
	</div>
</div>

@stop

@section('script')
<script type="text/javascript" charset="utf-8">
	var plantillas = null;
	$(function () {
		//cargarTabs();
		/*$( "#listaEmpresa" ).change(function() {
			if(this.value != '') {
				cargarContenido();
				$('#a0').click();
			}
		}); Deshabilito evento change*/
	});

	function consultar() {
		if($("#listaEmpresa").val() != '' && $("#listadoTipoPlantilla").val() != ''){
			cargarTabs();
		} else {
			if($("#listaEmpresa").val() == '') {
				toastr.error("Seleccione la empresa");
			}
			if($("#listadoTipoPlantilla").val() == '') {
				toastr.error("Seleccione el Tipo de Plantilla");
			}
		}
	}
	
	function cargarTabs() {
		$.get('{{ url("/plantilla/obtenerPlantilla") }}' + "/" + $("#listadoTipoPlantilla").val(),function(data)
        {
			plantillas = data;
            $('#tabForm').html("");
			$('#tabContent').html("");
            $.each(data.plantillas,function(index, result) { // Cargar solos las pestanias (Tab)
				$("#tabForm").append('<li class="nav-item" id="'+result.idPlantilla+'"><a id="a'+index+'" class="nav-link '+(index == 0 ? 'active' : '')+'" role="tab" href="#tab'+result.idPlantilla+'" aria-controls="tab'+result.idPlantilla+'" role="tab" data-toggle="tab">'+result.nombre+'</a></li>');
            });
			cargarContenido();
			$('#a0').click();
        });
	}

	function cargarContenido() {
		var indexx = 0;
		$("#tabContent").html('');
		$.each(plantillas.plantillas,function(index, result) { // Cargar el contenido del tab 
			var htmlDiv = '<div role="tabpanel" class="tab-pane '+(indexx == 0 ? 'active' : '')+'" id="tab'+result.idPlantilla+'">';
			var texto = cargarPlantillaLista($("#listaEmpresa").val(), result.idPlantilla);
			htmlDiv += texto;
			htmlDiv += "</div>"
			$("#tabContent").append(htmlDiv);
			indexx++;
		});
		campoFecha();
	}

	function cargarPlantillaLista(idEmpresa, idPlantilla) {
		var html = '';
		$.ajax({
			async: false,
			"dataType": 'json',
			"type": "GET",
			"url": "{{ url('/plantillaLista/cargarPlantillaLista') }}/" + idEmpresa + '/' + idPlantilla,
			"data": null,
			"success": function (response) {
				html += '<table class="table"><thead><tr><th scope="col">#</th><th scope="col">Descripción</th><th scope="col">Si/No</th><th scope="col">Fecha</th><th scope="col">Observación</th></thead><tbody>';
				$.each(response.plantillaLista,function(index, result) {
					html += '<tr id="' + result.idPlantillaLista + '">';
					html += '<td>'+result.numeroOrdenLista+'</td>';
					if(result.codigoTipoCaptura == "Texto") {
						html += '<td colspan="4">'+result.descripcion+'</td>';
					} else {
						html += '<td style="width: 250px;">'+result.descripcion+'</td>';
						html += '<td>'+crearControl(result.codigoTipoCaptura, result.opcionTipoCaptura, result.valor)+'</td>';
						html += '<td>'+crearControl("Fecha", null, result.fechaRealiza)+'</td>';
						html += '<td>'+crearControl("TextoInput", null, result.observacion)+'</td>';
					}
					html += "</tr>";
				});
				html += "</tbody></table>";
			}
		});
		return html;
	}

	function crearControl(codigoTipoCaptura, opcionTipoCaptura, valorDefecto) {
		var htmlControl = "";
		switch (codigoTipoCaptura) {
			case "Lista":
				htmlControl += '<select name="listaEmpresa" id="listaEmpresa" class="form-control">';
				htmlControl += '<option value="">--Seleccione--</option>';
				var spl = opcionTipoCaptura.split(',');
				for(var i = 0; i < spl.length; i++) {
					if(valorDefecto != null && valorDefecto == spl[i]) {
						htmlControl += '<option value="'+spl[i]+'" selected>'+spl[i]+'</option>';
					} else {
						htmlControl += '<option value="'+spl[i]+'">'+spl[i]+'</option>';
					}
				}
				htmlControl += '</select>';
				break;
			case "Fecha":
				if(valorDefecto != null) {
					htmlControl += '<input id="fecha" class="form-control datepicker" value="'+  valorDefecto + '"></input>';	
				} else {
					htmlControl += '<input id="fecha" class="form-control datepicker" value="{{Carbon\Carbon::now()->format('d/m/Y')}}"></input>';
				}
				break;
			case "TextoInput":
				if(valorDefecto != null) {
					htmlControl += '<input type="text" name="txt" id="comentario" class="form-control" value="' + valorDefecto + '" />';
				} else {
					htmlControl += '<input type="text" name="txt" id="comentario" class="form-control" />';
				}
				break;
		}
		return htmlControl;
	}

	function guardar() {
		var current_tab = $("#tabForm > li > a.active").attr('href');
		var idPlantilla = current_tab.replace('#tab', '');
		var objs = [];
		$(current_tab+' > table > tbody  > tr').each(function(index, tr) {
			var valorSelect = $(this).find('select');
			if(valorSelect.val() != undefined && valorSelect.val() != "undefined" && valorSelect.val() != "") { // Se valida que no este vacio.
				var inputs = $(this).find('input'),o={};
				//o["idEmpresa"] = $("#listaEmpresa").val();
				o["idPlantilla"] = idPlantilla;
				o["idPlantillaLista"] = tr.id;
				o["valor"] = valorSelect.val();
				inputs.each(function(){
					o[$(this).attr('id')] = this.value;
				});
				objs.push(o);
			}
		});
		console.log(objs);
		guardarAjax(objs);
	}
	
	function guardarAjax(objs) {
		$.ajax({
			async: false,
			"dataType": 'json',
			"type": "POST",
			"url": "{{ url('/implementacionEmpresa/guardar') }}",
			"data": {
				"idEmpresa" : $("#listaEmpresa").val(),
				"listadoResult" : JSON.stringify(objs)
			},
			"success": function (response) {
				if(response.exito) {
					toastr.success('La información se ha guardado correctamente.');
				} else {
					toastr.error(response.mensaje);
				}
			}
		});	
	}
</script>
@stop
