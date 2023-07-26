@extends('base.base')
@section('style')
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @include('layout.demo1.partials._toolbar')
    @include('layout.demo1.partials._header')
    <div class="">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Exportar Empresas</span>
                </h3>
                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"data-kt-initialized="1">
                    <a type="button" class="btn btn-sm btn-light btn btn-success exportarXlsx" data-bs-toggle="modal"
                       data-bs-target="#newOrder">
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
    </div>

    <div class="card rounded mx-10 mb-10">
        @include('order.partials.filter')
    </div>

    <div class="card rounded p-10 mx-10 mb-10">
        @include('order.partials.table')
    </div>

    @include('order.order.mdlNewOrder')
    @include('layout.demo1.partials._footer')

@endsection


@section('scripts')
    <script src="{{ mix('/assets/js/EssentialTables.js') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/order/order.js') }}"></script>
    <script src="{{ mix('/js/common/util.js') }}"></script>
    <script>Order.init()</script>
@endsection


