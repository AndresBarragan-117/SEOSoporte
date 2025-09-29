@extends('layouts.masterForm')

@section('titulo', 'Parámetro Por Defecto')
@section('cuerpo')

<div>
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ route('parametroDefecto.update', $edit->idParametroDefecto)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		<input name="_method" type="hidden" value="PUT">

		{{ csrf_field() }}
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('parametroDefecto') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<button type="submit" class="btn btn-info" title="Modificar"><span class="fa fa-edit" ></span></button>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" href="#form" aria-controls="home" role="tab" data-toggle="tab"> Formulario</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
				<div class="row">
					<div class="col-md-4">
						<label for="ticketPrioridad">Prioridad del Ticket</label>
						<select name="ticketPrioridad" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($ticketPrioridad as $p)
								@if ($edit->idTicketPrioridad == $p->idTicketPrioridad)
									<option value="{{$p->idTicketPrioridad}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idTicketPrioridad}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('ticketPrioridad', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="ticketEstado">Estado del Ticket</label>
						<select name="ticketEstado" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($ticketEstado as $p)
								@if ($edit->idTicketEstado == $p->idTicketEstado)
									<option value="{{$p->idTicketEstado}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idTicketEstado}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('ticketEstado', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="funcionario">Funcionario</label>
						<select name="funcionario" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($funcionario as $p)
								@if ($edit->idFuncionario == $p->idFuncionario)
									<option value="{{$p->idFuncionario}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idFuncionario}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('funcionario', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="ticketEstadoFinalizar">Estado Finalizar del Ticket</label>
						<select name="ticketEstadoFinalizar" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($ticketEstado as $p)
								@if ($edit->idTicketEstadoFinalizar == $p->idTicketEstado)
									<option value="{{$p->idTicketEstado}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idTicketEstado}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('ticketEstadoFinalizar', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="ticketEstadoArchivar">Estado Archivar del Ticket</label>
						<select name="ticketEstadoArchivar" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($ticketEstado as $p)
								@if ($edit->idTicketEstadoArchivar == $p->idTicketEstado)
									<option value="{{$p->idTicketEstado}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idTicketEstado}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('ticketEstadoArchivar', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="ticketEstadoRechazar">Estado Rechazar del Ticket</label>
						<select name="ticketEstadoRechazar" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($ticketEstado as $p)
								@if ($edit->idTicketEstadoRechazar == $p->idTicketEstado)
									<option value="{{$p->idTicketEstado}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idTicketEstado}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('ticketEstadoRechazar', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-2">
						<label for="diasArchivar">Días Archivar</label>
						<input name="diasArchivar" id="diasArchivar" class="form-control" value="{{$edit->diasArchivar}}"></input>
						<div class="text-danger">{!!$errors->first('diasArchivar', '<small>:message</small>')!!}</div>
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
