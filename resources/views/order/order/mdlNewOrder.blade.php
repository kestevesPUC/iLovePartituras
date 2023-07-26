@extends('layouts.modal', [
    'id' => 'newOrder',
    'title' => 'Novo Pedido',
    'size' => 'modal-xl',
    'modalBody' => 'newOrderBoddy',
    'modalButton' => 'newOrderFooter',
    'showButton' => false

])

@section('newOrderBoddy')
    @include('layout.demo10.wizard')
@endsection

