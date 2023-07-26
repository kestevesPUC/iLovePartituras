const form = document.getElementById('kt_stepper_example_basic_form');
var validator = null;
var success = true;

Inputmask({
    "mask" : "(99) 99999-9999",
}).mask("#charge_telephone");

Inputmask({
    "mask" : "99/99/9999"
}).mask("#kt_daterangepicker_3");

Inputmask({
    "mask" : "99/99/9999"
}).mask("#biling_date_expiration");

Inputmask({
    "mask" : "99999-999",
}).mask("#charge_cep");

Inputmask({
    "mask" : "9999-9999-9999-9999",
}).mask("#card_number");

Inputmask({
    "mask" : "999",
}).mask("#card_cvv");

Inputmask({
    "keepStatic": true,
    "mask" : ['999.999.999-99', '99.999.999/9999-99'],
}).mask("#charge_cpf_cnpj");


function makeOrder (form) {
    validator = paymentForm;
    validator.validate().then(function (status) {
        if (status == 'Valid') {
            if($('[name="type_payment"]:checked').val() == 2){
                validator = cardForm;
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        sendOrder(form)
                    }
                });
            }else{
                sendOrder(form);
            }
        }
    });

}

function sendOrder (form) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : route('order.makeOrder'),
        type : 'POST',
        dataType: 'JSON',
        cache: false,
        data : {
            data: JSON.stringify($(form).serializeArray())
        },
        beforeSend: function () { 	blockUI.block(); },
        success : function(response){
            blockUI.release()
            if (response.success) {
                let text = response.message;
                if (response.success && $('[name="type_payment"]:checked').val() == 1) {
                    text += ' <br><br> Clique no botão abaixo para gerar seu boleto: <br>';
                    button = '<i class="la la-print"></i> Imprimir Boleto</a>';
                } else if (response.success && $('[name="type_payment"]:checked').val() == 2) {
                    text += '<br><br> Seu pedido foi processado com sucesso!';
                } else if (response.success && $('[name="type_payment"]:checked').val() == 4) {
                    text += ' <br><br> Clique no botão abaixo para gerar seu QR Code: <br>';
                    button = '<i class="la la-print"></i> Gerar QR Code</a>';
                }
                Swal.fire({
                    title: "Confirmação",
                    text: text,
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonText: button
                }).then(function(result) {
                    if (result.value) {
                        window.open(response.url, '_blank');
                    }
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Falha',
                    text: (response.responseJSON !== undefined && response.responseJSON.message !== undefined) ? response.responseJSON.message : 'Ocorreu um erro ao processar seu pedido! ' + response.message,
                    icon: 'error',
                });
            }
        },
        error: function (error) {
            blockUI.release()
            Swal.fire({
                title: 'Falha',
                text: (error.responseJSON !== undefined && error.responseJSON.message !== undefined) ? error.responseJSON.message : 'Ocorreu um erro ao processar seu pedido! ' + error.message,
                icon: 'error',
            });
        }
    });
}

function onForm (form, url) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : url,
        type : 'POST',
        dataType: 'JSON',
        cache: false,
        data : {
            data: JSON.stringify($(form).serializeArray())
        },
        beforeSend: function () { 	blockUI.block(); },
        success : function(response){
            blockUI.release()

            if (response.success) {
                Swal.fire({
                    title: 'Sucesso',
                    text: response.message,
                    icon: 'success',
                }).then(res => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Falha',
                    text: (response.responseJSON !== undefined && response.responseJSON.message !== undefined) ? response.responseJSON.message : 'Ocorreu um erro ao processar seu pedido! ' + response.message,
                    icon: 'error',
                });
            }
        },
        error: function (error) {
            blockUI.release()
            Swal.fire({
                title: 'Falha',
                text: (error.responseJSON !== undefined && error.responseJSON.message !== undefined) ? error.responseJSON.message : 'Ocorreu um erro ao processar seu pedido! ' + error.message,
                icon: 'error',
            });
        }
    });
}

// Stepper lement
var element = document.querySelector("#kt_stepper_example_basic");

// Initialize Stepper
var stepper = new KTStepper(element);

// Handle next step
stepper.on("kt.stepper.next", function (stepper) {
    if(stepper.currentStepIndex == 1){
        generateExcelComapany();
        stepper.goNext();
    }else if (stepper.currentStepIndex == 2){
        validator = orderForm;
        valid(stepper);
    }else if(stepper.currentStepIndex == 3){
        validator = chargeForm;
        valid(stepper);
    }
});

// Handle previous step
stepper.on("kt.stepper.previous", function (stepper) {
    stepper.goPrevious(); // go previous step
});

$('#kt_daterangepicker_3, #biling_date_expiration, #manual_date').daterangepicker({
    "singleDatePicker": true,
    "locale": {
      "format": "DD/MM/YYYY",
      "separator": " - ",
      "applyLabel": "Aplicar",
      "cancelLabel": "Cancelar",
      "fromLabel": "De",
      "toLabel": "Até",
      "customRangeLabel": "Custom",
      "daysOfWeek": [
          "Dom",
          "Seg",
          "Ter",
          "Qua",
          "Qui",
          "Sex",
          "Sáb"
      ],
      "monthNames": [
          "Janeiro",
          "Fevereiro",
          "Março",
          "Abril",
          "Maio",
          "Junho",
          "Julho",
          "Agosto",
          "Setembro",
          "Outubro",
          "Novembro",
          "Dezembro"
      ],
      "firstDay": 0
    }
});


$("[name='type_payment']").change(function () {
    if(this.value == 2){
        $("#card_div").css('display', '')
        $("#biling_expiration").css('display', 'none')
    }else if(this.value == 1){
        $("#biling_expiration").css('display', '')
        $("#card_div").css('display', 'none')
    }else {
        $("#biling_expiration").css('display', 'none')
        $("#card_div").css('display', 'none')
    }
})

function generateExcelComapany () {
    $.ajax({
        url : route('order.download'),
        type : 'GET',
        dataType: 'JSON',
        data : {
            classInput: $('#classInput').val(),
            fileName : 'Realatório Empresas',
            corporateName: $('#inptRazaoSocial').val(),
            cnpj: $('#inptCnpj').val(),
            cep: $('#inptCep').val(),
            state: $('select[name="state"]').find(':selected').val(),
            cityName: $('select[name="city"]').find(':selected').text(),
            city_code: $('select[name="city"]').find(':selected').val(),
            neighborhood: $('#inptBairro').val(),
            type_establishment: $('select[name="type_establishment"]').find(':selected').text(),
            simple: $('select[name="simple"]').find(':selected').text(),
            postage: $('select[name="postage"]').find(':selected').text(),
            phone: $('#inptTel').val(),
            email: $('#inptEmail').val(),
            openDateBegin: $('#openDateBegin').val(),
            openDateEnd: $('#openDateEnd').val(),
            inptCapitalMin: $('#inptCapitalMin').val(),
            inptCapitalMax: $('#inptCapitalMax').val(),
            cnae: $('select[name="cnae"]').find(':selected').val(),
        },
        beforeSend: function () { 	blockUI.block(); },
        success : function(response){
            let value = response.lines * 0.03
            $("#order_lines_text").html(response.lines)
            $("#order_value_text").html(parseFloat(value).toFixed(2))
            $("#order_lines").val(response.lines)
            $("#order_value").val(parseFloat(value).toFixed(2))
            $("#name_file").val(response.file)
        },
        error: function (error) {
            blockUI.release()
        },
        complete: function () {
            blockUI.release()
        }
    });
}

var orderForm = FormValidation.formValidation(
    form,
    {
        fields: {
            'order_value': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

var chargeForm = FormValidation.formValidation(
    form,
    {
        fields: {
            'charge_cpf_cnpj': {
                validators: {
                    notEmpty: {
                        message: 'Campo CPF / CNPJ, não pode ser vazio.'
                    },
                    callback: {
                        message: 'O CPF / CNPJ precisa ter 11 / 14 digitos.',
                        callback: function (input) {
                            if (input.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').length < 11) {
                                return false;
                            }else {
                                if(input.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').length == 11){
                                    return true;
                                }

                                if (input.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').length < 14) {
                                    return false;
                                }else {
                                    return true;
                                }
                            }
                        }
                    }
                }
            },
            'charge_name': {
                validators: {
                    notEmpty: {
                        message: 'Campo nome, não pode ser vazio.'
                    }
                }
            },
            'charge_email': {
                validators: {
                    notEmpty: {
                        message: 'Campo email, não pode ser vazio.'
                    },
                    emailAddress: {
						message: 'Este não é um email valido.'
					}
                }
            },
            'charge_birth': {
                validators: {
                    notEmpty: {
                        message: 'Campo data de nascimento, não pode ser vazio.'
                    },
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'Não é uma data valida.',
                    }
                }
            },
            'charge_telephone': {
                validators: {
                    notEmpty: {
                        message: 'Campo telefone, não pode ser vazio.'
                    },
                    phone: {
						country: 'BR',
						message: 'Este não é um telefone valido.'
					}
                }
            },
            'charge_cep': {
                validators: {
                    notEmpty: {
                        message: 'Campo CEP, não pode ser vazio.'
                    }
                }
            },
            'charge_street': {
                validators: {
                    notEmpty: {
                        message: 'Campo rua, não pode ser vazio.'
                    }
                }
            },
            'charge_number': {
                validators: {
                    notEmpty: {
                        message: 'Campo numero, não pode ser vazio.'
                    }
                }
            },
            'charge_neighborhood': {
                validators: {
                    notEmpty: {
                        message: 'Campo bairro, não pode ser vazio.'
                    }
                }
            },
            'charge_city': {
                validators: {
                    notEmpty: {
                        message: 'Campo Cidade, não pode ser vazio.'
                    }
                }
            },
            'charge_state': {
                validators: {
                    notEmpty: {
                        message: 'Campo estado, não pode ser vazio.'
                    }
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

var paymentForm = FormValidation.formValidation(
    form,
    {
        fields: {
            'type_payment': {
                validators: {
                    notEmpty: {
                        message: 'Escolha uma forma de pagamento.'
                    }
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

var cardForm = FormValidation.formValidation(
    form,
    {
        fields: {
            'card_name': {
                validators: {
                    notEmpty: {
                        message: 'Campo nome do cartão, não ser vazio.'
                    }
                }
            },
            'card_number': {
                validators: {
                    notEmpty: {
                        message: 'Campo numero do cartão, não ser vazio.'
                    },
                    creditCard: {
						message: 'Este não é um cartão valido.'
					}
                }
            },
            'card_expiry_month': {
                validators: {
                    notEmpty: {
                        message: 'Campo mes, não ser vazio.'
                    }
                }
            },
            'card_expiry_year': {
                validators: {
                    notEmpty: {
                        message: 'Campo ano, não ser vazio.'
                    }
                }
            },
            'card_cvv': {
                validators: {
                    notEmpty: {
                        message: 'Campo CVV, não ser vazio.'
                    },
                    digits: {
						message: 'Este não é um CVV valido.'
					}
                }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

var getPersonForm = FormValidation.formValidation(
    form,
    {
        fields: {
            'charge_cpf_cnpj': {
                validators: {
                    notEmpty: {
                        message: 'Campo CPF / CNPJ, não pode ser vazio.'
                    },
                    callback: {
                        message: 'O CPF / CNPJ precisa ter 11 / 14 digitos.',
                        callback: function (input) {
                            if (input.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').length < 11) {
                                return false;
                            }else {
                                if(input.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').length == 11){
                                    return true;
                                }

                                if (input.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').length < 14) {
                                    return false;
                                }else {
                                    return true;
                                }
                            }
                        }
                    }
                }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

function valid (stepper) {
    // Validate form before submit
    validator.validate().then(function (status) {
        if (status == 'Valid') {
            stepper.goNext();
        }
    });
};


async function validGetPerson (cpf_cnpj) {
    try
    {
        validator = getPersonForm;

        const valid = await validator.validate();

        if(valid === "Valid")
        {
            const response = await getPerson(cpf_cnpj);
            if(response.success){
                console.log(response.person.uf, response.person)
                $("[name='charge_name']").val(response.person.name)
                $("[name='charge_email']").val(response.person.email)
                $("[name='charge_birth']").val(response.person.birth_date_br)
                $("[name='charge_telephone']").val(response.person.telephone_contact)
                $("[name='charge_cep']").val(response.person.cep)
                $("[name='charge_street']").val(response.person.street)
                $("[name='charge_number']").val(response.person.number)
                $("[name='charge_neighborhood']").val(response.person.neighborhood)
                $("[name='charge_complement']").val(response.person.complement)
                $("[name='charge_state']").val(response.person.uf).change();
                $("[name='charge_city']").val(response.person.city)

                getAddress(route('address.findCity', {'uf': [response.person.uf]}), response.person.code_city, "[name='charge_city']");

            }else {
                $("[name='charge_name']").val("")
                $("[name='charge_email']").val("")
                $("[name='charge_birth']").val("")
                $("[name='charge_telephone']").val("")
                $("[name='charge_cep']").val("")
                $("[name='charge_street']").val("")
                $("[name='charge_number']").val("")
                $("[name='charge_neighborhood']").val("")
                $("[name='charge_complement']").val("")
            }
        }
    }
    catch(e)
    { console.log(e); }
}

$("[name='charge_state']").on('change', function (e) {
    getAddress(route('address.findCity', {'uf': [$(this).val()]}), 0, "[name='charge_city']");
})

function getAddress (url, address, select) {

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : url,
        type : 'GET',
        dataType: 'JSON',
        cache: false,
        success : function(response){
            createOption(response.cities, address, select)
            if (response.success) {

            } else {
                Swal.fire({
                    title: 'Falha',
                    text: (response.responseJSON !== undefined && response.responseJSON.message !== undefined) ? response.responseJSON.message : 'Ocorreu um erro ao realizar a ação! ' + response.message,
                    icon: 'error',
                });
            }
        },
        error: function (error) {
            Swal.fire({
                title: 'Falha',
                text: (error.responseJSON !== undefined && error.responseJSON.message !== undefined) ? error.responseJSON.message : 'Ocorreu um erro ao realizar a ação! ' + error.message,
                icon: 'error',
            });
        },
        complete: function () {
        }
    });
}

function createOption (value, address, select) {
    $(select).empty().append('<option value>Selecione uma Cidade</option>');
    for(let i = 0; i < value.length; i++){
        $(select).append('<option value="'+value[i].id+'" '+(address == value[i].id ? "selected" : "") +'>'+value[i].text+'</option>')
    }
}
