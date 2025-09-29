<div class="row">
	<div class="col controlBusqueda{{ $name }}" >  
		<label for="empresa"><b>Empresa</b></label>
		<div class="input-group">
			<input type="text" name="inputText{{ $name }}" class="form-control" readonly="true" value="{{ old('inputText' . $name) }}">
			<div class="input-group-append">
				<button class="btn btn-outline-primary" type="button" onclick="abrirModal('#modal{{ $name }}')">
					<span class="fa fa-search"></span>
				</button>
				<button class="btn btn-outline-danger" type="button" onclick="limpiarControlBusqueda('{{ $name }}')">
					<span class="fa fa-eraser"></span>
				</button>
			</div>
		</div>
		<input type="hidden" value="{{ old($name) }}" name="{{ $name }}">	
		<div class="text-danger">{{$errors->error->first($name)}}</div>
	</div>
</div>

<!-- Modal -->
<div id="modal{{ $name }}" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{ $titulo }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-md-5 col-sm-8">
							<div class="form-group">
								<label for="txtBusquedaEmpresa" class="form-control-label">Búsqueda</label>
								<input type="text" class="form-control" id="txtBusquedaEmpresa" placeholder="Ingrese el nombre de la empresa...">
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6 col-sm-8">
							<button id="btnBusquedaEmpresa" type="button" class="btn btn-success">
								<span class="glyphicon glyphicon-search">Buscar</span>
							</button>
						</div>
					</div>
				</div>
				<br>
				<div class="row" id="contentTablaEmpresa" style="padding: 10px"></div>	
			</div>
		</div>
		<!--<div class="modal-footer"> -->
			<!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
		<!--</div>-->
	</div>
</div>

@section('script')
@parent
<script type="text/javascript" charset="utf-8">
	$(function()
	{
		$('#modal{{ $name }}').on('hidden.bs.modal', function () {
		  	$("#txtBusquedaEmpresa").val("");
			consultarEmpresa();
		});
		
		$("#btnBusquedaEmpresa").click(function()
		{
			consultarEmpresa()
		});

		// Ejecutar función cuando se presione la tecla intro.
		$("#txtBusquedaEmpresa").keypress(function (e) {
			if (e.keyCode == 13) {
				consultarEmpresa();
				return false;
			}
		});
	});

	function consultarEmpresa()
	{
		$.ajax(
		{
			"dataType": 'json',
			"type": "POST",
			"url": "{{ url('/usuario/busquedaEmpresa') }}",
			"data": {
				'nombre': $("#txtBusquedaEmpresa").val()
			},
			"success": function (response) {
				$("#contentTablaEmpresa").empty();
				$("#contentTablaEmpresa").html(response.html);
			}
		});
	}
	function MontarEmpresa(data)
	{
		$(".controlBusqueda{{ $name }} input:text").val(data.nit + ' - ' + data.razonSocial);
		$(".controlBusqueda{{ $name }} input:hidden").val(data.idEmpresa);
		cerrarModal('#modal{{ $name }}');
		$("#txtBusquedaEmpresa").val("");
		$("#contentTablaEmpresa").empty();
	}
	function limpiarControlBusqueda()
	{
		$(".controlBusqueda{{ $name }} input:text").val("");
		$(".controlBusqueda{{ $name }} input:hidden").val("");

	}
</script>
@stop