@extends('layouts.masterForm')

@section('titulo', 'Carpeta')

@section('cuerpo')
<div>
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ url('carpeta')}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		{{ csrf_field() }}
		
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('carpeta') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<a href="{{ url('carpeta/show') }}" class="btn btn-info" title="Consultar"><span class=" fa fa-search" ></span></a>
			<button type="submit" class="btn btn-info" title="Guardar"><span class="fa fa-save"></span></button>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" role="tab" href="#form" aria-controls="home" role="tab" data-toggle="tab"> Formulario</a></li>
			<li class="nav-item"><a class="nav-link" role="tab" href="#consulta" aria-controls="consulta" role="tab" data-toggle="tab">Consulta</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
				<div class="row">
					<div class="col-md-8">
						<label for="descripcion">Descripción</label>
						<input name="descripcion" id="descripcion" class="form-control" value="{{old('descripcion')}}"></input>
						<div class="text-danger">{!!$errors->first('descripcion', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row hide">
					<div class="col-md-8">
						<label for="carpetaPadre">Carpeta padre</label>
						<select name="carpetaPadre" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($carpeta as $p)
							@if (old('carpetaPadre') == $p->idCarpeta)
							<option value="{{$p->idCarpeta}}" selected> {{ $p->descripcion}}</option>
							@else

							<option value="{{$p->idCarpeta}}"> {{ $p->descripcion}}</option>
							@endif

							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('carpetaPadre', '<small>:message</small>')!!}</div>
					</div>
				</div>


			</div>
		</form>	
		<div role="tabpanel" class="tab-pane" id="consulta">
			@if(isset($data))

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Carpeta</th>
						<!--<th>Padre</th>-->
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $dt)
					<tr>
						<td>{{$dt->descripcion}}</td>
						<!--<td>{{$dt->carpetaPadre}}</td>-->
						<td>
							
							<form id="formDelete" action="{{ route('carpeta.destroy', $dt->idCarpeta) }}" method="POST">
								<input type="hidden" value="DELETE" name="_method">
								{{ csrf_field() }}
								<a href="{{ $dt->idCarpeta.'/edit'}}" class="btn btn-success btn-xs"><span class="fa fa-check"></span></a>
								<button type="submit" class="btn btn-danger btn-xs"><span class="fa fa-window-close"></span></button>
							</form>
							
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif

		</div>
	</div>
</div>

@stop

@section('script')
<script type="text/javascript" charset="utf-8" >
	@if(isset($data))
	$('#tabForm a[href=\"#consulta\"]').tab('show');

	$("#formDelete").submit(function(e)
	{

		if(!confirm("Está seguro de eliminar este registro?"))
		{
			e.preventDefault();	
		}

	});
	@endif
</script>
@stop
