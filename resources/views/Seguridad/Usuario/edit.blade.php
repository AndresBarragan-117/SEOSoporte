@extends('layouts.masterForm')
@section('titulo', 'Funcionario')
@section('cuerpo')

<div>
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ route('usuario.update', $edit->id)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		<input name="_method" type="hidden" value="PUT">

		{{ csrf_field() }}
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('usuario') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<button type="submit" class="btn btn-info" title="Modificar"><span class="fa fa-edit" ></span></button>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" href="#form" aria-controls="home" role="tab" data-toggle="tab"> Usuario</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
				<div class="row">
					<div class="col-md-8">
						<label for="name">Nombre</label>
						<input name="name" id="name" class="form-control" value="{{$edit->name}}" />
						<div class="text-danger">{!!$errors->first('name', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="email">Email</label>
						<input name="email" id="email" class="form-control" value="{{$edit->email}}"></input>
						<div class="text-danger">{!!$errors->first('email', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="firma">Firma</label>
						<input name="firma" id="firma" class="form-control" value="{{$firma}}"></input>
						<div class="text-danger">{!!$errors->first('firma', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="password">Contraseña</label>
						<input type="password" name="password" id="password" class="form-control" value="{{$edit->password}}" required></input>
						<div class="text-danger">{!!$errors->first('password', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="password-confirm">Confirmar Contraseña</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="{{$edit->password}}" required>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Roles</h3>
							</div>
							<div class="panel-body">
								<table style="margin-left: 10px;">
									<tbody>
										@if(isset($roles))
											@foreach($roles as $f)
												<tr>
													<td><span class="glyphicon glyphicon-list"></span> <label for="frm{{ $f->idRol }}">{{$f->nombre}}</label></td>
													<td style="padding: 2px">
														
														@if($f->seleccionar == 1)
															<input id="frm{{ $f->idRol }}" type="checkbox" name="form[]" value="{{ $f->idRol }}" checked="true" />
														@else
															<input id="frm{{ $f->idRol }}" type="checkbox" name="form[]" value="{{ $f->idRol }}" />
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

					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Categorías</h3>
							</div>
							<div class="panel-body">
								<table style="margin-left: 10px;">
									<tbody>
										@if(isset($categorias))
											@foreach($categorias as $f)
												<tr>
													<td><span class="glyphicon glyphicon-list"></span> <label for="frm{{ $f->idCategoria }}">{{$f->nombre}}</label></td>
													<td style="padding: 2px">
														
														@if($f->seleccionar == 1)
															<input id="frm{{ $f->idCategoria }}" type="checkbox" name="cats[]" value="{{ $f->idCategoria }}" checked="true" />
														@else
															<input id="frm{{ $f->idCategoria }}" type="checkbox" name="cats[]" value="{{ $f->idCategoria }}" />
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

					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Acciones</h3>
							</div>
							<div class="panel-body">
								<table style="margin-left: 10px;">
									<tbody>
										@if(isset($acciones))
											<tr>
												<td><span class="glyphicon glyphicon-list"></span> <label for="frmTodoSoporte">Mostrar todos los soportes</label></td>
												<td style="padding: 2px">
													@if (in_array('MOSTRARTODOSOPORTE', $acciones))
														<input id="frmTodoSoporte" type="checkbox" name="acciones[]" value="MOSTRARTODOSOPORTE" checked="true" />
													@else
														<input id="frmTodoSoporte" type="checkbox" name="acciones[]" value="MOSTRARTODOSOPORTE" />
													@endif
												</td>
											</tr>
											<tr>
												<td><span class="glyphicon glyphicon-list"></span> <label for="frmCambiarAtencion">Cambiar Atención</label></td>
												<td style="padding: 2px">
													@if (in_array('CAMBIARATENCION', $acciones))
														<input id="frmCambiarAtencion" type="checkbox" name="acciones[]" value="CAMBIARATENCION" checked="true" />
													@else
														<input id="frmCambiarAtencion" type="checkbox" name="acciones[]" value="CAMBIARATENCION" />
													@endif
												</td>
											</tr>
											<tr>
												<td><span class="glyphicon glyphicon-list"></span> <label for="frmCambiarPrioridadTicket">Cambiar Prioridad Ticket</label></td>
												<td style="padding: 2px">
													@if (in_array('CAMBIARPRIORIDADTICKET', $acciones))
														<input id="frmCambiarPrioridadTicket" type="checkbox" name="acciones[]" value="CAMBIARPRIORIDADTICKET" checked="true" />
													@else
														<input id="frmCambiarPrioridadTicket" type="checkbox" name="acciones[]" value="CAMBIARPRIORIDADTICKET" />
													@endif
												</td>
											</tr>
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