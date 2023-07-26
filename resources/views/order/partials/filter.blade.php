@extends('layouts.filter', [
    'id' => 'searchMarketingResearch',
    'size' => '400'
])

{{--@section('default')--}}
{{--    <div class="col-lg-12">--}}
{{--        @if(!$isMobile)--}}
{{--            <span class="position-absolute top-50 translate-middle ms-6">--}}
{{--                <i class="fa fa-search"></i>--}}
{{--            </span>--}}
{{--        @endif--}}
{{--        <input type="text" class="form-control form-control-sm form-control-solid ps-10 only_numbers" name="id" id="id_MarketingResearch" placeholder="@lang('orders.search.id_order')" autocomplete="off"/>--}}
{{--    </div>--}}
{{--@endsection--}}
@section('default')
    <div class="col-lg-12">
{{--        @if(!$isMobile)--}}
{{--            <span class="position-absolute top-50 translate-middle ms-6">--}}
{{--                <i class="fa fa-search"></i>--}}
{{--            </span>--}}
{{--        @endif--}}
        <input type="text" class="form-control form-control-sm form-control-solid ps-10 only_numbers" name="id" id="id_MarketingResearch" placeholder="Nº Pedido" autocomplete="off"/>
    </div>
@endsection

@section('advanced')
    <div class="col-lg-3">
        <label class="form-label" for="client">Cliente</label>
        <input type="text" class="form-control form-control-sm form-control-solid" id="client" name="client" placeholder="Cliente" autocomplete="off">
    </div>
    <div class="col-lg-3">
        <label class="form-label" for="protocol">CPF|CNPJ</label>
        <input type="text" class="form-control form-control-sm form-control-solid document" name="cpf_cnpj" placeholder="CPF|CNPJ" autocomplete="off">
    </div>
    <div class="col-lg-3">
        <label class="form-label" for="period">Período</label>
        <div class="input-group input-group-sm input-group-solid">
            <input type="text" class="form-control date" name="period_begin" id="period_begin"  autocomplete="off"/>
            <span class="input-group-text">Até</span>
            <input type="text" class="form-control date" name="period_end" id="period_end"  autocomplete="off"/>
        </div>
    </div>
@endsection('advanced')
