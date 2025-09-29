<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} </title>

    <!-- Scripts -->
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">-->
    <!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> -->
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    <link href="/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="/css/ui.core.css" rel="stylesheet">
    <link href="/css/ui.theme.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/buttons.dataTables.min.css">
    <link href="/css/toastr.css" rel="stylesheet">
    <style>
        .dataTables_wrapper {
            width: 100%;
        }
        .dropdown-submenu {
            position: relative;
            }

            .dropdown-submenu a::after {
            transform: rotate(-90deg);
            position: absolute;
            right: 6px;
            top: .8em;
            }

            .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-left: .1rem;
            margin-right: .1rem;
            }
    </style>
</head>
<body>
    <div id="app">
        @include('layouts.menu')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script type="text/javascript" src="{{ asset('js/general.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.mask.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('plugins/DataTables/datatables.min.js')}} "></script>
    <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/buttons.flash.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jszip.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/buttons.print.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/toastr.js') }}"></script>

    <script type="text/javascript">
        function notificaciones(){
            $.ajax(
            {
                "dataType": 'json',
                "type": "POST",
                "url": "{{ url('/ticketSoporte/notificacionTicket') }}",
                "data": null,
                "success": function (response) {
                    if(response.estado) {
                        $("#cantidadNotif").html(response.cantidad);
                        $("#notif").html();
                        if(response.cantidad > 0) {
                            var html = '';
                            $.each(response.data,function(index, result) {
                                html += '<a class="dropdown-item waves-effect waves-light" href="{{url('ticketSoporte/listadoTicketSoporte')}}"><h6><b>'+result.fecha+'</b><br>'+result.empresa+'<br>'+result.usuario+'</h6></a>';
                            });
                            $("#notif").html(html);
                        }
                    } else {
                        toastr.error(response.mensaje, "Error: ");
                    }
                }
            });
        }
        
        $(function () {
            notificaciones();
            setInterval(() => { notificaciones(); }, 15000); // 15segundos
        });

        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            ]); ?>

        path_base = '{{ url("/") }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('script')
</body>
</html>
