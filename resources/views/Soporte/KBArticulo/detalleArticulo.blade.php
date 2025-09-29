<!-- Modal -->
<div id="modalKbArticulo" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title" id="lblAsunto"></h5>
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
						<div class="col-md-4">
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
					</div>
					<br>
				</div>
				<input type="hidden" id="hidIdArticulo" value="0">
				<div id="divContenido">
					
				</div>
			</div>
		</div>
	</div>
</div>

@section('script')
@parent
<script type="text/javascript" charset="utf-8">
	var calif = 0;
	$(function()
	{
		$('#modalKbArticulo').on('hidden.bs.modal', function () {
			if(calif > 0) {
				consultar();
			}
			limpiarCalificacion();
			$("#hidIdArticulo").val("0");
			$("#lblAsunto").html("");
			$("#divContenido").html("");
			$("#divCalificacionFinal").hide();
			$("#divFinalizado").hide();
			calif = 0;
		});

		$( '.rdEstrella').on('change',function() {
			var idKbArticulo = $("#hidIdArticulo").val();
			var calificacion = $(this).val();
			calif = calificacion;
			if(calificacion > 0) {
				$.ajax(
				{
					"dataType": 'json',
					"type": "POST",
					"url": "{{ url('/kbArticulo/calificarArticulo') }}",
					"data": {
						'idKbArticulo': idKbArticulo,
						'calificacion': calificacion
					},
					"success": function (response) {
						if(response.estado) {
							$("#divCalificacionFinal").show();
							$("#divFinalizado").hide();
							cargarCalificacion(calificacion);
							toastr.success('La calificación del Articulo se guardado correctamente.');
							calificacion = 0;
							//$('#modalKbArticulo').modal('toggle'); 
						} else {
							toastr.error(response.mensaje);
						}
					}
				});
			} else {
				toastr.warning('Seleccione la calificación del Articulo.');
			}
		});
	});
</script>
@stop