@extends('base.base')

@section('style')
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @include('layout.demo1.partials._toolbar')
    @include('layout.demo1.partials._header')

    @include('user.partials.basicInformation')
    @include('user.partials.AdditionalInfomation')
    @include('user.partials.addressInformation')

    @include('user.modal.editProfile')
    @include('layout.demo1.partials._footer')
@endsection

@section('scripts')
    <script src="{{ mix('/js/user/user.js') }}"></script>
@endsection
