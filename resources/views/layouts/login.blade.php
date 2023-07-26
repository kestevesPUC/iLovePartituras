<!DOCTYPE html>

<html lang="en">

<head><base href="../../../">
    <title>Autenticação | AC Link</title>
    <meta charset="utf-8" />
    <meta name="description" content="Sistema de gestão de pedidos certificado Link Certificação Digital" />
    <meta name="keywords" content="Certificado Digital, Gestão de Pedidos, AC LINK" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="AC Link - Gestão de Pedidos" />
    <meta property="og:url" content="{{route('login')}}" />
    <meta property="og:site_name" content="AC Link | Gestão de Pedidos" />
    <link rel="canonical" href="{{route('login')}}" />
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ mix('assets/css/essential.css')}}" rel="stylesheet" type="text/css" />
    <style media="screen">
        .m-login__certificado {
            display: none;
        }
        .m-login.m-login--1.m-login--certificado .m-login__certificado {
            display: block;
        }
        .m-login.m-login--1.m-login--certificado .m-login__signin {
            display: none;
        }
        .m-login.m-login--1.m-login--certificado .m-login__forget-password {
            display: none;
        }
        .m-login.m-login--1.m-login--certificado .m-login__signup {
            display: none;
        }
        .flipInX {
            -webkit-backface-visibility: visible !important;
            backface-visibility: visible !important;
            -webkit-animation-name: flipInX;
            animation-name: flipInX;
        }
        .fadeIn {
            -webkit-animation-name: fadeIn;
            animation-name: fadeIn;
        }
        .animated {
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }
    </style>
    @routes
</head>
<body id="kt_body" class="page-loading-enabled page-loading bg-body">
<div class="page-loader">
    <span class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Carregando...</span>
    </span>
</div>
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-color: {{(app()->environment('production') ? '#0d4e9c' : '#7271c7')}}">
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                <div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
                    <span href="" class="py-9 mb-5">
                        <img alt="Logo" src="{{ URL::asset('img/logo.png') }}" class="h-60px" />
                    </span>
                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #FFFFFF;">Gestão de Pedidos</h1>
                    <p class="fw-bold fs-2" style="color: #FFFFFF;">Uma nova plataforma
                        <br />para você se surpreender</p>
                </div>
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(assets/media/illustrations/sketchy-1/13-Nova.png"></div>
            </div>
        </div>
        <div class="d-flex flex-column flex-lg-row-fluid py-10">
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <div class="w-lg-500px p-10 p-lg-15 mx-auto m-login m-login--1" id="m-login">
                    <div class="m-login__signin">
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" >
                            @csrf
                            <input type="hidden" name="previous" value="{{ URL::previous() }}">
                            <div class="text-center mb-10">
                                @if(!app()->environment('production'))
                                    <h1 class="text-dark mb-3 bg-google">AMBIENTE DE HOMOLOGAÇÃO</h1>
                                @endif
                                <h1 class="text-dark mb-3">Sistema AC Link</h1>
                                <div class="text-gray-400 fw-bold fs-4">Faça seu login abaixo</div>
                            </div>
                            <div class="fv-row mb-10">
                                <label class="form-label fs-6 fw-bolder text-dark">Login</label>
                                <input class="form-control form-control-lg form-control-solid" type="text" name="nome" autocomplete="off" tabindex="1" autofocus />
                            </div>
                            <div class="fv-row mb-10">
                                <div class="d-flex flex-stack mb-2">
                                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Senha</label>
                                    <a href="" class="link-primary fs-6 fw-bolder" tabindex="4">Esqueceu a senha?</a>
                                </div>
                                <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" tabindex="2" />
                            </div>
                            <div class="text-center">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5" tabindex="3">
                                    <span class="indicator-label">Logar</span>
                                    <span class="indicator-progress">Por favor aguarde...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <div class="text-center text-muted text-uppercase fw-bolder mb-5">ou</div>

                                <a href="javascript:" id="m_login_signin_certificado" class="btn btn-success btn-flex flex-center btn-lg w-100 mb-5">
                                    Logar com certificado digital</a>
                            </div>
                        </form>
                    </div>
                    <div class="m-login__certificado" id="m-login__certificado">
                        <form class="form w-100" novalidate="novalidate" id="authForm" method="post">
                            @csrf
                            <input type="hidden" name="previous" value="{{ URL::previous() }}">
                            <div class="text-center mb-10">
                                <h1 class="text-dark mb-3">Sistema AC Link</h1>
                                <div class="text-gray-400 fw-bold fs-4">Escolha o seu certificado digital</div>
                            </div>
                            <div class="fv-row mb-10">
                                <select id="certificateSelect" name="certificado" class="form-control-lg selectpicker" style="width: 100%;"></select>
                                <input type="hidden" name="token" value="{{$token}}">
                            </div>
                            <div class="text-center">
                                <button type="button" id="m_login_login_certificado_submit" class="btn btn-lg btn-primary w-100 mb-5" tabindex="3">
                                    <span class="indicator-label">Logar</span>
                                    <span class="indicator-progress">Por favor aguarde...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <div class="text-center text-muted text-uppercase fw-bolder mb-5">ou</div>

                                <a href="javascript:" id="m_login_login_certificado_refresh" class="btn btn-success btn-flex flex-center btn-lg w-100 mb-5">
                                    Recarregar Certificados</a>
                                <a href="javascript:" id="m_login_login_certificado_cancel" class="btn btn-danger btn-flex flex-center btn-lg w-100 mb-5">
                                    Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('/assets/js/essential.js') }}" type="text/javascript"></script>
<script src="{{ mix('/assets/js/login.js') }}" type="text/javascript"></script>

</body>

</html>
