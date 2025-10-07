@extends('layouts.masterForm')

@section('titulo', 'Lista Plantillas')
@section('cuerpo')

<div>
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->
 
	<form action="{{ url('plantillaLista')}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		{{ csrf_field() }}
		<!-- Acciones(COnsultar, Guardar, Nuevo) -->
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('plantillaLista') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<a href="{{ url('plantillaLista/show') }}" class="btn btn-info" title="Consultar"><span class=" fa fa-search" ></span></a>
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
						<label for="listaPlantilla">Plantilla</label>
						<select name="listaPlantilla" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($listaPlantilla as $p)
								dd($listaPlantilla);
								@if (old('listaPlantilla') == $p->idPlantilla)
									<option value="{{$p->idPlantilla}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idPlantilla}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach	
						</select>	
						<div class="text-danger">{!!$errors->first('listaPlantilla', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="numeroOrdenLista">Número Orden Lista</label>
						<input name="numeroOrdenLista" id="numeroOrdenLista" class="form-control" value="{{old('numeroOrdenLista')}}"></input>
						<div class="text-danger">{!!$errors->first('numeroOrdenLista', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="listaTipoCaptura">Tipo Captura</label>
						<select name="listaTipoCaptura" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($listaTipoCaptura as $p)
								@if (old('listaTipoCaptura') == $p->idTipoCaptura)
									<option value="{{$p->idTipoCaptura}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idTipoCaptura}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('listaTipoCaptura', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="opcionTipoCaptura">Opción Tipo Captura</label>
						<input name="opcionTipoCaptura" id="opcionTipoCaptura" class="form-control" value="{{old('opcionTipoCaptura')}}"></input>
						<div class="text-danger">{!!$errors->first('opcionTipoCaptura', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-5">
						<label for="estado">
							Estado
							<input type="checkbox" name="estado" id="estado" checked></input>
						</label>
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
						<th>Descripción</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $dt)
					<tr>
						<td>{{$dt->nombre}}</td>
						<td>{{$dt->descripcion}}</td>
						<td>
							<form class="formDelete" action="{{ route('plantillaLista.destroy', $dt->idPlantillaLista) }}" method="POST">
								<input type="hidden" value="DELETE" name="_method">
								{{ csrf_field() }}
								<a title="Editar" href="{{ $dt->idPlantillaLista.'/edit'}}" class="btn btn-success btn-xs"><span class="fa fa-check"></span></a>
								{{-- <button title="Eliminar" type="submit" class="btn btn-danger btn-xs"><span class="fa fa-window-close"></span></button> --}}
								<button title="Eliminar" type="submit" class="btn btn-danger btn-xs delete-btn" data-name="{{ $dt->nombre }}">
									<span class="fa fa-window-close"></span>
								</button>
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
	
	// script confirmar si se elimina una plantilla
	$(".formDelete").submit(function(e){
		if(!confirm("¿Está seguro de eliminar esta lista de plantilla?")) 
		{
			e.preventDefault();	
		}
	});
	@endif
</script>
@stop
