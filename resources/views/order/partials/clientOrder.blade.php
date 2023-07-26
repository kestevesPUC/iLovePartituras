<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-body">

                <div class="m-form__heading" style="padding-bottom: 2rem;">
                    <h3 class="m-form__heading-title">
                        @lang('request.edit.general_data')
                    </h3>
                </div>

                <div class="row">

                    <div class="col-4">
                        <div class="m-form__group form-group">
                            <label for="">@lang('generic.words.type')</label>
                            <div class="m-checkbox-inline">
                                <label class="m-radio">
                                    <input type="radio" value="1" disabled="disabled" {{($person->id_type == 1)?'checked':''}} />
                                    @lang('generic.words.cpf')
                                    <span></span>
                                </label>
                                <label class="m-radio">
                                    <input type="radio" value="2" disabled="disabled" {{($person->id_type == 2)?'checked':''}} />
                                    @lang('generic.words.cnpj')
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    @if($person->id_type == 1)
                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.cpf')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{\App\Helpers\Util::mask( (string) $person->cpf, "###.###.###-##")}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.birth')</label>
                            <input type="text" class="form-control data" autocomplete="off" value="{{$person->birth_date}}" disabled="disabled">
                        </div>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.name') </label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->name}}" disabled="disabled">
                        </div>
                    </div>

                    @if($person->id_type == 2)
                        <div class="col-md-4 pessoaJuridica">
                            <div class="form-group">
                                <label class="control-label">@lang('generic.words.trading_name')  </label>
                                <input type="text" class="form-control" autocomplete="off" value="{{$person->fantasy_name}}" disabled="disabled">
                            </div>
                        </div>

                        <div class="col-md-4 pessoaJuridica">
                            <div class="form-group">
                                <label class="control-label">@lang('generic.words.state_registration') </label>
                                <input type="text" class="form-control" autocomplete="off" value="{{$person->state_registration}}" disabled="disabled">
                            </div>
                        </div>

                        <div class="col-md-4 pessoaJuridica">
                            <div class="form-group">
                                <label class="control-label">@lang('generic.words.municipal_registration') </label>
                                <input type="text" class="form-control" autocomplete="off" value="{{$person->municipal_registration}}" disabled="disabled">
                            </div>
                        </div>
                    @endif

                    @if($person->id_type == 1)
                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">PIS </label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->pis}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.rg')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->rg}}" maxlength="11" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.consignor')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->issuing_agency}}" maxlength="6" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.dispatch_date')</label>
                            <input type="text" class="form-control data" autocomplete="off" value="{{$person->date_issui}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.cei') </label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->cei}}" maxlength="64" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.role')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->job}}" maxlength="50" disabled="disabled">
                        </div>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.email')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->email}}" disabled="disabled">
                        </div>
                    </div>

                    @if($person->id_type == 2)
                        <div class="col-4">
                            <div class="m-form__group form-group">
                                <label for="">@lang('generic.words.tax_substitute')</label>
                                <div class="m-checkbox-inline">
                                    <label class="m-radio">
                                        <input type="radio" value="1" disabled="disabled" {{($person->tax_substitution)?'checked':''}} />
                                        @lang('generic.words.yes')
                                        <span></span>
                                    </label>
                                    <label class="m-radio">
                                        <input type="radio" value="0" disabled="disabled" {{(!$person->tax_substitution)?'checked':''}} />
                                        @lang('generic.words.no')
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="panel panel-default">
            <div class="m-form__heading" style="padding-bottom: 2rem; padding-top: 2rem;">
                <h3 class="m-form__heading-title">
                    @lang('generic.words.contact')
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.name_contact') </label>
                            <input type="text" class="form-control telefone" autocomplete="off" value="{{$person->name_contact ?? $person->name}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.cell_phone') </label>
                            <input type="text" class="form-control telefone" autocomplete="off" value="{{$person->company_contact ?? $person->telephone_contact}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4 pessoaFisica">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.email_contact') </label>
                            <input type="text" class="form-control telefone" autocomplete="off" value="{{$person->email_contact ?? $person->email}}" disabled="disabled">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="m-form__heading" style="padding-bottom: 2rem; padding-top: 2rem;">
                <h3 class="m-form__heading-title">
                    @lang('generic.words.address')
                </h3>
            </div>
            <div class="panel-body endereco">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.cep')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->cep}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.address')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->street}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.number')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->number}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.complement') </label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->complement}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.district')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->neighborhood}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.state')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->uf}}" disabled="disabled">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">@lang('generic.words.municipality')</label>
                            <input type="text" class="form-control" autocomplete="off" value="{{$person->city_desc}}" disabled="disabled">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
