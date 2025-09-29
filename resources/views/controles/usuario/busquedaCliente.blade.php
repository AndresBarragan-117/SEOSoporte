<div class="row">
	<div class="col controlBusqueda{{ $name }}" >  
		<label for="categoria"><b>Cliente</b></label>
		<div class="input-group">
			<input type="text" name="inputText{{ $name }}" class="form-control" readonly="true" value="{{ old('inputText' . $name) }}">
			<div class="input-group-append">
				<button class="btn btn-outline-primary" type="button" onclick="abrirModal('#modal{{ $name }}')">
					<span class="fa fa-search"></span>
				</button>
				<button class="btn btn-outline-danger" type="button" onclick="limpiarControlBusquedaCliente('{{ $name }}')">
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
								<label for="txtBusquedaCliente" class="form-control-label">Búsqueda</label>
								<input type="text" class="form-control" id="txtBusquedaCliente" placeholder="Ingrese el usuario o nombre de la empresa...">
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6 col-sm-8">
							<button id="btnBusquedaCliente" type="button" class="btn btn-success">
								<span class="glyphicon glyphicon-search">Buscar</span>
							</button>
						</div>
					</div>
				</div>
				<br>
				<div class="row" id="contentTablaCliente" style="padding: 10px"></div>	
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
		  	$("#txtBusquedaCliente").val("");
			consultarCliente();
		});
		//consultarCliente();
		$("#btnBusquedaCliente").click(function()
		{
			consultarCliente()
		});

		// Ejecutar función cuando se presione la tecla intro.
		$("#txtBusquedaCliente").keypress(function (e) {
			if (e.keyCode == 13) {
				consultarCliente();
				return false;
			}
		});
	});

	function consultarCliente()
	{
		$.ajax(
		{
			"dataType": 'json',
			"type": "POST",
			"url": "{{ url('/usuario/busqueda') }}",
			"data": {
				'nombre': $("#txtBusquedaCliente").val()
			},
			"success": function (response) {
				$("#contentTablaCliente").empty();
				$("#contentTablaCliente").html(response.html);
			}
		});
	}
	function MontarCliente(data)
	{
		$(".controlBusqueda{{ $name }} input:text").val(data.nombre + ' - ' + data.razonSocial);
		$(".controlBusqueda{{ $name }} input:hidden").val(data.idEmpresaClienteUsuario);
		cerrarModal('#modal{{ $name }}');
		$("#txtBusquedaCliente").val("");
		$("#contentTablaCliente").empty();
	}
	function limpiarControlBusquedaCliente()
	{
		$(".controlBusqueda{{ $name }} input:text").val("");
		$(".controlBusqueda{{ $name }} input:hidden").val("");

	}
</script>
@stop