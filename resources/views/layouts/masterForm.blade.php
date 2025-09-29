@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
            <div class="col-md-{{ ucfirst($widthh ?? '10') }}">
               <div class="card mb-3">
                  <div class="card-header">
                     @yield('titulo')
                  </div>
                  <div class="card-body">
                     @yield('cuerpo')
                  </div>
               </div>
            </div>
         </div>
   </div>
</div>
@stop 
@section('script')
@stop