<div class="flex-column current" data-kt-stepper-element="content">
    <input type="hidden" name="classInput" value="CompanyReport" id="classInput">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="nome">Razão Social</label>
                <input type="text" class="form-control" id="inptRazaoSocial" placeholder="Nome">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="nome">CPF | CNPJ</label>
                <input type="text" class="form-control datepickerRel document" id="inptCnpj" placeholder="00.000.000/0001-00">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="inptCep">CEP</label>
                <input type="text" class="form-control datepickerRel slctXlsx zipcode address" placeholder="00.000-000" id="inptCep">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="representation_report">Estado</label>
            {{ Form::select('state', $states, null, ['class' => 'form-select form-select-solid', 'data-control' => 'select2', 'multiple' => 'multiple', 'data-dropdown-parent' => '#newOrder']) }}
        </div>
        <div class="col-md-4">
            <label class="form-label" for="period">Município</label>
            {{ Form::select('city', ['' => 'Todos'], null, ['class' => 'form-select form-select-solid', 'data-control' => 'select2', 'multiple' => 'multiple', 'data-dropdown-parent' => '#newOrder']) }}
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="inptBairro">Bairro</label>
                <input type="text" class="form-control slctXlsx datepickerRel" id="inptBairro" placeholder="Bairro">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="period">Tipo Estabelecimento</label>
            {{ Form::select('type_establishment', ['' => 'Todos', 1 => 'Matriz', 2 => 'Filial'], null, ['class' => 'form-select form-select-solid slctXlsx', 'data-control' => 'select2', 'data-dropdown-parent' => '#newOrder']) }}
        </div>
        <div class="col-md-4">
            <label class="form-label" for="value">Simples</label>
            {{ Form::select('simple', ['' => 'Todos', 'S' => 'Sim', 'N' => 'Não'], null, ['class' => 'form-select form-select-solid slctXlsx', 'data-control' => 'select2', 'data-dropdown-parent' => '#newOrder']) }}
        </div>
        <div class="col-md-4">
            <label class="form-label" for="value">Porte</label>
            {{ Form::select('postage', ['' => 'Todos', 1 => 'MEI', 2 => 'ME', 3 => 'EPP', 4 => 'DEMAIS', 5 => 'Não Informado'], null, ['class' => 'form-select form-select-solid slctXlsx', 'data-control' => 'select2', 'multiple' => 'multiple', 'data-dropdown-parent' => '#newOrder']) }}
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="inptTel">Telefone</label>
                <input type="text" class="form-control datepickerRel slctXlsx phone phone_group" id="inptTel" placeholder="(xx) xxxxx-xxxx">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="inptEmail">E-mail</label>
                <input type="text" class="form-control slctXlsx datepickerRel" id="inptEmail" placeholder="Email">
            </div>
        </div>
    </div>
    <div class="row p-3">
        <div class="col-md-5">
            <label class="form-label" for="value">Período de Abertura</label>
            <div class="input-group input-group-sm input-group-solid">
                <input type="text" class="form-control flatpickr-input date slctXlsx" name="openDateBegin" id="openDateBegin" autocomplete="off" autocomplete="off"/>
                <span class="input-group-text">à</span>
                <input type="text" class="form-control flatpickr-input date slctXlsx" name="openDateEnd" id="openDateEnd" autocomplete="off" autocomplete="off"/>
            </div>
        </div>

        <div class="col-md-5">
            <label class="form-label" for="value">Capital Social</label>
            <div class="input-group input-group-sm input-group-solid">
                <input type="text" class="form-control currency slctXlsx" name="inptCapitalMin" id="inptCapitalMin" autocomplete="off" autocomplete="off"/>
                <span class="input-group-text">à</span>
                <input type="text" class="form-control currency slctXlsx" name="inptCapitalMax" id="inptCapitalMax" autocomplete="off" autocomplete="off"/>
            </div>
        </div>
        <div class="col-md-12">
            <label class="form-label" for="value">Descrição do CNAE</label>
            {{ Form::select('cnae', $cnae, null, ['class' => 'form-select form-select-solid', 'data-control' => 'select2', 'multiple' => 'multiple', 'data-dropdown-parent' => '#newOrder']) }}
        </div>
        <div class="col-md-12">
            <label class="form-label" for="value">Natureza Jurídica</label>
            {{ Form::select('legalNature', $legalNature, null, ['class' => 'form-select form-select-solid', 'data-control' => 'select2', 'multiple' => 'multiple', 'data-dropdown-parent' => '#newOrder']) }}
        </div>
    </div>
</div>
