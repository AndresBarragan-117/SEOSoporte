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

	<form action="{{ route('kbArticulo.update', $edit->idKBArticulo)}}" role="form" class="form-horizontal" method="POST" accept-charset="utf-8">
		<input name="_method" type="hidden" value="PUT">

		{{ csrf_field() }}
		<div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('kbArticulo') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
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
							@foreach($categorias as $p)
								@if ($edit->idKBArticuloCategoria == $p->idKBArticuloCategoria)
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
						<input name="asunto" id="asunto" class="form-control" value="{{$edit->asunto}}"></input>
						<div class="text-danger">{!!$errors->first('asunto', '<small>:message</small>')!!}</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label for="contenido">Contenido</label>
						<textarea rows="10" name="contenido" id="contenido" class="form-control ckeditor">{{$edit->contenido}}</textarea>
						<div class="text-danger">{!!$errors->first('contenido', '<small>:message</small>')!!}</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-8">
						<label for="tipo">Tipo</label>
						<!-- <select name="tipo" class="form-control">
							<option value="" selected>--Seleccione--</option>
							<option value="0" {{old('estado',$edit->tipo) == '0' ?'selected':''}}>GRABADO</option>
							<option value="1" {{old('estado',$edit->tipo) == '1' ?'selected':''}}>PUBLICO</option>
							<option value="2" {{old('estado',$edit->tipo) == '2' ?'selected':''}}>SOPORTE</option>
						</select> -->
						<select name="tipo" class="form-control">
							<option value="">--Seleccione--</option>
							@foreach($tipos as $t)
								@if (old('tipo') == $t->idKBArticuloTipo)
									<option value="{{ $t->idKBArticuloTipo }}" selected>{{ $t->nombre }}</option>
								@else
									<option value="{{ $t->idKBArticuloTipo }}">{{ $t->nombre }}</option>
								@endif
							@endforeach
						</select>
						<div class="text-danger">{!!$errors->first('categoria', '<small>:message</small>')!!}</div>
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
