@extends('base.base')

@section('content')
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                {{ theme()->getView('layout/partials/_header') }}
                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    {{ theme()->getView('layout/partials/_sidebar') }}
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <div class="d-flex flex-column flex-column-fluid">
                                <div class="card rounded mx-10 mb-10">
                                    @include('layouts.filter',['id' => 'search_sheet', 'size' => '400', 'advanced' => true])
                                </div>
                                <div class="card rounded mx-10 mb-10">
                                    @include('home.partials.file-pdf')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
@endsection
