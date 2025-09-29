@extends('layouts.masterForm')

@section('titulo', 'Estado del Ticket')
@section('cuerpo')

<div>
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ route('ticketEstado.update', $edit->idTicketEstado)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		<input name="_method" type="hidden" value="PUT">

		{{ csrf_field() }}
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('ticketEstado') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<button type="submit" class="btn btn-info" title="Modificar"><span class="fa fa-edit" ></span></button>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" href="#form" aria-controls="home" role="tab" data-toggle="tab"> Formulario</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
				<div class="row">
					<div class="col-md-6">
						<label for="nombre">Nombre</label>
						<input name="nombre" id="nombre" class="form-control" value="{{$edit->nombre}}"></input>
						<div class="text-danger">{!!$errors->first('nombre', '<small>:message</small>')!!}</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-2">
						<label for="orden">Orden</label>
						<input name="orden" id="orden" class="form-control" value="{{$edit->orden}}"></input>
						<div class="text-danger">{!!$errors->first('orden', '<small>:message</small>')!!}</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-8">
						<label for="color">Color Estado</label>
						<input id="color" name="color" type="color" value="{{$edit->color}}">
						<div class="text-danger">{!!$errors->first('color', '<small>:message</small>')!!}</div>
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