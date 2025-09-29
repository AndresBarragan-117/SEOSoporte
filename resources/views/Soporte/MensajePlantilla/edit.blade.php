@extends('layouts.masterForm')

@section('titulo', 'Mensaje Plantilla')
@section('cuerpo')

<div>
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ route('mensajePlantilla.update', $edit->idMensajePlantilla)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		<input name="_method" type="hidden" value="PUT">

		{{ csrf_field() }}
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('mensajePlantilla') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
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
						<label for="categoria">Categoría</label>
						<select name="categoria" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($categoria as $c)
								@if ($edit->idCategoria == $c->idCategoria)
									<option value="{{$c->idCategoria}}" selected> {{ $c->nombre}}</option>
								@else
									<option value="{{$c->idCategoria}}"> {{ $c->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('categoria', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="pregunta">Pregunta</label>
						<input name="pregunta" id="pregunta" class="form-control" value="{{$edit->pregunta}}"></input>
						<div class="text-danger">{!!$errors->first('pregunta', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="respuesta">Respuesta</label>
						<input name="respuesta" id="respuesta" class="form-control" value="{{$edit->respuesta}}"></input>
						<div class="text-danger">{!!$errors->first('respuesta', '<small>:message</small>')!!}</div>
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