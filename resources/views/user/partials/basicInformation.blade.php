<div class="px-10">
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <!--begin::Details-->
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <!--begin: Pic-->
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <img src="{{ route('profile.get.picture', ['idUser' => Auth::id()]) }}" alt="image">
                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 @if ($person->logged) bg-success @else bg-danger @endif rounded-circle border border-4 border-body h-20px w-20px"></div>
                    </div>
                </div>
                <!--end::Pic-->

                <!--begin::Info-->
                <div class="flex-grow-1">
                    <!--begin::Title-->
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <!--begin::User-->
                        <div class="d-flex flex-column">
                            <!--begin::Name-->
                            <div class="d-flex align-items-center mb-2">
                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{$person->name}}</a>
                                @if ($person->email_verified_at)
                                    <h1 class=" bi bi-patch-check text-success"></h1>
                                @endif
                            </div>
                            <!--end::Name-->
                            <!--begin::Info-->
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-outline ki-profile-circle fs-4 me-1"></i>{{$person->job}}
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-outline ki-geolocation fs-4 me-1"></i>{{$person->city}}, {{$person->uf}}
                                </a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <i class="ki-outline ki-sms fs-4 me-1"></i>{{$person->email}}
                                </a>
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::User-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <!--begin::Card header-->
        <div class="card-header cursor-pointer">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Detalhes do Perfil</h3>
            </div>
            <!--end::Card title-->
            @if (Auth::id() == $person->id_user || App\Helpers\Util::getTypeAdministrator())
                <!--begin::Action-->
                <a type="button" data-bs-toggle="modal" data-bs-target="#editProfile" class="btn btn-sm btn-primary align-self-center">Editar Perfil</a>
                <!--end::Action-->
            @endif

        </div>
        <!--begin::Card header-->

        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Nome Completo</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-semibold text-gray-800 fs-6">{{$person->name}}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">CPF / CNPJ</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-semibold text-gray-800 fs-6">{{$person->cpf ? \App\Helpers\Util::setMaskCpfCnpj($person->cpf) : ($person->cnpj ? \App\Helpers\Util::setMaskCpfCnpj($person->cnpj) : 'Não informado pelo usuario.')}}</span>
                </div>
                <input type='hidden' id="inf_cpf_cnpj" value="{{$person->cpf ? \App\Helpers\Util::setMaskCpfCnpj($person->cpf) : ($person->cnpj ? \App\Helpers\Util::setMaskCpfCnpj($person->cnpj) : "000.000.000-00")}}" />
                <!--end::Col-->
            </div>
            <!--end::Row-->

            @if ($person->cpf)
                <!--begin::Row-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-semibold text-muted">Data de Nascimento</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <span class="fw-semibold text-gray-800 fs-6">{{\Carbon\Carbon::parse($person->birth_date)->format('d/m/Y') ??  'Não informado pelo usuario.'}}</span>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Input group-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-semibold text-muted">Empresa</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{$person->company ?? 'Não informado pelo usuario.'}}</span>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-10">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-semibold text-muted">Ocupação</label>
                    <!--begin::Label-->

                    <!--begin::Label-->
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-800">{{$person->job ?? 'Não informado pelo usuario.'}}</span>
                    </div>
                    <!--begin::Label-->
                </div>
                <!--end::Input group-->

            @else
                <!--begin::Input group-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-semibold text-muted">Web Site</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{$person->website ?? 'Não informado pelo usuario.'}}</a>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-lg-4 fw-semibold text-muted">Nome para Contato</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <span class="fw-semibold text-gray-800 fs-6">{{$person->name_contact ?? 'Não informado pelo usuario.'}}</span>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
            @endif

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Formas de Contato</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-semibold text-gray-800 fs-6">Email: {{$person->email ?? 'Não informado pelo usuario.'}}<br/> Telefone: {{$person->telephone_contact ? \App\Helpers\Util::setMaskPhone($person->telephone_contact) : 'Não informado pelo usuario.'}}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
</div>
