@extends('layouts.masterForm')

@section('titulo', 'Ticket')
@section('cuerpo')

<div>
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->

    <form method="POST" enctype="multipart/form-data" action="{{ url('ticket')}}" role="form" accept-charset="utf-8">
        @csrf

        <div style="margin-left:6px;margin-bottom: 12px;">
			<a href="{{ url('ticket') }}" class="btn btn-info"><span class="fa fa-file" title="Limpiar"></span></a>
			<button type="submit" class="btn btn-info" title="Guardar"><span class="fa fa-save"></span></button>
		</div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="tabForm">
			<li class="nav-item"><a class="nav-link active" role="tab" href="#form" aria-controls="home" role="tab" data-toggle="tab"> Datos Principales</a></li>
			<li class="nav-item"><a class="nav-link" role="tab" href="#anexos" aria-controls="anexos" role="tab" data-toggle="tab"> Anexos</a></li>
		</ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="form">
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <label for="categoria"><b>Categoría</b></label>
                        <select name="categoria" id="categoria" class="form-control">
                            <option value="">--Seleccione--</option>
                            
                            @foreach($categoria as $p)
                            @if (old('categoria') == $p->idCategoria)
                                <option value="{{$p->idCategoria}}" selected> {{ $p->nombre}}</option>
                            @else
                                <option value="{{$p->idCategoria}}"> {{ $p->nombre}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-10">
                        <label for="asunto"><b>Problema</b></label>
                        <textarea rows="10" name="asunto" maxlength="500" id="asunto" class="form-control ckeditor">{{old('asunto')}}</textarea>
                        <div class="text-danger">{!!$errors->first('asunto', '<small>:message</small>')!!}</div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="anexos">
                @include('controles.cargarArchivo',["label"=> "Anexo 1", "name"=> "anexo1"])
                @include('controles.cargarArchivo',["label"=> "Anexo 2", "name"=> "anexo2"])
                @include('controles.cargarArchivo',["label"=> "Anexo 3", "name"=> "anexo3"])
            </div>
        </div>
    </form>
</div>
@stop

@section('script')
<script type="text/javascript" charset="utf-8" >
    $(function() {
        controlFile(".image-preview-anexo1");
        controlFile(".image-preview-anexo2");
        controlFile(".image-preview-anexo3");
    });
</script>
@stop