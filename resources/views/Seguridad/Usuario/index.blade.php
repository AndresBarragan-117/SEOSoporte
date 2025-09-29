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

	<form action="{{ url('usuario')}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		{{ csrf_field() }}

		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('usuario') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<a href="{{ url('usuario/show') }}" class="btn btn-info" title="Consultar"><span class=" fa fa-search" ></span></a>
			<button type="submit" class="btn btn-info" title="Guardar"><span class="fa fa-save"></span></button>
		</div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" role="tab" data-toggle="tab" aria-controls="home" href="#form">Funcionario</a></li>
			<li class="nav-item"><a class="nav-link" role="tab"  data-toggle="tab" aria-controls="consulta" href="#consulta">Consulta</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
				<br>
				<div class="row">
					<div class="col-md-8">
						<label for="name">Nombre</label>
						<input name="name" id="name" class="form-control" value="{{old('name')}}"></input>
						<div class="text-danger">{!!$errors->first('name', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="email">Email</label>
						<input name="email" id="email" class="form-control" value="{{old('email')}}"></input>
						<div class="text-danger">{!!$errors->first('email', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="firma">Firma</label>
						<input name="firma" id="firma" class="form-control" value="{{old('firma')}}"></input>
						<div class="text-danger">{!!$errors->first('firma', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="password">Contraseña</label>
						<input type="password" name="password" id="password" class="form-control" value="{{old('password')}}" required></input>
						<div class="text-danger">{!!$errors->first('password', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="password-confirm">Confirmar Contraseña</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        
					</div>
				</div>
				<br>
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
														
														<input id="frm{{ $f->idRol }}" type="checkbox" name="form[]" value="{{ $f->idRol }}" />
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
														
														<input id="frm{{ $f->idCategoria }}" type="checkbox" name="cats[]" value="{{ $f->idCategoria }}" />
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
										<tr>
											<td><span class="glyphicon glyphicon-list"></span> <label for="frmTodoSoporte">Mostrar todos los soportes</label></td>
											<td style="padding: 2px">
												<input id="frmTodoSoporte" type="checkbox" name="acciones[]" value="MOSTRARTODOSOPORTE" />
											</td>
										</tr>
										<tr>
											<td><span class="glyphicon glyphicon-list"></span> <label for="frmCambiarAtencion">Cambiar Atención</label></td>
											<td style="padding: 2px">
												<input id="frmCambiarAtencion" type="checkbox" name="acciones[]" value="CAMBIARATENCION" />
											</td>
										</tr>
										<tr>
											<td><span class="glyphicon glyphicon-list"></span> <label for="frmCambiarPrioridadTicket">Cambiar Prioridad Ticket</label></td>
											<td style="padding: 2px">
												<input id="frmCambiarPrioridadTicket" type="checkbox" name="acciones[]" value="CAMBIARPRIORIDADTICKET" />
											</td>
										</tr>
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
						<th>Usuario</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $dt)
					<tr>
						<td>{{ $dt->name }}</td>
						<td>
							<form id="formDelete" action="{{ route('usuario.destroy', $dt->id) }}" method="POST">
								<input type="hidden" value="DELETE" name="_method">
								{{ csrf_field() }}
								<a href="{{ $dt->id.'/edit'}}" class="btn btn-success btn-xs"><span class="fa fa-check"></span></a>
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