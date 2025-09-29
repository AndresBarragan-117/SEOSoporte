<div class="row">
	<div class="col controlBusqueda{{ $name }}" >
		<label for="funcionario"><b>Funcionario</b></label>
		<div class="input-group">
			<input type="text" name="inputText{{ $name }}" class="form-control" readonly="true" value="{{ $nombreFuncionario ?? '' }}">
			<div class="input-group-append">
				@if (isset($validar))
					<button {{ $idFuncionario > 0 ? 'disabled' : '' }} class="btn btn-outline-primary" type="button" onclick="abrirModal('#modal{{ $name }}')">
						<span class="fa fa-search"></span>
					</button>
					<button {{ $idFuncionario > 0 ? 'disabled' : '' }} class="btn btn-outline-danger" type="button" onclick="limpiarControlBusquedaFuncionario('{{ $name }}')">
						<span class="fa fa-eraser"></span>
					</button>
				@else
					<button class="btn btn-outline-primary" type="button" onclick="abrirModal('#modal{{ $name }}')">
						<span class="fa fa-search"></span>
					</button>
					<button class="btn btn-outline-danger" type="button" onclick="limpiarControlBusquedaFuncionario('{{ $name }}')">
						<span class="fa fa-eraser"></span>
					</button>
				@endif
			</div>
		</div>
		<input type="hidden" value="{{ $idFuncionario ?? '0' }}" name="{{ $name }}">
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
								<label for="txtBusquedaFuncionario{{ $name }}" class="form-control-label">Búsqueda</label>
								<input type="text" class="form-control" id="txtBusquedaFuncionario{{ $name }}" placeholder="Ingrese el nombre del funcionario...">
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6 col-sm-8">
							<button id="btnBusquedaFuncionario{{ $name }}" type="button" class="btn btn-success">
								<span class="glyphicon glyphicon-search">Buscar</span>
							</button>
						</div>
					</div>
				</div>
				<br>
				<div class="row" id="contentTablaFuncionario{{ $name }}" style="padding: 10px"></div>	
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
		  	$("#txtBusquedaFuncionario{{ $name }}").val("");
			consultarFuncionario{{ $name }}();
		});
		
		$("#btnBusquedaFuncionario{{ $name }}").click(function()
		{
			consultarFuncionario{{ $name }}()
		});

		// Ejecutar función cuando se presione la tecla intro.
		$("#txtBusquedaFuncionario{{ $name }}").keypress(function (e) {
			if (e.keyCode == 13) {
				consultarFuncionario{{ $name }}();
				return false;
			}
		});
	});

	function consultarFuncionario{{ $name }}()
	{
		$.ajax(
		{
			"dataType": 'json',
			"type": "POST",
			"url": "{{ url('/usuario/busquedaFuncionario') }}",
			"data": {
				'nombre': $("#txtBusquedaFuncionario{{ $name }}").val(),
				'control' : "{{ $name }}"
			},	
			"success": function (response) {
				$("#contentTablaFuncionario{{ $name }}").empty();
				$("#contentTablaFuncionario{{ $name }}").html(response.html);
			}
		});
	}
	function MontarFuncionario(data, control)
	{
		$(".controlBusqueda"+control+" input:text").val(data.nombre);
		$(".controlBusqueda"+control+" input:hidden").val(data.idFuncionario);
		cerrarModal('#modal'+control+'');
		$("#txtBusquedaFuncionario"+control+"").val("");
		$("#contentTablaFuncionario"+control+"").empty();
	}
	function limpiarControlBusquedaFuncionario(name)
	{
		$(".controlBusqueda"+name+" input:text").val("");
		$(".controlBusqueda"+name+" input:hidden").val("");

	}
</script>
@stop