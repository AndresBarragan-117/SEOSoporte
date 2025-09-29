<!-- Modal -->
<div id="modalAnexoTicket" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
                <h5 class="modal-title">Anexos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="anexos"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@section('script')
@parent
<script type="text/javascript" charset="utf-8">
	$(function()
	{	
		
	});

	/*function saveFile(blob, filename) {
		if (window.navigator.msSaveOrOpenBlob) {
			window.navigator.msSaveOrOpenBlob(blob, filename);
		} else {
			const a = document.createElement('a');
			document.body.appendChild(a);
			const url = window.URL.createObjectURL(blob);
			a.href = url;
			a.download = filename;
			a.click();
			setTimeout(() => {
			window.URL.revokeObjectURL(url);
			document.body.removeChild(a);
			}, 0)
		}
	}

	function download(url, filename, mimeType){
        return (fetch(url)
            .then(function(res){return res.arrayBuffer();})
            .then(function(buf){return new File([buf], filename, {type:mimeType});})
        );
    }*/

	function cargarAnexoTicket(idTicket) {
		abrirModal('#modalAnexoTicket');
		$("#anexos").html("");
		$.ajax(
		{
			"dataType": 'json',
			"type": "POST",
			"url": "{{ url('/ticketSoporte/consultarAnexoTicket') }}",
			"data": {
				'idTicket': idTicket
			},
			"success": function (response) {
				if(response.estado) {
					var html = '';
					$.each(response.data,function(index, result) {
						if(result.archivoAnexo1 != null) {
							html += "<a href=\"javascript:filee('"+result.archivoAnexo1+"', '"+result.archivoNombre1+"', 'text/plain');\">"+result.archivoNombre1+"</a>";
						}

						if(result.archivoAnexo2 != null) {
							html += "<br><br><a href=\"javascript:filee('"+result.archivoAnexo2+"', '"+result.archivoNombre2+"', 'text/plain');\">"+result.archivoNombre2+"</a>";
						}

						if(result.archivoAnexo3 != null) {
							html += "<br><br><a href=\"javascript:filee('"+result.archivoAnexo3+"', '"+result.archivoNombre3+"', 'text/plain');\">"+result.archivoNombre3+"</a>";
						}
					});
					$.each(response.data,function(index, result) {
						if(result.archivoAnexo1 == null && result.archivoAnexo2 == null && result.archivoAnexo3 == null) {
							html += "<br><br><h3>Mensajes</h3>";
							html += "<h6><span class='badge badge-warning'>"+result.created_at + " - " + result.contenido+"</span></h6>";
						}
					});

					$("#anexos").html(html);
				} else {
					alert(response.mensaje);
				}
			}
		});
	}

	/*function filee(data, nombre, mime) {
		download(data, nombre, 'text/plain')
				.then(function(file){
					saveFile(file, nombre);
				})
	}*/
</script>
@stop