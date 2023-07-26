//== Class definition

// On document ready
var target = document.querySelector("body");
var blockUI = new KTBlockUI(target, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});

KTUtil.onDOMContentLoaded(function() {
    Inputmask({
        "keepStatic": true,
        "mask" : '999.999.999-99',
    }).mask(".cpf");

    Inputmask({
        "keepStatic": true,
        "mask" : ['999.999.999-99', '99.999.999/9999-99'],
    }).mask(".document");

    Inputmask({
        "keepStatic": true,
        "mask" : ['(99) 9999-9999', '(99) 99999-9999', '+99 (99) 9999-9999', '+99 (99) 99999-9999'],
    }).mask(".phone");

    Inputmask("R$ 999.999,99", {
        "numericInput": true,
        "rightAlignNumerics": true
    }).mask(".currency");

    Inputmask("99/99/9999", {
        "keepStatic": true,
    }).mask(".date");

    Inputmask({
        "keepStatic": true,
        "mask" : '99.999-999',
    }).mask(".zipcode");

    // $(".negative-money").maskMoney({
    //     "unmasked": false,
    //     "decimal": ",",
    //     "thousands": ".",
    //     "affixesStay": true,
    //     "allowEmpty": true,
    //     "allowNegative" : true
    // }).addClass('text-end');

    // $(".money").filter('input:not([readonly])').maskMoney({
    //     "unmasked": false,
    //     "decimal": ",",
    //     "thousands": ".",
    //     "affixesStay": true,
    //     "allowEmpty": true,
    //     "allowZero": true,
    // }).addClass('text-end');

    // $(".money-brl").maskMoney({
    //     // "unmasked": false,
    //     "decimal": ",",
    //     "thousands": ".",
    //     "affixesStay": true,
    //     "allowEmpty": true,
    //     "prefix": "R$ ",
    // }).addClass('text-end');

    // $('.only_numbers').on('input', function () {
    //     $(this).val($(this).val().replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'));
    // });


    // Photo.init();
    // Flatpickr.init();

    // $(document).on('select2:open', (e) => {
    //     $(e.target).parent().find('.select2-search__field').focus();
    // });
});

const Flatpickr = function () {

    // const flatpickr = () => {
    //     $(".flatpickr-input").flatpickr({
    //         dateFormat: "d/m/Y",
    //         locale: "pt",
    //         allowInput: true,
    //     });
    // };

    const inputTime = () => {
        // $(".flatpickr-input-time").flatpickr({
        //     enableTime: true,
        //     dateFormat: "d/m/Y H:i",
        //     locale: "pt",
        // });
    };

    const inputOnlyTime = () => {
        // $(".flatpickr-input-only-time").flatpickr({
        //     enableTime: true,
        //     noCalendar: true,
        //     dateFormat: "H:i",
        //     locale: "pt",
        //     plugins: [new confirmDatePlugin({
        //         confirmText: 'OK',
        //         showAlways: true,
        //     })]
        // });
    };

    const inputOnlyMonth = () => {
        // $(".flatpickr-input-only-mouth").flatpickr({
        //     locale: "pt",
        //     static: true,
        //     altInput: true,
        //     plugins: [new monthSelectPlugin({shorthand: false, dateFormat: "Y-m-d", altFormat: "M Y"})]
        // });
    };

    return {
        init: () => {
            flatpickr();
            inputTime();
            inputOnlyTime();
            inputOnlyMonth();
        }
    }
}();

var BootstrapSelect = {
    init: function init() {
        $(".m_selectpicker").selectpicker({
             noneSelectedText: Lang.get('system.labels.select')
        });
    }
};

var Util = function () {

    const setMaskMoney = function () {
        $(".money").filter('input:not([readonly])').maskMoney({
            "unmasked": true,
            "decimal": ",",
            "thousands": ".",
            "affixesStay": true,
            "allowEmpty": true,
        }).addClass('text-end');
    }

    const setMaskNegativeMoney = function () {
        $(".money").maskMoney({
            "unmasked": false,
            "decimal": ",",
            "thousands": ".",
            "affixesStay": true,
            "allowEmpty": true,
            "allowNegative" : true
        }).addClass('text-end');
    }

    var initLanguage = function() {
        Lang.setLocale(common/util.js);
        $("#language").msDropdown();
        $("#language").change(function() {
            mApp.blockPage(Lang.get('system.load_language'));
            $("#formLanguage").submit();
        });
    };

    var realMoneyToDatabase = function(value) {
        return parseFloat(value.replace(/\./g, '').replace(/,/g, '.'));
    }

    var handleRequestErrors = function(errors) {
        var errorText = '';
        if(errors.errors) {
            $.each(errors.errors, function(fild, value) {
                errorText += '<ul>';
                $.each(value, function(ix, vl) {
                    errorText += '<li>' + vl + '</li>';
                });
                errorText += '</ul>';
            });
        } else {
            errorText = errors.message;
        }
        swal.fire(Lang.get('system.modal_messages.error_message'), errorText, "error");
    };

    var handleRequestSuccess = function(response, fnc) {
        Swal.fire({
            title: Lang.get('system.labels.success'),
            html: response.message,
            icon: 'success',
            timer: 3000
        }).then(() => {
            //window.location.reload();
        });
    };

    //FORM VALIDATION FUNCTIONS
    var applyFormValidation = function(validate, form, rules, messages) {
        if (validate === false) {
            $.validator.addMethod("cRequired", $.validator.methods.required, ' ');
            $.validator.addClassRules("cRequired", {cRequired: true});

            validate = form.validate({
                onsubmit: false,
                ignore: ":disabled,:hidden, .asdasdasd",
                debug: true,
                highlight: function highlight(element) {
                    $(element).closest('div').addClass('has-danger');
                    $(element).addClass('is-invalid');
                },
                unhighlight: function unhighlight(element) {
                    $(element).removeClass('is-invalid');
                    $(element).closest('div').removeClass('has-danger');
                },
                submitHandler: function submitHandler() {
                    return true;
                },
                invalidHandler: function invalidHandler(event, validator) {
                    $(event.target).find('.text-alert').empty().append("Dados inválidos, tente novamente.");
                    validator.errorList[0].element.focus();
                }
            });

            // $("select").on("select2:close", function (e) {
            //     $(this).valid();
            // });
        }
        validate.settings.rules = rules;
        validate.settings.messages = messages;

        return validate;
    };

    var clearAllFieldsOnAForm = function clearAllFieldsOnAForm(form) {
        $(form).find(".form-control").val("");
        $(form).find(".select2").select2().val("");
        $(form).find(".select2").select2().trigger("refresh");
    };

    var mergeRulesOrMessagesOfValidation = function mergeRulesOrMessagesOfValidation(objFirst, objSecond) {
        return Object.assign(objFirst, objSecond);
    };

    var insertErrorMessagesInFormFields = function insertErrorMessagesInFormFields(form, errors) {
        $.each(errors, function (index, value) {
            $.each(value, function (idx, val) {
                form.find('.wrap_' + index).find('span.m-form__help').text(val);
                if (form.find("select[name='" + index + "']").hasClass("select2")) {
                    form.find("[name='" + index + "']").next().find('.select2-selection').addClass('border-danger').focus();
                } else {
                    form.find("[name='" + index + "']").addClass("border-danger").focus();
                }
            });
        });
        return false;
    };

    var removeErrorMessagesInFormFields = function(form) {
        form.find(".form-control").removeClass("border-danger");
        form.find(".select2").next().find('.select2-selection').removeClass('border-danger');
        form.find('span.m-form__help').text("");
        return false;
    };

    var addMaskString = function(mask, string) {
        if (string.length === 0) {
            return '';
        }

        let maskArr = mask.split("");
        let s = string.replace(" ", "");

        for (let index = 0; index < s.length; index++) {
            if (mask.indexOf("#") >= 0) {
                maskArr[mask.indexOf("#")] = s[index];
                mask = maskArr.join("");
            }
        }

        return mask;
    };

    var sendRequisition = function (route, type, data, successFunction, errorFunction, obj, body) {
        let attacheData = {
            type: type,
            url: route,
            beforeSend: function () {
                BlockUI.blockPage();
            },
            data: data,
            success: function success(response) {
                BlockUI.unBlock();
                if (response.success) {
                    if (successFunction) {
                        successFunction(response);
                    } else {
                        handleRequestSuccess(response);
                    }
                } else {
                    if (errorFunction) {
                        errorFunction(response);
                    } else {
                        handleRequestErrors(response);
                    }
                }
            },
            error: function (response) {
                BlockUI.unBlock();
                handleRequestErrors(JSON.parse(response.responseText));
            }
        };
        if (!(data instanceof FormData)) {
            Object.assign(attacheData, { dataType: 'json'});
        } else {
            Object.assign(attacheData, {
                contentType: false,
                cache: false,
                processData: false,
            });
        }

        $.ajax(attacheData);
    };

    var downloadFiles = function (route, data, json = true) {
        $.ajax({
            type: 'POST',
            url: route,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        }
                    }
                };
                return xhr;
            },
            data: json ? JSON.stringify(data) : data,
            beforeSend: function () {
                BlockUI.blockPage();
            },
            success: function (response, status, xhr) {
                if (xhr.status == 200) {
                    var url = window.URL.createObjectURL(response);
                    var link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', json ? $.trim(data.fileCustomName ? data.fileCustomName : data.fileName) : data.get('fileCustomName'));
                    document.body.appendChild(link);
                    link.click();
                } else {
                    Swal.fire({
                        title: Lang.get('system.labels.success'),
                        html: response.message,
                        icon: 'success',
                    }).then((e) => {
                        if (response.orderId) {
                            window.location.href = window.location.origin + '/pedidos/' + response.orderId;
                        }
                    });
                }
            },
            error: function (response) {
                handleRequestErrors(JSON.parse(response.responseText));
            },
            complete: function () {
                BlockUI.unBlock();
            }
        });
    }

    var clearForm = function (form) {
        form.trigger('reset');
        form.find('.form-select').select2().trigger("refresh");
    }

    return {
        clearForm: function (form) {
            clearForm(form);
        },

        addMaskString: function(mask, string) {
            return addMaskString(mask, string);
        },

        initHandleRequestErrors: function(errors) {
            handleRequestErrors(errors);
        },

        initSuccessMessage: function initSuccessMessage(success, fnc = null){
            handleRequestSuccess(success, fnc);
        },

        prepareAjaxRequisitionHeader: function prepareAjaxRequisitionHeader() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('[name=_token]').val()
                },
                contentType: 'application/json'
            });
        },

        //FORM VALIDATION
        applyFormValidation: function(validate, form, rules, messages) {
            return applyFormValidation(validate, form, rules, messages);
        },

        clearAllFieldsOnAForm: function(form) {
            clearAllFieldsOnAForm(form);
        },

        insertErrorMessagesInFormFields: function(form, errors) {
            insertErrorMessagesInFormFields(form, errors);
        },

        removeErrorMessagesInFormFields: function(form) {
            removeErrorMessagesInFormFields(form);
        },

        mergeRulesOrMessagesOfValidation: function(objFirst, objSecond) {
            mergeRulesOrMessagesOfValidation(objFirst, objSecond);
        },

        initDatePicker: function(selector) {
            selector.mask("99/99/9999");
            let language = 'en'
            if (Lang.getLocale() === 'pt-br') {
                language = 'pt-BR';
                $.fn.datepicker.dates['pt-BR'] = {
                    days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                    daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                    daysMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                    months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                    monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    today: "Hoje",
                    monthsTitle: "Meses",
                    clear: "Limpar",
                    format: "dd/mm/yyyy"
                };
            }

            selector.datepicker({
                todayHighlight: true,
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                },
                language: language,
            }).on('changeDate', function(ev) {
                let valid = false;
                if (Lang.getLocale() == 'pt-br') {
                    valid = moment($(this).val(), 'DD/MM/YYYY', true).isValid();
                } else {
                    valid = moment($(this).val(), 'MM/DD/YYYY', true).isValid();
                }

                if (!valid) {
                    selector.datepicker('setDate', null);
                    swal.fire(Lang.get('system.modal_messages.date_error_title'), Lang.get('system.modal_messages.date_error_message'), "error");
                }
            });
        },

        getDatePickerConfiguration: function getDatePickerConfiguration() {
            var datePicker = {
                format: 'dd/mm/yyyy',
                days: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                daysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                daysMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
            };

            return datePicker;
        },

        initModule: function initModule(moduleName) {
            if (moduleName != "") {
                // if($.isArray(moduleName)) {
                //     moduleName.forEach(function(itemModuleName){
                //         eval(itemModuleName).init();
                //     });
                // }
                // eval(null).init();
            }
        },

        getCookie: function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },

        sendRequisition: function (route, type, data, successFunction, errorFunction, obj = {}, body = '') {
            sendRequisition(route, type, data, successFunction, errorFunction, obj, body);
        },

        downloadFiles: function (route, data, options = {}, body = '') {
            downloadFiles(route, data, options, body);
        },

        setMaskMoney,
        setMaskNegativeMoney,
        realMoneyToDatabase
    };
}();

// $.fn.serializeObject = function () {
//     var o = {};
//     var a = this.serializeArray();
//     $.each(a, function () {
//         if (o[this.name]) {
//             if (!o[this.name].push) {
//                 o[this.name] = [o[this.name]];
//             }
//             o[this.name].push(this.value || '');
//         } else {
//             o[this.name] = this.value || '';
//         }
//     });
//     return o;
// };

function formatDateInPtBr(stringDate) {
    var time = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    var separatorDate = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "/";
    var separatorTime = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : ":";

    var date = new Date(stringDate.toString().length == 10 ? stringDate + " " : stringDate);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();

    var dateFinal = (day.toString().length < 2 ? "0" + day : day) + separatorDate + (month.toString().length < 2 ? "0" + month : month) + separatorDate + year;

    if (time) {
        var hour = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var timeFinal = (hour.toString().length < 2 ? "0" + hour : hour) + separatorTime + (minutes.toString().length < 2 ? "0" + minutes : minutes) + separatorTime + (seconds.toString().length < 2 ? "0" + seconds : seconds);

        return dateFinal + " " + timeFinal;
    }
    return dateFinal;
}

function formatCPForCNPJ(document) {
    var documentStr = document.replace(/[^\d]/g, "");

    if (documentStr.toString().length > 11) {
        return documentStr.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
    }
    return documentStr.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
}

async function getPerson (cpf_cnpj) {
    const response = await $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : route('profile.get.person'),
        type : 'POST',
        dataType: 'JSON',
        cache: false,
        data : {
            cpf_cnpj: cpf_cnpj
        },
        beforeSend: function () {blockUI.block(); },
        success : function(response){
            blockUI.release()
            if (!response.success) {
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

    return(response);
}
