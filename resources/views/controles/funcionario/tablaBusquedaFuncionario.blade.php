<table id="tbBusquedaFuncionario" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Email</th>
			<th>Funcionario</th>
		</tr>
	</thead>
	<tbody>
		@if($data != null)
			@if(count($data) > 0)
				@foreach($data as $d)
					<tr style="cursor: pointer;" onDblClick="MontarFuncionario({'idFuncionario':'{{ $d->idFuncionario }}','nombre':'{{$d->nombre}}' }, '{{$control}}')">
						<td>{{$d->email}}</td>
						<td>{{$d->nombre}}</td>
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