@extends('base.base')
@section('style')
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @include('layout.demo1.partials._toolbar')
    @include('layout.demo1.partials._header')

    <div class="card">
        <div class="card-header card-header-stretch">
            <h3 class="card-title">@lang('request.edit.order_data')</h3>
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">@lang('request.edit.summary')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">@lang('request.edit.client')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">@lang('request.edit.issue_invoice')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">@lang('request.edit.payment')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">@lang('request.edit.hystoric')</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card m-10">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    @include('order.partials.symmaryOrder', ['person' => $client, 'order' => $order])
                </div>

                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                    @include('order.partials.clientOrder', ['person' => $client])
                </div>

                <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                    @include('order.partials.clientOrder', ['person' => $emission])
                </div>

                <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                    @include('order.partials.nfeOrder', ['order' => $order])
                </div>

                <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                    @include('order.partials.historyOrder', ['history' => $history])
                </div>
            </div>
        </div>
    </div>

    @include('order.modals.lowOrder')
    @include('layout.demo1.partials._footer')

@endsection


@section('scripts')
    <script src="{{ mix('/assets/js/EssentialTables.js') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/order/order.js') }}"></script>
    <script src="{{ mix('/js/common/util.js') }}"></script>
@endsection


