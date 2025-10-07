@extends('layouts.masterForm')

@section('titulo', 'KBArticuloCategoria')
@section('cuerpo')

<div>
	
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ url('kbArticuloCategoria')}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		{{ csrf_field() }}
		
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a title="Nueva Categoría" href="{{ url('kbArticuloCategoria') }}" class="btn btn-info"><span class="fa fa-file"></span></a>
			<a title="Consultar" href="{{ url('kbArticuloCategoria/show') }}" class="btn btn-info"><span class=" fa fa-search" ></span></a>
			<button title="Guardar" type="submit" class="btn btn-info" ><span class="fa fa-save"></span></button>
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
						<label for="nombre">Nombre</label>
						<input name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}"></input>
						<div class="text-danger">{!!$errors->first('nombre', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label for="padreId">Categoría Padre</label>
						<select name="padreId" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($padreId as $p)
								@if (old('padreId') == $p->idKBArticuloCategoria)
									<option value="{{$p->idKBArticuloCategoria}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idKBArticuloCategoria}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('padreId', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="nombreEtiqueta">Nombre de Etiqueta</label>
						<input name="nombreEtiqueta" id="nombreEtiqueta" class="form-control" value="{{old('nombreEtiqueta')}}"></input>
						<div class="text-danger">{!!$errors->first('nombreEtiqueta', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label for="orden">Orden</label>
						<input name="orden" id="orden" class="form-control" value="{{old('orden')}}"></input>
						<div class="text-danger">{!!$errors->first('orden', '<small>:message</small>')!!}</div>
					</div>
				</div>
			</div>
		</form>	
		<div role="tabpanel" class="tab-pane" id="consulta">
			@if(isset($data))

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Categoría Padre</th>
						<th>Descripción</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $dt)
					<tr>
						<td>{{$dt->nombre}}</td>
						<td>{{$dt->carpetaPadre ?? ''}}</td>
						<td>{{$dt->nombreEtiqueta}}</td>
						<td>
							<form class="formDelete" action="{{ route('kbArticuloCategoria.destroy', $dt->idKBArticuloCategoria) }}" method="POST">
								<input type="hidden" value="DELETE" name="_method">
								{{ csrf_field() }}
								<a title="Editar" href="{{ $dt->idKBArticuloCategoria.'/edit'}}" class="btn btn-success btn-xs"><span class="fa fa-check"></span></a>
								<button title="Eliminar" type="submit" class="btn btn-danger btn-xs"><span class="fa fa-window-close"></span></button>
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

	// script confirmar si se elimina una categoria
	$(".formDelete").submit(function(e)
	{
		if(!confirm("Está seguro de eliminar esta categoria?"))
		{
			e.preventDefault();	
		}
	});
	@endif
</script>
@stop
