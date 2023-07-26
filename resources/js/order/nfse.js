function cancelNFse(url) {
    Swal.fire({
        title: '<strong>Exclusão</strong>',
        icon: "error",
        html: 'Deseja realmente cancelar esta NFse? Esta ação é irreversível. <br><br> Informe o motivo do cancelamento:',
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: '<i class="la la-check"></i> Confirmar',
        cancelButtonText: '<i class="la la-thumbs-down"></i> Cancelar',
        input: 'text',
        inputPlaceholder: 'Motivo',
        inputAttributes: {
            name: 'motivo',
        },
        inputValidator: (value) => {
            if (!value)
                return 'Por favor digite um motivo';
        }
    }).then((result) => {
        if (result.value !== undefined) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : url,
                type : 'POST',
                dataType: 'JSON',
                cache: false,
                data : {
                    other: $("input[name='motivo']").val()
                },
                dataType: 'json',
                beforeSend: function () { 	blockUI.block(); },
                success: function (response) {
                    blockUI.release();
                    if (response.success) {
                        Swal.fire({
                            title: 'Exclusão',
                            text: response.message,
                            icon: 'success',
                            timer: 1000
                        }).then((result) => {
                            window.location.reload(true);
                        });
                    } else {
                        Swal.fire({
                            title: 'Falha',
                            text: response.message,
                            icon: 'error',
                        });
                    }
                },
                error: function (msg) {
                    blockUI.release();
                    Swal.fire({
                        title: 'Falha',
                        text: msg.responseText,
                        icon: 'error',
                    });
                },
                complete: function () {
                    blockUI.release();
                }
            });
        }
    });
}

function donwloadFile(url) {
    var f = document.createElement("form");
    f.setAttribute('target', "_blank");
    f.setAttribute('method', "get");
    f.setAttribute('style', "height: 0; min-height: 0;");
    f.setAttribute('action', url);
    document.body.appendChild(f);
    f.submit();
}

function viewNFe(url,numeroNFse, cnpjPrestador) {
    $("#modalNota .htmlNota").html("");
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'html',
        cache: false,
        beforeSend: function () { 	blockUI.block(); },
        success: function (response) {
            blockUI.release();
            $("#modalNota .htmlNota").html("");
            $("#modalNota .htmlNota").append(response);
            $(".baixarNota").attr("onclick", "baixarNFse('" + numeroNFse + "', '" + cnpjPrestador + "');");
            $("#modalNota").modal("show").on('hidden.bs.modal', function (e) {
                $(this).data('bs.modal', null);
            }).on('shown.bs.modal', function (e) {});
            jQuery("body").prop('style', 'overflow-y:hidden!important');
        },
        error: function (msg) {
            blockUI.release();
            Swal.fire({
                title: 'Falha',
                text: msg.responseText,
                icon: 'error',
            });
        },
        complete: function () {
            blockUI.release();
        }
    });
}

function modalSendEmail(url) {
    $("#myModal").html("");
    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        cache: false,
        beforeSend: function () { 	blockUI.block(); },
        success: function (response) {
            blockUI.release();
            $("#myModal").html("");
            $("#myModal").append(response.view);
            $("#" + response.modal).modal("show").on('hidden.bs.modal', function (e) {
                $(this).data('bs.modal', null);
            }).on('shown.bs.modal', function (e) {
            });
            jQuery("body").prop('style', 'overflow-y:hidden!important');

        },
        error: function (msg) {
            blockUI.release();
            Swal.fire({
                title: 'Falha',
                text: msg.responseText,
                icon: 'error',
            });
        },
        complete: function () {
            blockUI.release();
        }
    });
}

function sendEmail(url) {

    if ($("#email_email_taker").val() == "") {
        Swal.fire({
            title: 'Falha',
            text: "Preencha ao menos um E-mail",
            icon: 'error',
        });
        return false;
    }

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: 'POST',
        data: {
            other: $("#email_email_taker").val(),
        },
        dataType: 'json',
        beforeSend: function () { 	blockUI.block(); },
        success: function (response) {
            blockUI.release();
            if (response.success) {
                Swal.fire({
                    title: 'Enviado!',
                    text: response.message,
                    icon: 'success',
                }).then((result) => {
                    window.location.reload(true);
                });
            } else {
                Swal.fire({
                    title: 'Falha',
                    text: response.message,
                    icon: 'error',
                });
            }
        },
        error: function (msg) {
            blockUI.release();
            Swal.fire({
                title: 'Falha',
                text: msg.responseText,
                icon: 'error',
            });
        },
        complete: function () {
            blockUI.release();
        }
    });
}
