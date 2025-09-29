@extends('layouts.masterForm')

@section('titulo', 'KBArticulo')
@section('cuerpo')

<div>
	
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ url('kbArticulo')}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		{{ csrf_field() }}
		
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('kbArticulo') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<a href="{{ url('kbArticulo/show') }}" class="btn btn-info" title="Consultar"><span class=" fa fa-search" ></span></a>
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
						<label for="categoria">Categoría</label>
						<select name="categoria" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($categorias as $p)
								@if (old('categoria') == $p->idKBArticuloCategoria)
									<option value="{{$p->idKBArticuloCategoria}}" selected> {{ $p->nombre}}</option>
								@else
									<option value="{{$p->idKBArticuloCategoria}}"> {{ $p->nombre}}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('categoria', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="asunto">Asunto</label>
						<input name="asunto" id="asunto" class="form-control" value="{{old('nombre')}}"></input>
						<div class="text-danger">{!!$errors->first('asunto', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label for="contenido">Contenido</label>
						<textarea rows="10" name="contenido" id="contenido" class="form-control ckeditor">{{old('contenido')}}</textarea>
						<div class="text-danger">{!!$errors->first('contenido', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label for="tipo">Tipo</label>
						<select name="tipo" class="form-control">
							<option value="" selected>--Seleccione--</option>
							<option value="0">GRABADO</option>
							<option value="1">PUBLICO</option>
							<option value="2">SOPORTE</option>
						</select>
						<div class="text-danger">{!!$errors->first('categoria', '<small>:message</small>')!!}</div>
					</div>
				</div>
			</div>
		</form>	
		<div role="tabpanel" class="tab-pane" id="consulta">
			@if(isset($data))

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Asunto</th>
						<th>Categoría</th>
						<th>Tipo</th>
						<th>Acción</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $dt)
					<tr>
						<td>{{$dt->asunto}}</td>
						<td>{{$dt->categoria}}</td>
						<td>{{$dt->tipo}}</td>
						<td>
							<form id="formDelete" action="{{ route('kbArticulo.destroy', $dt->idKBArticulo) }}" method="POST">
								<input type="hidden" value="DELETE" name="_method">
								{{ csrf_field() }}
								<a href="{{ $dt->idKBArticulo.'/edit'}}" class="btn btn-success btn-xs"><span class="fa fa-check"></span></a>
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
