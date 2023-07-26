<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Data de Nascimento</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input class="form-control form-control-solid" name="birth_date" placeholder="Data de nascimento" value="{{\Carbon\Carbon::parse($person->birth_date)->format('d/m/Y')}}"/>
        <span class="form-text text-muted">Por favor digite sua data de nascimento.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Numero RG</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="rg"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Digite o numero do RG" value="{{$person->rg}}">
            <span class="form-text text-muted">Por favor informe o numero do RG.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Orgão Expedidor</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="issuing_agency"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Digite o nome do Orgão Expedidor" value="{{$person->issuing_agency}}">
            <span class="form-text text-muted">Por favor informe o orgão Expedidor.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Data de Expedição</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input class="form-control form-control-solid" name="date_issui" placeholder="Data de nascimento" value="{{\Carbon\Carbon::parse($person->date_issui)->format('d/m/Y')}}"/>
        <span class="form-text text-muted">Por favor informe a data de expedição.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">CEI</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="cei"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Digite o numero do CEI" value="{{$person->cei}}">
            <span class="form-text text-muted">Por favor informe o CEI.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Ocupação</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="job"
            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
            placeholder="Digite sua ocupação" value="{{$person->job}}">
            <span class="form-text text-muted">Por favor informe o seu cargo.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-4 col-form-label fw-semibold fs-6">Empresa</label>
    <!--end::Label-->

    <!--begin::Col-->
    <div class="col-lg-8 fv-row fv-plugins-icon-container">
        <input type="text" name="company" class="form-control form-control-lg form-control-solid"
            placeholder="Company name" value="{{$person->company}}">
            <span class="form-text text-muted">Por favor informe o nome da empresa.</span>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->
