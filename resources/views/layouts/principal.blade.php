<!DOCTYPE html>

<html lang="pt-br">

<head>
    <meta charset="utf-8" />
{{--    <meta content='maximum-scale=1.0, initial-scale=1.0, width=device-width' name='viewport'>--}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTags->title ?? 'Gestão AC' }}</title>
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
    {{--    <link href="{{ mix('assets/css/toastr.min.css')}}" rel="stylesheet" type="text/css" />--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css"/>
    @yield('styles')
    @if(!app()->environment('production'))
        <style>
            @media (min-width: 992px) {
                .menu-item, .menu-title, .menu-icon i {
                    color: #ffffff !important;
                }
                .menu-item:hover .menu-title, .menu-item:hover .menu-icon i {
                    color: #5E6278 !important;
                }
            }
        </style>
    @endif
    @routes
</head>

<body id="kt_body" class="page-loading-enabled page-loading @if(!isset($showMenu))header-fixed header-tablet-and-mobile-fixed @if(!isset($showSubHeader)) toolbar-enabled toolbar-fixed @endif @endif" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
<div class="page-loader">
    <span class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Carregando...</span>
    </span>
</div>
<div class="d-flex flex-column flex-root">
    <div class="page d-flex flex-row flex-column-fluid">
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            @if(!isset($showMenu))
            <div id="kt_header" style="" class="header align-items-stretch">
                <div class="container-xxl d-flex align-items-stretch justify-content-between @if(!app()->environment('production')) bg-info @endif">
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
                        <a href="{{ route('home') }}">
                            <img alt="Logo" src="{{ route('view.logo', Session::get('usuario.logo', ContractConstants::CNPJ_LINK)) }}" class="h-20px h-lg-30px" />
                        </a>
                    </div>
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                        <div class="d-flex align-items-stretch" id="kt_header_nav">
                            @include('layouts.principal-menu')
                        </div>
                        <div class="d-flex align-items-stretch flex-shrink-0">
                            <div class="d-flex align-items-stretch flex-shrink-0">

                                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                        <img src="{{ route('view.photo', Auth::id()) }}" alt="user" id="logoUser"/>
                                    </div>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <div class="cursor-pointer symbol symbol-50px me-5">
                                                    <img alt="Logo" id="avatarChange" src="{{ route('view.photo', Auth::id()) }}" />
                                                    <input type="file" id="changeAvatar" data-id="{{ Auth::id() }}" class="d-none" name="avatar" accept=".png, .jpg, .jpeg" />
                                                </div>
                                                <div class="d-flex flex-column">

                                                    <div class="fw-bolder d-flex align-items-center fs-5 max-w-200px" style="width: 80%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ Session::get('usuario.nome') }}
                                                    </div>
                                                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{ Session::get('usuario.email') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
                                            <a href="#" class="menu-link px-5">
                                                <span class="menu-title position-relative">Language
                                                <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">Português
                                                <img class="w-15px h-15px rounded-1 ms-2" src="{{ URL::asset('assets/media/flags/brazil.svg') }}" alt="" /></span></span>
                                            </a>
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <div class="menu-item px-3">
                                                    <a href="../../demo1/dist/account/settings.html" class="menu-link d-flex px-5 ">
															<span class="symbol symbol-20px me-4">
																<img class="rounded-1" src="{{ URL::asset('assets/media/flags/united-states.svg') }}" alt="" />
															</span>Inglês</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="../../demo1/dist/account/settings.html" class="menu-link d-flex px-5 active">
															<span class="symbol symbol-20px me-4">
																<img class="rounded-1" src="{{ URL::asset('assets/media/flags/brazil.svg') }}" alt="" />
															</span>Português</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="menu-item px-5 my-1">
                                            <a href="{{ route('users.view', Session::get('usuario.id')) }}" class="menu-link px-5">Account Settings</a>
                                        </div>
                                        <div class="menu-item px-5">
                                            <a href="{{ route('logout') }}" class="menu-link px-5">Sair</a>
                                        </div>
{{--                                        <div class="separator my-2"></div>--}}
{{--                                        <div class="menu-item px-5">--}}
{{--                                            <div class="menu-content px-5">--}}
{{--                                                <label class="form-check form-switch form-check-custom form-check-solid pulse pulse-success" for="kt_user_menu_dark_mode_toggle">--}}
{{--                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="1" name="mode" id="kt_user_menu_dark_mode_toggle" data-kt-url="../../demo1/dist/index.html" />--}}
{{--                                                    <span class="pulse-ring ms-n1"></span>--}}
{{--                                                    <span class="form-check-label text-gray-600 fs-7">Dark Mode</span>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
                                    <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
                                        <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="black" />
                                                <path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="black" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                @if(!isset($showSubHeader))
                    <div class="toolbar" id="kt_toolbar">
                        <div id="kt_toolbar_container" class="container-fluid {{ $isMobile ? '' : 'd-flex flex-stack' }}">
                            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">@lang("system.init.crumb." . $metaTags->crumbTitle)</h1>
                                <span class="h-20px border-gray-300 border-start mx-4"></span>
                                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                                    <li class="breadcrumb-item text-muted">
                                        <a href="{{ route('home') }}" class="text-muted text-hover-primary">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                                    </li>
                                    <li class="breadcrumb-item text-dark">@lang("system.init.sub_crumb." . $metaTags->crumbSubTitle)</li>
                                </ul>
                            </div>
                            <div class="{{ $isMobile ? '' : 'd-flex align-items-center gap-2 gap-lg-3' }}">
                                @if($isMobile)
                                <div class="row g-2">
                                    @yield('buttons')
                                </div>
                                @else
                                    @yield('buttons')
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <div class="post d-flex flex-column-fluid" id="kt_post">
                    <div id="kt_content_container" class="container-xxl">
                        @yield('content')
                    </div>
                </div>
            </div>

            <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="text-dark order-2 order-md-1">
                        <span class="text-muted fw-bold me-1">{{date('Y')}} &copy;</span>
                        <a href="https://kryptontech.com.br" target="_blank" class="text-gray-800 text-hover-primary">@lang('dashboard.menu.developer_by') Krypton Tech</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@isset($idVideoTutorial)
    @include('helptutorial.button_help_tutorials', [
        'idVideoTutorial' => $idVideoTutorial
    ])
@endisset

@yield('modals')
@include('partials.util.croppie')
@include('manuals.index')
@include('dashboard.modals.tutorialsMdl')

<script src="{{ mix('/assets/js/essential.js') }}" type="text/javascript"></script>
<script src="{{ mix('/assets/plugins/flatpickr.js') }}" type="text/javascript"></script>
<script src="{{ mix('/assets/js/common/util.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>
@stack('javascripts')
<script type="text/javascript">
    Manuals.init();
    Tutorials.init();
    Util.initModule("{{ $metaTags->module }}", "{{ env('APP_ENV') }}")
</script>
</body>
</html>
