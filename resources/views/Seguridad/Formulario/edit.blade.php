@extends('layouts.masterForm')

@section('titulo', 'Formulario')
@section('cuerpo')


<div>
	
	<div class="flash-message">
	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
		@if(Session::has('alert-' . $msg))
		<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	</div> <!-- end .flash-message -->

	<form action="{{ route('formulario.update', $edit->idFormulario)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		{{ csrf_field() }}
		<input name="_method" type="hidden" value="PUT">
		<div style="margin-left:6px;margin-bottom: 12px;">
			
			<a href="{{ url('formulario') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<button type="submit" class="btn btn-info" title="Modificar"><span class="fa fa-edit" ></span></button>
		
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" href="#form" aria-controls="home" role="tab" data-toggle="tab">Formulario</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="form">
				<div class="row">
					<div class="col-md-8">
						<label for="nombre">Nombre</label>
						<input name="nombre" id="nombre" class="form-control" value="{{ old('nombre',$edit->nombre)}}"></input>
						<div class="text-danger">{!!$errors->first('nombre', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label for="path">Path</label>
						<input name="path" id="path" class="form-control" value="{{ old('path',$edit->path)}}"></input>
						<div class="text-danger">{!!$errors->first('path', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label for="tag">Tag</label>
						<input name="tag" id="tag" class="form-control" value="{{old('tag',$edit->tag)}}" readonly="true"></input>
						<div class="text-danger">{!!$errors->first('tag', '<small>:message</small>')!!}</div>
					</div>
				</div>
				

				<div class="row">
					<div class="col-md-8">
						<label for="carpeta">Carpeta</label>
						<select name="carpeta" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($carpeta as $p)
							@if (old('carpeta',$edit->idCarpeta) == $p->idCarpeta)
							<option value="{{$p->idCarpeta}}" selected> {{ $p->descripcion}}</option>
							@else

							<option value="{{$p->idCarpeta}}"> {{ $p->descripcion}}</option>
							@endif

							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('carpeta', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-5">
						<label for="estado">
							Estado
							<input type="checkbox" name="estado" id="estado" {{old('estado',$edit->estado)?'checked':''}}></input>
						</label>
						
						<div class="text-danger">{!!$errors->first('estado', '<small>:message</small>')!!}</div>
					</div>
				</div>


			</div>
		</form>	
	</div>
</div>

@stop

@section('script')

<script type="text/javascript" charset="utf-8" >
	
</script>

@stop
