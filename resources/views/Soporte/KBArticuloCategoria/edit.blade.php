@extends('layouts.masterForm')

@section('titulo', 'KBArticuloCategoría')
@section('cuerpo')

<div>
	
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ route('kbArticuloCategoria.update', $edit->idKBArticuloCategoria)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		<input name="_method" type="hidden" value="PUT">

		{{ csrf_field() }}
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('kbArticuloCategoria') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<button type="submit" class="btn btn-info" title="Modificar"><span class="fa fa-edit" ></span></button>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" href="#form" aria-controls="home" role="tab" data-toggle="tab"> Formulario</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">

				<div class="row">
					<div class="col-md-8">
						<label for="nombre">Nombre</label>
						<input name="nombre" id="nombre" class="form-control" value="{{$edit->nombre}}"></input>
						<div class="text-danger">{!!$errors->first('nombre', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="padreId">Categoría Padre</label>
						<select name="padreId" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($padreId as $p)
								@if ($edit->padreId == $p->idKBArticuloCategoria)
									<option value="{{$p->padreId}}" selected> {{ $p->nombre}}</option>
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
						<input name="nombreEtiqueta" id="nombreEtiqueta" class="form-control" value="{{$edit->nombreEtiqueta}}"></input>
						<div class="text-danger">{!!$errors->first('nombreEtiqueta', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="orden">Orden</label>
						<input name="orden" id="orden" class="form-control" value="{{$edit->orden}}"></input>
						<div class="text-danger">{!!$errors->first('orden', '<small>:message</small>')!!}</div>
					</div>
				</div>
			</div>
		</form>	
	</div>
</div>

@stop

@section('script')

<script type="text/javascript" charset="utf-8" >
	@if(isset($data))
	$('#tabForm a[href=\"#consulta\"]').tab('show');
	@endif

</script>

@stop
