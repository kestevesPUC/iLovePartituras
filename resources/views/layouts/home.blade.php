<!DOCTYPE html>
<html lang="@if(!empty(Session::get('locale'))){{ Session::get('locale') }}@else pt-br @endif">

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
    <meta name="author" content="Desenvolvido por Krypton Tech"> @endif

    @if(!empty($metaTags->canonical))
    <link rel="canonical" href="{{ $metaTags->canonical }}" />@else
    <link rel="canonical" href="#" /> @endif

    <link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}" />
    <link href="{{ mix('assets/css/essential.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ mix('assets/css/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    @yield('styles')
    @routes
</head>

<body class="m-page--wide m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <header id="m_header" class="m-grid__item m-header " m-minimize="minimize" m-minimize-offset="200" m-minimize-mobile-offset="200" style="z-index:10;">
            <div class="m-header__top">
                <div class="m-container m-container--responsive m-container--full-height m-page__container bg-header-link">
                    <div class="m-stack m-stack--ver m-stack--desktop">
                        <div class="m-stack__item m-brand bg-header-link">
                            <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
                                <div class="m-stack__item m-stack__item--middle m-brand__logo">
                                    <a href="{{route('home')}}" class="m-brand__logo-wrapper">
                                        <img alt="logoAclink" class="img-fluid" src="{{URL::asset('img/'.System::getNameLogo())}}" />
                                    </a>
                                </div>
                                <div class="m-stack__item m-stack__item--middle m-brand__tools">
                                    <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                                        <span></span>
                                    </a>
                                    <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                        <i class="flaticon-more"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @include('partials.topbar')
                    </div>
                </div>
            </div>
            @include('partials.menu')
        </header>
        <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop m-container m-container--responsive  m-page__container m-body" style="padding-left: 0;">
            <button class="m-aside-left-close m-aside-left-close--skin-light" id="m_aside_left_close_btn">
                <i class="la la-close"></i>
            </button>
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
                <div class="m-content">
                    @yield('content')
                </div>
            </div>
        </div>
        <footer class="m-grid__item m-footer " style="margin-top: 0.3rem;">
            <div class="m-container m-container--responsive m-container--full-height m-page__container">
                <div class="m-footer__wrapper" style="margin-left: 10px;">
                    <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
                        <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                            <span class="m-footer__copyright">{{date('Y')}} &copy; @lang('dashboard.menu.developer_by') Krypton Tech</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div id="m_scroll_top" class="m-scroll-top">
        <i class="la la-arrow-up"></i>
    </div>
    <script src="{{ mix('/assets/js/essential.js') }}" type="text/javascript"></script>
    <script src="{{ mix('/assets/js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ mix('/assets/js/common/util.js') }}" type="text/javascript"></script>
    @stack('javascripts')
    <script type="text/javascript">
        mApp.blockPage();
        Util.initModule("{{ $metaTags->module }}", "{{ env('APP_ENV') }}")
    </script>
</body>
@toastr_render

</html>
