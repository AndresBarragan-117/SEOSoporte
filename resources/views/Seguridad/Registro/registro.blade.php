@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Regístro') }}</div>
                
                <div class="card-body">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                            @endif
                        @endforeach
                    </div> <!-- end .flash-message -->

                    <form method="POST" action="{{ url('registro')}}" role="form" accept-charset="utf-8">
                        @csrf

                        <input type="hidden" value="{{ $nit }}" name="nit" />
                        <input type="hidden" value="{{ $usuario }}" name="usuario" />

                        {{-- 
                            
                            <div class="form-group row">
                            <label for="empresa" class="col-md-4 col-form-label text-md-right">Empresa</label>
                            <div class="col-md-6">
                                <select name="empresa" id="empresa" disabled class="form-control">
                                    <option value="">--Seleccione--</option>
                                    
                                    @foreach($empresa as $p)
                                    @if ($nit == $p->nit)
                                        <option value="{{$p->idEmpresa}}" selected> {{ $p->razonSocial}}</option>
                                    @else
                                        <option value="{{$p->idEmpresa}}"> {{ $p->razonSocial}}</option>
                                    @endif
        
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        {{--<div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre de Usuario') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $usuario }}" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>--}}

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


@section('script')
<script type="text/javascript" charset="utf-8" >
   /* $('form').bind('submit', function () {
        $(this).find(':input').prop('disabled', false);
    });*/
</script>
@stop