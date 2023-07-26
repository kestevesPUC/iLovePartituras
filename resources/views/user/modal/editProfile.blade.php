@extends('layouts.modal', [
    'id' => 'editProfile',
    'title' => 'Editar Perfil',
    'size' => 'modal-xl',
    'modalBody' => 'editProfileBoddy',
    'modalButton' => 'editProfileFooter',
])

@section('editProfileBoddy')
    <div class="card mb-5 mb-xl-10">
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form id="kt_account_profile_details_form" class="form fv-plugins-bootstrap5 fv-plugins-framework fv-row" novalidate="novalidate">
                <!--begin::Card body-->
                <div class="card-body p-9">
                    <input type="hidden" name="id_person" value="{{$person->id}}">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Foto de Perfil</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true"
                                style="background-image: url('/metronic8/demo32/assets/media/svg/avatars/blank.svg')">
                                <!--begin::Preview existing avatar-->
                                <div id="teste" class="image-input-wrapper w-125px h-125px"
                                    style="background-image: url(data:image/jpeg;base64,{{$person->img_profile}})">
                                </div>
                                <!--end::Preview existing avatar-->

                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar"
                                    data-bs-original-title="Trocar Foto" data-kt-initialized="1">
                                    <i class="bi bi-pencil"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="profile">
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->

                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                                    data-bs-original-title="Remover Foto" data-kt-initialized="1">
                                    <i class="bi bi-x-lg"></i> </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->

                            <!--begin::Hint-->
                            <div class="form-text">Extensões permitidas: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nome completo</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="first_name"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="First name" value="{{$person->name}}">
                            <span class="form-text text-muted">Por favor informe o nome completo.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">CPF / CNPJ</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="cpf_cnpj"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="Digite o CPF / CNPJ" value="{{$person->cpf ?? $person->cnpj}}" onblur="getPerson(this.value)">
                                <span class="form-text text-muted">Por favor informe o CPF / CNPJ.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <div id="editPf" style="display: none;">
                        @include('user.partials.editInfPf')
                    </div>

                    <div id="editPj" style="display: none;">
                        @include('user.partials.editInfPj')
                    </div>


                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6 required">
                            <span class="">Telefone de contato</span>
                            <span class="ms-1" data-bs-toggle="tooltip" aria-label="Phone number must be active"
                                data-bs-original-title="Phone number must be active" data-kt-initialized="1">
                                <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i></span> </label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="tel" name="telephone_contact" class="form-control form-control-lg form-control-solid"
                                placeholder="Phone number" value="{{$person->company_contact ?? $person->telephone_contact}}">
                                <span class="form-text text-muted">Por favor informe o numero de contato.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">CEP</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="cep" class="form-control form-control-lg form-control-solid"
                                placeholder="Cep" value="{{$person->cep}}">
                                <span class="form-text text-muted">Por favor informe o cep.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Rua</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="street" class="form-control form-control-lg form-control-solid"
                                placeholder="Nome da rua" value="{{$person->street}}">
                                <span class="form-text text-muted">Por favor informe o nome da rua.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Numero</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="number" class="form-control form-control-lg form-control-solid"
                                placeholder="Numero da casa" value="{{$person->number}}">
                                <span class="form-text text-muted">Por favor informe o numero da residencia.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Complemento</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="complement" class="form-control form-control-lg form-control-solid"
                                placeholder="Complemento" value="{{$person->complement}}">
                                <span class="form-text text-muted">Por favor informe o complemento da residencia.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Bairro</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <input type="text" name="neighborhood" class="form-control form-control-lg form-control-solid"
                                placeholder="Nome do Bairro" value="{{$person->neighborhood}}">
                                <span class="form-text text-muted">Por favor informe o nome do bairro.</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Estado</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <!--begin::Input-->
                            <select name="uf" class="form-control selectpicker" >
                                <option>selecione um estado</option>
                            </select>

                            <div class="form-text">
                                Selecione um Estado.
                            </div>
                            <!--end::Hint-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Cidade</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row fv-plugins-icon-container">
                            <!--begin::Input-->
                            <select name="city_code" class="form-control selectpicker"></select>

                            <div class="form-text">
                                Selecione uma Cidade.
                            </div>
                            <!--end::Hint-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                </div>
                <!--end::Card body-->
                <input type="hidden">
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
@endsection

@section('editProfileFooter')
    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit" onclick="onForm('#kt_account_profile_details_form', '{{route('profile.update', ['userId' => $person->id_user])}}')">Save Changes</button>
@endsection
