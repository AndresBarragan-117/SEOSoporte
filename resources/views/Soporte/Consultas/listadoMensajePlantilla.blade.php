@extends('layouts.masterForm',["widthh"=>12])

@section('titulo', 'Consulta Mensaje Plantilla')
@section('cuerpo')

<div>
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->
        @csrf
        
        <br>
        <div class="row">
            @if(isset($mensajePlantilla))
                <table id="ticket_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 150px; !important">Categoría</th>
                            <th>Pregunta</th>
                            <th>Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mensajePlantilla as $dt)
                        <tr>
                            <td>{{$dt->categoria}}</td>
                            <td>{{$dt->pregunta}}</td>
                            <td>{{$dt->respuesta}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
</div>
@stop

@section('script')
<script type="text/javascript" charset="utf-8" >
    $(function() {
       $("#ticket_table").DataTable({
            "language": {
                "url": "{{ asset('js/Spanish.json') }}"
            },
            "order": [[ 2, "desc" ]]
       });
    });
</script>
@stop