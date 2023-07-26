@extends('layouts.modal', [
    'id' => 'mdlTicket',
    'title' => 'Boleto',
    'size' => 'modal-xl',
    'modalBody' => 'bodyTicket',
    'modalButton' => 'buttonTicket'
])

@section('bodyTicket')
    <div class="row">
        <div class="col-lg-6">
{{--        @if(\App\Helpers\System::checkActionPermission(11))--}}
                <a type="button" id="btnPrintSlip" className="btn btn-sm btn-info" href=""  target="_blank" disabled="">
                    <i className="fas fa-barcode"></i>Imprimir boleto</a>
{{--            @include('orders.partials.menu-button', [--}}
{{--                'id' => 'btnPrintSlip',--}}
{{--                'btnColor' => 'btn-info',--}}
{{--                'href' => route('newcompanie.marketingResearch.generateTicket', ['code' => encrypt(1396716)]), //número do pedido--}}
{{--                'icon' => 'fas fa-barcode',--}}
{{--                'name' => 1 == \App\Helpers\Constants\SystemConstants::PAYMENT_TYPE_BILLET ? 'Imprimir boleto' : 'Imprimir PIX', // 1 -> Boleto--}}
{{--            ])--}}
{{--        @endif--}}
        </div>
{{--        <div class="col-lg-6">--}}
{{--            <label class="form-label" for="inptVencimento">@lang('bills.labels.due_date')</label>--}}
{{--            <input type="text" class="form-control form-control-sm form-control-solid " id="inptVencimento" name="inptVencimento" autocomplete="off" disabled>--}}
{{--        </div>--}}
    </div>

@endsection

