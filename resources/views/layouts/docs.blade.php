<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8" />
	<title>{{System::getName()}} | {{$nameSystem}}</title>
	<meta name="description" content="Latest updates and statistic charts">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}" />
	<link href="{{ mix('assets/css/essential.css')}}" rel="stylesheet" type="text/css" />
	@yield('styles')
	<style>
		.col {
			width: auto;
		}
		.swagger-ui .wrapper {
			max-width: 100%;
		}
		.swagger-ui .info {
			margin: 0 0 50px 0;
		}
	</style>
</head>

<body class="m-page--wide m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<header id="m_header" class="m-grid__item m-header " m-minimize="minimize" m-minimize-offset="200" m-minimize-mobile-offset="200">
			<div class="m-header__top">
				<div class="m-container m-container--responsive m-container--full-height m-page__container">
					<div class="m-stack m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-brand">
							<div class="m-stack m-stack--ver m-stack--general m-stack--inline">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="/{{System::getPrefix()}}" class="m-brand__logo-wrapper">
										<img alt="" src="{{URL::asset('img/'.System::getNameLogo())}}" style="max-width: 160px; max-height: 80px;"/>
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
						<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
							<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-topbar__nav-wrapper language-dropdown">
									@include('OLD.partials.language')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="m-header__bottom">
				<div class="m-container m-container--responsive m-container--full-height m-page__container">
					<div class="m-stack m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
							<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
									<i class="la la-close"></i>
								</button>
							<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">
								<ul class="m-menu__nav m-menu__nav--submenu-arrow "></ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop 	m-container m-container--responsive  m-page__container m-body" style="padding-left: 0;">
			<button class="m-aside-left-close m-aside-left-close--skin-light" id="m_aside_left_close_btn">
				<i class="la la-close"></i>
			</button>
			<div class="m-grid__item m-grid__item--fluid m-wrapper">
				<div class="m-content">
					@yield('content')
				</div>
			</div>
		</div>
		<footer class="m-grid__item m-footer ">
			<div class="m-container m-container--responsive m-container--full-height m-page__container">
				<div class="m-footer__wrapper" style="margin-left: 0;">
					<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
						<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
							<span class="m-footer__copyright">{{date('Y')}} &copy; @lang('dashboard.menu.developer_by') AssinarWeb</span>
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

	@yield('javascripts')
	<script>
		sistemaAssinador = JSON.parse('<?php echo json_encode(Prefix::getAllPrefix());?>');
	</script>
</body>

</html>
