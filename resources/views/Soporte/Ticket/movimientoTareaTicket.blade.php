<!-- Modal -->
<div id="modalMovimientoTareaTicket" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title" id="lblTitulo"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="divCalificacionFinal" style="display: none">
					<label class="lblEstrellaCalif" id="star1">★</label>
					<label class="lblEstrellaCalif" id="star2">★</label>
					<label class="lblEstrellaCalif" id="star3">★</label>
					<label class="lblEstrellaCalif" id="star4">★</label>
					<label class="lblEstrellaCalif" id="star5">★</label>
				</div>
				<div id="divFinalizado" style="display: none">
					<div class="row">
						<div class="col-md-2">
							<label for="nombre">Calificación: </label>
							<div class="clasificacion">
									<input class="rdEstrella" id="radio1" type="radio" name="estrellas" value="5"><!--
								--><label class="lblEstrella" for="radio1">★</label><!--
								--><input class="rdEstrella" id="radio2" type="radio" name="estrellas" value="4"><!--
								--><label class="lblEstrella" for="radio2">★</label><!--
								--><input class="rdEstrella" id="radio3" type="radio" name="estrellas" value="3"><!--
								--><label class="lblEstrella" for="radio3">★</label><!--
								--><input class="rdEstrella" id="radio4" type="radio" name="estrellas" value="2"><!--
								--><label class="lblEstrella" for="radio4">★</label><!--
								--><input class="rdEstrella" id="radio5" type="radio" name="estrellas" value="1"><!--
								--><label class="lblEstrella" for="radio5">★</label>
							</div>
						</div>
						<div class="col-md-6">
							<label for="chbNoSolucionado" style="font-weight: bold; color: red;">No Solucionado</label>
							<input type="checkbox" id="chbNoSolucionado">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label for="nombre">Comentario:</label>
							<input name="comentario" id="comentario" class="form-control" value="" />
							<br>
							<a href="javascript:guardar()" id='btnGuardar' class="btn btn-info"><span style="color: white" class="fa fa-save" title="Guardar"> Guardar</span></a>
						</div>
					</div>
					<br>
				</div>
				<div id="divContenido">
					
				</div>
			</div>
		</div>
	</div>
</div>

@section('script')
@parent
<script type="text/javascript" charset="utf-8">
	var calificacion = 0;
	$(function()
	{
		$('#modalMovimientoTareaTicket').on('hidden.bs.modal', function () {
			limpiarCalificacion();
			$("#lblTitulo").html("");
			$("#divContenido").html("");
			$("#divCalificacionFinal").hide();
			$("#divFinalizado").hide();
			if(calificacion > 0) {
				location.reload();
			}
			calificacion = 0;
		});

		$( '.rdEstrella').on('change',function() {
			calificacion = $(this).val();
		});
	});

	function guardar() {
		var guid = $("#lblTitulo").html();
		var comentario = $("#comentario").val();

		if((calificacion > 0 || $("#chbNoSolucionado").prop('checked') == true)) {
			if($("#chbNoSolucionado").prop('checked') == true && (comentario == null || comentario == "" || comentario === undefined)) {
				$("#comentario").focus();
				toastr.error("Ingrese un comentario.");
				return;
			}

			$.ajax(
			{
				"dataType": 'json',
				"type": "POST",
				"url": "{{ url('/ticket/calificarTicket') }}",
				"data": {
					'guid': guid,
					'calificacion': calificacion,
					'comentario': comentario,
					'noSolucionado': ($("#chbNoSolucionado").prop('checked') == true ? 1 : 0)
				},
				"success": function (response) {
					if(response.estado) {
						$("#divFinalizado").hide();
						if($("#chbNoSolucionado").prop('checked') == true)
						{
							$("#divCalificacionFinal").hide();
							toastr.success('La solución fue rechazada correctamente.');
						} else {
							$("#divCalificacionFinal").show();
							cargarCalificacion(calificacion);
							toastr.success('La calificación del Ticket se guardado correctamente.');
						}
						//calificacion = 0;
						//location.reload();
					} else {
						toastr.error(response.mensaje);
					}
				}
			});
		} else {
			toastr.warning('Seleccione la calificación del Ticket.');
		}
	}
</script>
@stop