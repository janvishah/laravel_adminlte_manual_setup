<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


        <!-- CSS -->
        
        <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- SweetAlert CSS-->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
              
       
        @stack('css_after')

        <!-- Scripts -->
        <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        

        <!-- SweetAlert JS-->
        <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>       

        @stack('js_after')
    </head>


    <body  class="sidebar-mini layout-fixed" style="height: auto;">
    
        <div class="wrapper">
            
                @include('layouts.partials.navbar')
           
                @include('layouts.partials.sidebar')

                 <!-- Page Content -->
                <main class="content-wrapper card">
                    @yield('content')
                </main>

                @include('layouts.partials.footer')
        </div>
         

    </body>
</html>
