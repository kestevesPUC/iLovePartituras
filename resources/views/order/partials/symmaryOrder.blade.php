<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">@lang('request.edit.summary')</span>
        </h3>
    </div>
</div>
<div class="card">
    <div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <div class="form-group">
                            <label class="control-label">@lang('request.edit.order_number')</label>
                            <input type="text" class="form-control" id="id" disabled="disabled" value="{{$order->id}}">
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="form-group">
                            <label class="control-label">@lang('request.edit.date')</label>
                            <input type="text" class="form-control text-center" id="data" disabled="disabled" value="{{\Carbon\Carbon::parse($order->created_at)->format('d/m/Y')}}">
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="form-group">
                            <label class="control-label">@lang('request.edit.total_value')</label>
                            <input type="text" class="form-control text-right" id="valor_total" disabled="disabled" value="R$ {{$order->charge_value}}">
                        </div>
                    </div>

                    <div class="col-md-8 mt-3">
                        <div class="form-group">
                            <label for="nome" class="control-label">@lang('request.edit.client')</label>
                            <input type="text" placeholder="Nome" class="form-control" id="nome" name="nome" autocomplete="off" value="{{$person->name}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 mt-3">
                        <div class="form-group">
                            <label class="control-label">@lang('request.edit.payment_method')</label>
                            <input type="text" class="form-control" id="valor_total" disabled="disabled" value="{{$order->payment_option == 1 ? "BOLETO" : ($order->payment_option == 2 ? "CARTÃO DE CREDITO" : "PIX")}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="padding-top: 20px">
                    <a class="btn btn-block alert {{$order->status == 1 ? "bg-info" : ($order->status == 2 ? "bg-success" : "bg-danger")}} bg-success w-100"
                       style="height: 125px; padding-top: 40px; margin-bottom: 0; font-size: 36px;">
                       <p style="color:white">{{$status}}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
