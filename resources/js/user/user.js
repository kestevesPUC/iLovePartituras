var target = document.querySelector("body");
var blockUI = new KTBlockUI(target, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});
var validator = null;

$(document).on('change', '[name="uf"]', function(e) {
    getAddress(route('address.findCity', {'uf': [$(this).val()]}), '#profile_city', "[name='city_code']");
});

Inputmask({
    "mask" : ["(99) 9999-9999", "(99) 9 9999-9999"],
}).mask("[name='telephone_contact']");

Inputmask({
    "mask" : "99999-999",
}).mask("[name='cep']");

Inputmask({
    "keepStatic": true,
    "mask" : ['999.999.999-99', '99.999.999/9999-99'],
}).mask("[name='cpf_cnpj']");

Inputmask({
    "mask" : "99/99/9999"
}).mask("[name='birth_date'], [name='date_issui']");

$('[name="birth_date"], [name="date_issui"]').daterangepicker({
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


function tranformProfile (img) {
    let string = '';
    if(img.indexOf('data:image/jpeg;base64,') != -1){
        string = img.split("data:image/jpeg;base64,")
    }else if (img.indexOf('data:image/png;base64,') != -1){
        string = img.split("data:image/png;base64,")
    }else if(img.indexOf('data:image/jpg;base64,') != -1){
        string = img.split("data:image/jpg;base64,")
    }

    return string[1].split('")')[0]
}

function onForm (form, url) {
    let string = tranformProfile($('#teste').css("backgroundImage"));

    $("[name='profile']").val(string);

    if($('[name="cpf_cnpj"]').val().length == 14){
        validator = formEditPf
    }else{
        validator = formEditPj

    }

    validator.validate().then(function (status) {
        if (status == 'Valid') {
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
                            text: (response.responseJSON !== undefined && response.responseJSON.message !== undefined) ? response.responseJSON.message : 'Ocorreu um erro ao realizar a ação! ' + response.message,
                            icon: 'error',
                        });
                    }
                },
                error: function (error) {
                    blockUI.release()
                    Swal.fire({
                        title: 'Falha',
                        text: (error.responseJSON !== undefined && error.responseJSON.message !== undefined) ? error.responseJSON.message : 'Ocorreu um erro ao realizar a ação! ' + error.message,
                        icon: 'error',
                    });
                }
            });
        }
    });
}

$('#editProfile').on('shown.bs.modal', function (e) {
    getAddress(route('address.findState'), "#profile_uf", "[name='uf']")
    getAddress(route('address.findCity', {'uf': [$('#profile_uf').val()]}), '#profile_city', "[name='city_code']");
    getPerson($("#inf_cpf_cnpj").val());
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
    if(select == "[name='city_code']"){
        $(select).empty().append('<option value>Selecione uma Cidade</option>');
        for(let i = 0; i < value.length; i++){
            $(select).append('<option value="'+value[i].id+'" '+($(address).val() == value[i].id ? "selected" : "") +'>'+value[i].text+'</option>')
        }
    }else{
        $(select).empty().append('<option value>Selecione um Estado</option>');
        for (var key in value) {
            if (value.hasOwnProperty(key)) {
                $(select).append('<option value="'+key+'" '+($(address).val() == key ? "selected" : "") +'>'+value[key]+'</option>')
            }
        }
    }
}


var formEditPf = FormValidation.formValidation(document.getElementById('kt_account_profile_details_form'),
    {
        fields: {
            'first_name': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'cpf_cnpj': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    },
                    callback: {
                        message: 'O CPF precisa ter 11 digitos.',
                        callback: function (input) {
                            if (input.value.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-').length < 11) {
                                return false;
                            }else {
                                return true;
                            }
                        }
                    }
                }
            },
            'birth_date': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'cep': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'street': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'number': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'neighborhood': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'uf': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'city_code': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
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

var formEditPj = FormValidation.formValidation(document.getElementById('kt_account_profile_details_form'),
    {
        fields: {
            'first_name': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'cpf_cnpj': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    },
                    callback: {
                        message: 'O CNPJ precisa ter 14 digitos.',
                        callback: function (input) {
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
            },
            'name_contact': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'telephone_contact': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'cep': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'street': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'number': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'complement': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'neighborhood': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'uf': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
                    }
                }
            },
            'city_code': {
                validators: {
                    notEmpty: {
                        message: 'Não pode ser vazio.'
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

function getPerson (value) {
    if(value.length == 14){
        $("#editPf input").removeAttr('disabled');
        $("#editPf").css('display', '');
        $("#editPj input").attr('disabled', 'disabled');
        $("#editPj").css('display', 'none');
    }else{
        $("#editPj input").removeAttr('disabled');
        $("#editPj").css('display', '');
        $("#editPf input").attr('disabled', 'disabled');
        $("#editPf").css('display', 'none');
    }
}

