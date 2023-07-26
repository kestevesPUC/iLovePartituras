<div class="flex justify-content-between" data-kt-stepper-element="content">
    <div class="radio radio-outline radio-success ">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label fv-row">
                        <input type="radio" name="type_payment" value="1" style="margin: 3px;"/>Boleto
                        <small>Pagamento via Boleto</small>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <div class="" id="biling_expiration" style="display: none;">
                    <label>Data de vencimento:</label>
                    <input class="form-control form-control-solid" name="biling_date_expiration" placeholder="Pick date rage" id="biling_date_expiration"/>
                    <span class="form-text text-muted">Digite a data de vencimento do boleto.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="radio radio-outline radio-success">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label fv-row">
                        <input type="radio" name="type_payment" value="4" style="margin: 3px;"/>Pix
                        <small>Pagamento via Pix</small>
                    </h3>
                </div>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
    <div class="radio radio-outline radio-success">
        <div class="card card-custom gutter-b">
            <div class="flex justify-content-center card-header">
                <div class="card-title">
                    <h3 class="card-label fv-row">
                        <input type="radio" name="type_payment" value="2" style="margin: 3px;"/>Cartão
                        <small>Pagamento via Cartão</small>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <div id="card_div" style="display: none;">
                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                            <span class="required">Nome do Cartão</span>
                            <span class="ms-1" data-bs-toggle="tooltip" aria-label="Specify a card holder's name" data-bs-original-title="Specify a card holder's name" data-kt-initialized="1">
                            </span>
                        </label>
                        <!--end::Label-->
                        <input type="text" class="form-control form-control-solid" placeholder="Digite o nome do cartão" name="card_name" value="">
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row fv-plugins-icon-container">
                        <!--begin::Label-->
                        <label class="required fs-6 fw-semibold form-label mb-2">Numero do Cartão</label>
                        <!--end::Label-->
                        <!--begin::Input wrapper-->
                        <div class="position-relative">
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-solid" placeholder="Digite o numero do cartão" name="card_number" id="card_number" value="">
                            <!--end::Input-->
                            <!--begin::Card logos-->
                            <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                                <img src="https://preview.keenthemes.com/metronic8/demo1/assets/media/svg/card-logos/visa.svg" alt="" class="h-25px">
                                <img src="https://preview.keenthemes.com/metronic8/demo1/assets/media/svg/card-logos/mastercard.svg" alt="" class="h-25px">
                                <img src="https://preview.keenthemes.com/metronic8/demo1/assets/media/svg/card-logos/american-express.svg" alt="" class="h-25px">
                            </div>
                            <!--end::Card logos-->
                        </div>
                        <!--end::Input wrapper-->
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <div class="row mb-10" data-select2-id="select2-data-128-pd27">
                        <!--begin::Col-->
                        <div class="col-md-8 fv-row" data-select2-id="select2-data-127-9cpe">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold form-label mb-2">Data de Expiração</label>
                            <!--end::Label-->
                            <!--begin::Row-->
                            <div class="row fv-row fv-plugins-icon-container" data-select2-id="select2-data-126-j08j">
                                <!--begin::Col-->
                                <div class="col-6" data-select2-id="select2-data-125-qvxg">
                                    <select name="card_expiry_month" class="form-select form-select-solid select2-hidden-accessible" data-control="select2" data-hide-search="true" data-placeholder="Mês" data-select2-id="select2-data-16-to57" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                        <option data-select2-id="select2-data-18-ipom"></option>
                                        <option value="1" data-select2-id="select2-data-131-b28d">1</option>
                                        <option value="2" data-select2-id="select2-data-132-cy5o">2</option>
                                        <option value="3" data-select2-id="select2-data-133-jgn2">3</option>
                                        <option value="4" data-select2-id="select2-data-134-d39a">4</option>
                                        <option value="5" data-select2-id="select2-data-135-7kbi">5</option>
                                        <option value="6" data-select2-id="select2-data-136-9eid">6</option>
                                        <option value="7" data-select2-id="select2-data-137-b9pg">7</option>
                                        <option value="8" data-select2-id="select2-data-138-ggpq">8</option>
                                        <option value="9" data-select2-id="select2-data-139-qm8p">9</option>
                                        <option value="10" data-select2-id="select2-data-140-418a">10</option>
                                        <option value="11" data-select2-id="select2-data-141-50me">11</option>
                                        <option value="12" data-select2-id="select2-data-142-7axf">12</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-6" data-select2-id="select2-data-144-ireb">
                                    <select name="card_expiry_year" class="form-select form-select-solid select2-hidden-accessible" data-control="select2" data-hide-search="true" data-placeholder="Ano" data-select2-id="select2-data-19-kcgv" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                        <option data-select2-id="select2-data-21-rqvf"></option>
                                        <option value="2023" data-select2-id="select2-data-145-tlcc">2023</option>
                                        <option value="2024" data-select2-id="select2-data-146-mf0f">2024</option>
                                        <option value="2025" data-select2-id="select2-data-147-edy4">2025</option>
                                        <option value="2026" data-select2-id="select2-data-148-ns5c">2026</option>
                                        <option value="2027" data-select2-id="select2-data-149-16kd">2027</option>
                                        <option value="2028" data-select2-id="select2-data-150-p3g1">2028</option>
                                        <option value="2029" data-select2-id="select2-data-151-pmsx">2029</option>
                                        <option value="2030" data-select2-id="select2-data-152-07vw">2030</option>
                                        <option value="2031" data-select2-id="select2-data-153-gwjj">2031</option>
                                        <option value="2032" data-select2-id="select2-data-154-o6rj">2032</option>
                                        <option value="2033" data-select2-id="select2-data-155-8hce">2033</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">CVV</span>
                                <span class="ms-1" data-bs-toggle="tooltip" aria-label="Enter a card CVV code" data-bs-original-title="Enter a card CVV code" data-kt-initialized="1">
                                </span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input wrapper-->
                            <div class="position-relative">
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" minlength="3" maxlength="4" placeholder="CVV" name="card_cvv" id="card_cvv">
                                <!--end::Input-->
                                <!--begin::CVV icon-->
                                <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <!--end::CVV icon-->
                            </div>
                            <!--end::Input wrapper-->
                        <div class="fv-plugins-message-container invalid-feedback"></div></div>
                        <!--end::Col-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
