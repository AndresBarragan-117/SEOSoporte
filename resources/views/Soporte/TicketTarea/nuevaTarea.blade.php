<!-- Modal -->
<div id="modalNuevaTarea" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title" id="lblTitulo"></h5>
                <h5 style="line-height: 1.6;">- <label class="marker" id="lblGuid" for="guidModal"></label></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label id="lblContenido" for="txtContenido" class="form-control-label"></label>
								<textarea rows="10" id="txtContenido" name="txtContenido" maxlength="500" class="form-control ckeditor"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="estadoTarea" id="lblEstadoTarea" class="form-control-label">Estado del Ticket</label>
								<select id="estadoTarea" name="estadoTarea" class="form-control"></select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-4 col-md-4 col-sm-4">
							<button id="btnGuardarTarea" type="button" class="btn btn-success">
								<span class="glyphicon glyphicon-search">Guardar</span>
							</button>
						</div>
					</div>
				</div>
				<br>
                <div class="row" id="contentTablaFuncionario" style="padding: 10px"></div>
                <input type="hidden" name="hiddenContenidoInicio" id="hiddenContenidoInicio" value="1" />
                <input type="hidden" name="hiddenTicketTarea" id="hiddenTicketTarea" value="0" />
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
		//cargarEstadoTarea();
		$('#modalNuevaTarea').on('hidden.bs.modal', function () {
              CKEDITOR.instances['txtContenido'].setData('');
		});
		
		$("#btnGuardarTarea").click(function()
		{
			guardarTarea();
		});
	});

	function cargarEstadoTarea(finalizar) {
		$.get("{{ url('/ticketSoporte/ticketEstadoTarea')}}",function(data){
			if(finalizar) {
				$("#estadoTarea").show();
				$("#lblEstadoTarea").show();
			} else {
				$("#estadoTarea").hide();
				$("#lblEstadoTarea").hide();
			}
			$("#estadoTarea").html("");
			$("#estadoTarea").append('<option value="0">--Seleccione--</option>');
			$.each(data,function(index, result) {
				$("#estadoTarea").append("<option value="+result.idTicketEstado+">"+result.nombre+"</option>");
			});
		});
	}

	function guardarTarea()
	{
        var content = CKEDITOR.instances['txtContenido'].getData();
        var element = $(content);     //convert string to JQuery element
        element.find("div").remove();        //remove div elements
        var newString = element.html();   //get back new string
        if(newString == '' || newString === undefined){
            alert("Ingrese el contenido.");
        } else {
            $.ajax(
            {
                "dataType": 'json',
                "type": "POST",
                "url": "{{ url('/ticketSoporte/nuevaTarea') }}",
                "data": {
                    'hiddenContenidoInicio': $("#hiddenContenidoInicio").val(),
                    'hiddenTicketTarea' : $("#hiddenTicketTarea").val(),
                    'guid' : $.trim($("#lblGuid").html()),
					'idTicketEstado': $("#estadoTarea").val(),
                    'contenido' : content
                },
                "success": function (response) {
                    if(response.estado) {
                        $("#hiddenContenidoInicio").val("1");
                        $("#hiddenTicketTarea").val("0");
                        $("#lblGuid").html("<b>Guid</b>");
                        cerrarModal('#modalNuevaTarea');
                        ajaxListadoDetalle(response.idTicket);
                    } else {
                        alert(response.mensaje);
                    }
                }
            });
        }
	}
	/*function MontarFuncionario(data, control)
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

	}*/
</script>
@stop