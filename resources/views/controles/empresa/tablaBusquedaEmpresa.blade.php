<table id="tbBusquedaEmpresa" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Nit</th>
			<th>Empresa</th>
		</tr>
	</thead>
	<tbody>
		@if($data != null)
			@if(count($data) > 0)
				@foreach($data as $d)
					<tr style="cursor: pointer;" onDblClick="MontarEmpresa({'idEmpresa':'{{ $d->idEmpresa }}','nit':'{{$d->nit}}', 'razonSocial': '{{ $d->razonSocial }}' })">
						<td>{{$d->nit}}</td>
						<td>{{$d->razonSocial}}</td>
					</tr>		
				@endforeach
			@else
				<tr>
				<td colspan="2" align="center">Sin Datos</td>
			</tr>
			@endif
			
		@else
			<tr>
				<td colspan="2" align="center">Sin Datos</td>
			</tr>
		@endif
	</tbody>
</table>