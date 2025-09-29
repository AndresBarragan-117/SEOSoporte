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

	<form action="{{ route('rol.update', $edit->idRol)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		<input name="_method" type="hidden" value="PUT">

		{{ csrf_field() }}
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('rol') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
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
						<div class="text-danger">{{$errors->first('nombre')}}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="descripcion">Descripción</label>
						<input name="descripcion" id="descripcion" class="form-control" value="{{$edit->descripcion}}"></input>
						<div class="text-danger">{!!$errors->first('descripcion', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="estado">Activo?</label>
						@if( ($edit->estado) )
						<input type="checkbox" name="estado" value="1" checked /><br>
						@else
						<input type="checkbox" name="estado" value="" /><br>
						@endif
						<div class="text-danger">{!!$errors->first('estado', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
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
											<td>
												<span class="fa fa-list"></span> <label for="frm{{ $f->idFormulario }}">{{$f->nombre}}</label>
											</td>
											<td style="padding: 2px">
												@if($f->seleccionar == 1)
												<input id="frm{{ $f->idFormulario }}" type="checkbox" name="form[]" value="{{ $f->idFormulario }}" checked="true" />
												@else
												<input id="frm{{ $f->idFormulario }}" type="checkbox" name="form[]" value="{{ $f->idFormulario }}" />
												@endif
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
	</div>
</div>

@stop
