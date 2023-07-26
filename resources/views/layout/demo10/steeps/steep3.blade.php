<div class="flex-column" data-kt-stepper-element="content">
    <div class="card-body">
        <h3 class="font-size-lg text-dark font-weight-bold mb-6">1. Dados pessoais:</h3>
        <div class="form-group row">
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>CPF / CNPJ:</label>
                <input type="text" name="charge_cpf_cnpj" id="charge_cpf_cnpj" class="form-control" placeholder="CPF / CNPJ" onblur="validGetPerson(this.value)" />
                <span class="form-text text-muted">Por favor digite seu cpf ou cnpj.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Nome Completo:</label>
                <input type="text" name="charge_name" class="form-control" placeholder="Nome completo"/>
                <span class="form-text text-muted">Por favor digite seu nome completo.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Email:</label>
                <input type="text" name="charge_email" id="charge_email" class="form-control" placeholder="Email"/>
                <span class="form-text text-muted">Por favor digite seu email</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Data de nascimento:</label>
                <input class="form-control form-control-solid" name="charge_birth" placeholder="Pick date rage" id="kt_daterangepicker_3"/>
                <span class="form-text text-muted">Por favor digite sua data de nascimento.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Telefone:</label>
                <input type="text" name="charge_telephone" id="charge_telephone" class="form-control" placeholder="Telefone"/>
                <span class="form-text text-muted">Por favor digite seu telefone</span>
            </div>
        </div>
        <h3 class="font-size-lg text-dark font-weight-bold mb-6">2. Endereço de cobrança:</h3>
        <div class="form-group row">
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Cep:</label>
                <input type="text" name="charge_cep" id="charge_cep" class="form-control" placeholder="CEP"/>
                <span class="form-text text-muted">Por favor digite seu cep.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Rua:</label>
                <input type="text" name="charge_street" class="form-control" placeholder="Rua"/>
                <span class="form-text text-muted">Por favor informe o nome da sua rua</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Numero:</label>
                <input type="text" name="charge_number" class="form-control" placeholder="Numero"/>
                <span class="form-text text-muted">Por favor digite o numero da casa.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Bairro:</label>
                <input type="text" name="charge_neighborhood" class="form-control" placeholder="Bairro"/>
                <span class="form-text text-muted">Por favor digite o nome do bairro.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Complemento:</label>
                <input type="text" name="charge_complement" class="form-control" placeholder="Complemento"/>
                <span class="form-text text-muted">Caso opte por digitar um complemento.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Estado:</label>
                <select name="charge_state" class="form-control selectpicker">
                    @foreach ($states as $key => $state)
                        <option value="{{$key}}">{{$state}}</option>
                    @endforeach
                </select>
                <span class="form-text text-muted">Por favor selecione um estado.</span>
            </div>
            <div class="col-lg-4 mb-5 fv-row mb-10 fv-plugins-icon-container">
                <label>Cidade:</label>
                <select name="charge_city" class="form-control selectpicker"></select>
                <span class="form-text text-muted">Por favor selecione uma cidade.</span>
            </div>
        </div>
    </div>
</div>
