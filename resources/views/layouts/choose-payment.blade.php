<!DOCTYPE html>
<html lang="{{ Session::get('locale') }}">

    <head>
        <meta charset="utf-8" />
        <title>@if(!empty($metaTags->title)){{ $metaTags->title }} @else Gestão AC @endif</title>
        @if(!empty($metaTags->description))
            <meta name="description" content="{{ $metaTags->description }}"> @else
            <meta name="description" content="Sistema gerenciador de AR's">@endif

        @if(!empty($metaTags->keywords))
            <meta name="keywords" content="{{ $metaTags->keywords }}"> @else
            <meta name="keywords" content="Gestão AC"> @endif

        @if(!empty($metaTags->author)){{ $metaTags->author }} @else
            <meta name="author" content="Desenvolvido por Krypton BPO"> @endif

        @if(!empty($metaTags->canonical))
            <link rel="canonical" href="{{ $metaTags->canonical }}" />@else
            <link rel="canonical" href="#" /> @endif

        <link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}" />
        <link href="{{ mix('assets/css/essential.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ mix('assets/css/toastr.min.css')}}" rel="stylesheet" type="text/css" />
        @yield('styles')
        @routes
    </head>

    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
        <div class="m-grid m-grid--hor m-grid--root m-page">
            @yield('content')
        </div>
        @routes
        <script src="{{ mix('/assets/js/essential.js') }}" type="text/javascript"></script>
        <script src="{{ mix('/assets/js/common/util.js') }}" type="text/javascript"></script>
        @stack('scripts')
        <script type="text/javascript">
            Util.initModule("{{ $metaTags->module }}", "{{ env('APP_ENV') }}")
        </script>
    </body>
</html>
