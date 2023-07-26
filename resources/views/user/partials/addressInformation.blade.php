<div class="px-10">
    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <!--begin::Card header-->
        <div class="card-header cursor-pointer">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Endereço</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->

        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Rua</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-semibold text-gray-800 fs-6">{{$person->street ? $person->street : 'Não informado pelo usuario.'}}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Numero</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{$person->number ? $person->number : 'Não informado pelo usuario.'}}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Complemento</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{$person->complement ? $person->complement : 'Não informado pelo usuario.'}}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">
                    Bairro
                    <span class="ms-1" data-bs-toggle="tooltip" aria-label="Phone number must be active" data-bs-original-title="Phone number must be active" data-kt-initialized="1">
                        <i class="ki-outline ki-information fs-7"></i>
                    </span>
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 d-flex align-items-center">
                    <span class="fw-semibold text-gray-800 fs-6 me-2">{{$person->neighborhood ? $person->neighborhood : 'Não informado pelo usuario.'}}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Cidade</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <a href="#" class="fw-semibold text-gray-800 fs-6">{{$person->city ? $person->city : 'Não informado pelo usuario.'}}</a>
                    <input type="hidden" id="profile_city" value="{{$person->code_city ?? 0}}">
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Estados</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <a href="#" class="fw-semibold text-gray-800 fs-6">{{$person->uf ? $person->uf : 'Não informado pelo usuario.'}}</a>
                    <input type="hidden" id="profile_uf" value="{{$person->uf ?? 0}}">
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">
                    CEP
                    <span class="ms-1" data-bs-toggle="tooltip" aria-label="Country of origination" data-bs-original-title="Country of origination" data-kt-initialized="1">
                        <i class="ki-outline ki-information fs-7"></i>
                    </span>
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-semibold text-gray-800 fs-6">{{$person->cep ? $person->cep : 'Não informado pelo usuario.'}}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
</div>
