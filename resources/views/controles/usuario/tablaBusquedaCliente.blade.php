<table id="tbBusquedaCliente" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Usuario</th>
			<th>Empresa</th>
		</tr>
	</thead>
	<tbody>
		@if($data != null)
			@if(count($data) > 0)
				@foreach($data as $d)
					<tr style="cursor: pointer;" onDblClick="MontarCliente({'idEmpresaClienteUsuario':'{{ $d->idEmpresaClienteUsuario }}', 'nombre': '{{ $d->nombre }}', 'razonSocial': '{{ $d->razonSocial }}' })">
						<td>{{$d->nombre}}</td>
						<td>{{$d->razonSocial}}</td>
					</tr>		
				@endforeach
			@else
				<tr>
				<td colspan="6" align="center">Sin Datos</td>
			</tr>
			@endif
			
		@else
			<tr>
				<td colspan="6" align="center">Sin Datos</td>
			</tr>
		@endif
	</tbody>
</table>