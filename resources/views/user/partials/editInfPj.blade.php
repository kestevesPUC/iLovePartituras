<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Inscrição Estadual</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="state_registration"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Por favor informe inscrição estadual " value="{{$person->state_registration}}">
            <span class="form-text text-muted">Por favor informe inscrição estadual.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Inscrição Municipal</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="municipal_registration"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Por favor informe inscrição municipal" value="{{$person->municipal_registration}}">
            <span class="form-text text-muted">Por favor informe inscrição municipal.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Nome fantasia</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="fantasy_name"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Por favor informe o nome fantasia" value="{{$person->fantasy_name}}">
            <span class="form-text text-muted">Por favor informe o nome fantasia.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<div class="row mb-6">

    <label class="col-lg-4 col-form-label fw-semibold fs-6">Substituição Tributaria</label>

    <div class="col-lg-8 d-flex align-items-center">
        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
            <input class="form-check-input w-45px h-30px" type="checkbox" id="allowmarketing" name="tax_substitution" @if ($person->tax_substitution) checked="" @endif value="1">
            <label class="form-check-label" for="allowmarketing"></label>
        </div>
    </div>
</div>

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Web Site</label>
    <!--end::Label-->

    <!--begin::Col-->
    <div class="col-lg-8 fv-row">
        <input type="text" name="website" class="form-control form-control-lg form-control-solid"
            placeholder="Website" value="{{$person->website}}">
        <span class="form-text text-muted">Por favor informe o seu website.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Nome para Contato</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="name_contact"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Por favor informe o nome para contato" value="{{$person->name_contact}}">
            <span class="form-text text-muted">Por favor informe o nome para contato.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->
