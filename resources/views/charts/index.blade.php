@extends('base.base')


@section('content')
    @include('layout.demo1.partials._toolbar')

{{--{{ theme()->getView('layout/partials/_sidebar') }}--}}
    @include('layout.demo1.partials._header')
    <div class="card mb-5 mb-xl-10 ">
        <span class="indicator-progress">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
        <div class="card-header border-0">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Exportar empresas:</span>
            </h3>
            <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                 data-kt-initialized="1">
                <a type="button" class="btn btn-sm btn-light btn btn-success exportarXlsx_chart" id="exportarXlsx_chart"
                   data-bs-toggle="modal" data-bs-target="#newOrder">
                        <span class="svg-icon svg-icon-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                      transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"></rect>
                            </svg>
                        </span>
                    Novo Pedido
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card p-15 mb-5 mb-xl-10">
            <div class="justify-content-between">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 align-items-end">
                        @include('charts.echarts.map')
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12 align-items-center">
                        @include('charts.echarts.bar')
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 align-items-center">
                        @include('charts.echarts.pie')
                    </div>
                </div>
            </div>
        </div>
        <div class="card p-15 mb-5 mb-xl-10">
            <div class="justify-content-between">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 align-items-start">
                        @include('charts.table')
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12 align-items-center">
                        @include('charts.echarts.bar-race')
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 align-items-start">
                        @include('charts.echarts.bar-race2')
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    @include('layout.demo1.partials._footer')--}}
    @include('order.order.mdlNewOrder')
@endsection

@section('scripts')
    {{--    <script src="{{ mix('/assets/js/EssentialTables.js') }}" type="text/javascript"></script>--}}
    <script src="{{ mix('/assets/js/EssentialTables.js') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/charts/echarts.js') }}"></script>
    <script src="{{ mix('/js/charts/charts.js') }}"></script>
    <script src="{{ mix('/js/order/order.js') }}"></script>
    <script>Order.init()</script>
    <script>CHARTS.init();</script>
    <script>DATA.init();</script>
@endsection
