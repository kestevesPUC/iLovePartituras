@extends('layouts.modal', [
    'id' => 'lowOrder',
    'title' => 'Baixa Manual',
    'size' => 'modal-lg',
    'modalBody' => 'bodyManualLowOrder',
    'modalButton' => 'buttonManualLowOrder'
])

@section('bodyManualLowOrder')
    <form id="formManualLowOrder" class="form" autocomplete="off" novalidate="novalidate" method="POST">
        @csrf
        <input type="hidden" name="manual_order" value="{{$order->id}}"/>
        <div class="form-group row">
            <div class="mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Tipo:</label>
                <select id="manual_reason" name="manual_reason" class="form-control">
                    <option value="">Selecione</option>
                    <option value="1">Normal</option>
                    <option value="2">Funcionãrio</option>
                    <option value="3">Contigência</option>
                    <option value="4">Bonificação</option>
                    <option value="5">Projeto Exterior</option>
                </select>
                <span class="form-text text-muted">Selecione o tipo de baixa.</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Data:</label>
                <input class="form-control form-control-solid" id="manual_date" name="manual_date" placeholder="Pick date rage" />
                <span class="form-text text-muted">Data da baixa.</span>
            </div>
            <div class="col-lg-6 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Valor:</label>
                <input type="text" class="form-control" id="manual_value" name="manual_value" placeholder="Digite o valor da baixa" value="{{$order->charge_value}}"/>
                <span class="form-text text-muted">Digite o valor da baixa.</span>
            </div>
        </div>
        <div class="mb-5 fv-row mb-10 fv-plugins-icon-container">
            <label>Descrição:</label>
            <textarea class="form-control" id="manual_description" name="manual_description" placeholder="Explique o motivo da baixa" rows="3"></textarea>
            <span class="form-text text-muted">Explique o motivo da baixa.</span>
        </div>
        <button type="button" class="btn btn-light-success" onclick="onForm(this.form, '{{route('order.manual.order', [])}}')">Emitir</button>
    </form>
@endsection


