<!DOCTYPE html>
{{--
Product Name: {{ theme()->getOption('product', 'description') }}
Author: KeenThemes
Purchase: {{ theme()->getOption('product', 'purchase') }}
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: {{ theme()->getOption('product', 'license') }}
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"{!! theme()->printHtmlAttributes('html') !!} {{ theme()->printHtmlClasses('html') }}>
{{-- begin::Head --}}
<head>
    <meta charset="utf-8"/>
    @if(isset($title) && !empty($title))
        <title>{{ $title }}</title>
    @else
        <title>Market Research</title>
    @endif
    <meta name="description" content="{{ ucfirst(theme()->getOption('meta', 'description')) }}"/>
    <meta name="keywords" content="{{ theme()->getOption('meta', 'keywords') }}"/>
    <link rel="canonical" href="{{ ucfirst(theme()->getOption('meta', 'canonical')) }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" href="{{ url('assets/img/favicons/favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>--}}

    {{-- begin::Fonts --}}
    {{ theme()->includeFonts() }}
    {{-- end::Fonts --}}

    @if (theme()->hasVendorFiles('css'))
        {{-- begin::Page Vendor Stylesheets(used by this page) --}}
        @foreach (array_unique(theme()->getVendorFiles('css')) as $file)
            @if(util()->isExternalURL($file))
                <link rel="stylesheet" href="{{ $file }}"/>
            @else
                {!! preloadCss(getAsset($file)) !!}
            @endif
        @endforeach
        {{-- end::Page Vendor Stylesheets --}}
    @endif

    @if (theme()->hasOption('page', 'assets/custom/css'))
        {{-- begin::Page Custom Stylesheets(used by this page) --}}
        @foreach (array_unique(theme()->getOption('page', 'assets/custom/css')) as $file)
            {!! preloadCss(getAsset($file)) !!}
        @endforeach
        {{-- end::Page Custom Stylesheets --}}
    @endif

    @if (theme()->hasOption('assets', 'css'))
        {{-- begin::Global Stylesheets Bundle(used by all pages) --}}
        @foreach (array_unique(theme()->getOption('assets', 'css')) as $file)
            @if (strpos($file, 'plugins') !== false)
                {!! preloadCss(getAsset($file)) !!}
            @else
                <link href="{{ getAsset($file) }}" rel="stylesheet" type="text/css"/>
            @endif
        @endforeach
        {{-- end::Global Stylesheets Bundle --}}
    @endif

    @if (theme()->getViewMode() === 'preview')
        {{ theme()->getView('partials/trackers/_ga-general') }}
        {{ theme()->getView('partials/trackers/_ga-tag-manager-for-head') }}
    @endif

    @routes
    <script src="{{ mix('/js/charts/echarts.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>
    @yield('styles')
</head>
{{-- end::Head --}}

{{-- begin::Body --}}
<body id="kt_body"
      {!! theme()->printHtmlAttributes('body') !!} {!! theme()->printHtmlClasses('body') !!} {!! theme()->printCssVariables('body') !!}
      data-kt-name="metronic" class="page-loading-enabled page-loading header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed"
      style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
<div class="page-loader">
    <span class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Carregando...</span>
    </span>
</div>
@include('partials/theme-mode/_init')

@yield('content')

{{--<div class="d-flex flex-column flex-root">--}}
{{--    <div class="page d-flex flex-row flex-column-fluid">--}}
{{--        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">--}}
            <div id="kt_header" style="" class="header align-items-stretch">
{{-- begin::Javascript --}}
@if (theme()->hasOption('assets', 'js'))
    {{-- begin::Global Javascript Bundle(used by all pages) --}}
    <script src="{{ mix('/js/essential.js') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/common/util.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        Util.initModule(null, "{{ env('APP_ENV') }}");
    </script>
    @foreach (array_unique(theme()->getOption('assets', 'js')) as $file)
        <script src="{{ getAsset($file) }}"></script>
    @endforeach
    {{-- end::Global Javascript Bundle --}}
@endif



@if (theme()->hasVendorFiles('js'))
    {{-- begin::Page Vendors Javascript(used by this page) --}}
    @foreach (array_unique(theme()->getVendorFiles('js')) as $file)
        @if(util()->isExternalURL($file))
            <script src="{{ $file }}"></script>
        @else
            <script src="{{ getAsset($file) }}"></script>
        @endif
    @endforeach
    {{-- end::Page Vendors Javascript --}}
@endif

@if (theme()->hasOption('page', 'assets/custom/js'))
    {{-- begin::Page Custom Javascript(used by this page) --}}
    @foreach (array_unique(theme()->getOption('page', 'assets/custom/js')) as $file)
        <script src="{{ getAsset($file) }}"></script>
    @endforeach
    {{-- end::Page Custom Javascript --}}
@endif
{{-- end::Javascript --}}

@if (theme()->getViewMode() === 'preview')
    {{ theme()->getView('partials/trackers/_ga-tag-manager-for-body') }}
@endif
            </div>
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@yield('scripts')
</body>
{{-- end::Body --}}
</html>
