@extends('layouts.masterForm')

@section('titulo', 'Rol')
@section('cuerpo')

<div>
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ url('rol')}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		{{ csrf_field() }}
		
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('rol') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<a href="{{ url('rol/show') }}" class="btn btn-info" title="Consultar"><span class="fa fa-search" ></span></a>
			<button type="submit" class="btn btn-info" title="Guardar"><span class="fa fa-save"></span></button>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" href="#form" aria-controls="home" role="tab" data-toggle="tab">Rol</a></li>
			<li class="nav-item"><a class="nav-link" href="#consulta" aria-controls="consulta" role="tab" data-toggle="tab">Consulta</a></li>
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
						<label for="descripcion">Descripción</label>
						<input name="descripcion" id="descripcion" class="form-control" value="{{old('descripcion')}}"></input>
						<div class="text-danger">{!!$errors->first('descripcion', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="estado">Activo? </label>
						<input type="checkbox" name="estado" value="1" checked></input><br>
						<div class="text-danger">{!!$errors->first('estado', '<small>:message</small>')!!}</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Formulario</h3>
							</div>
							<div class="panel-body">
								<table style="margin-left: 10px;">
									<tbody>
										@if(isset($formularios))
											@foreach($formularios as $f)
												<tr>
													<td><span class="fa fa-list"></span> 
														<label for="frm{{ $f->idFormulario }}">{{$f->nombre}}
														
													</td>
													<td style="padding: 2px">
														</label>
														<input id="frm{{ $f->idFormulario }}" type="checkbox" name="form[]" value="{{ $f->idFormulario }}" />
													</td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div role="tabpanel" class="tab-pane" id="consulta">
			@if(isset($data))

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Rol</th>
						<th>Descripción</th>
						<th>Estado</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $dt)
					<tr>
						<td>{{ $dt->nombre }}</td>
						<td>{{ $dt->descripcion }}</td>
						<td>{{ ($dt->estado) ? 'Activo' : 'Inactivo' }}</td>
						<td>
							
							<form id="formDelete" action="{{ route('rol.destroy', $dt->idRol) }}" method="POST">
								<input type="hidden" value="DELETE" name="_method">
								{{ csrf_field() }}
								<a href="{{ $dt->idRol.'/edit'}}" class="btn btn-success btn-xs"><span class="fa fa-check"></span></a>
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
