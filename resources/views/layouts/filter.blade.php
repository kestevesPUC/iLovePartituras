<div class="card">
    <div class="card-body">
        <form class="form" id="{{ $id }}">
            <div class="d-flex align-items-center'">
                <div class="position-relative min-w-{{ $size }}px me-2">
                    <div class="row g-8">
                        <div class="col-xxl-12">
                            <div class="row g-5">
                                @yield('default')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-success applySearch me-2" id="apply">Aplicar</button>
                    <button type="reset" class="btn btn-sm btn-danger me-5 cleanSearchForm" id="clean">Limpar</button>
                    @if(!isset($advanced))
                        <a id="kt_horizontal_search_advanced_link" class="btn btn-link collapsed" data-bs-toggle="collapse" href="#kt_advanced_search_form" aria-expanded="false">Filtros Avançados</a>
                    @endif
                </div>
            </div>
            @if(!isset($advanced))
                <div class="collapse" id="kt_advanced_search_form" style="">
                    <div class="separator separator-dashed mt-9 mb-6"></div>
                    <div class="row g-5">
                        @yield('advanced')
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
