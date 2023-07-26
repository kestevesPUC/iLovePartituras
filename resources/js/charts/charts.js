const CHARTS = function () {
    const valuesClicked = function () {

        $("#exportarXlsx_chart").on('click', function() {
            state();
            postage();
            cnaes();
            legalNature();
        });
    }

    const state = function () {
        try {
            if (data_download.uf) {
                getCities();
            }
        } catch (e) {}

        $('select[name="state"]').val(data_download.uf).trigger('change');
    }
    const getCities = () => {
        const successFunction = (response) => {
            response.cities.unshift("Todos");
            $('select[name="city"]').select2({ data: response.cities });
        }
        $('select[name="state"]').on('change', function() {
            $('select[name="city"]').empty();
            $.ajax({
                url: route('address.findCity'),
                type: 'GET',
                data: {
                    uf: $(this).val(),
                },
                success: function (response) {
                    successFunction(response)
                }
            });
        });
    }

    const postage = function () {
        console.log(data_download)
        let porte = [];
        for (let portes in data_download.porte) {
            switch (data_download.porte[portes].toLocaleLowerCase()) {
                case '':
                    break;
                case 'mei':
                    porte.push(1);
                    break;
                case 'me':
                    porte.push(2);
                    break
                case 'epp':
                    porte.push(3);
                    break
                case 'demais':
                    porte.push(4);
                    break
                case 'não informado':
                    porte.push(5);
                    break
            }
        }
        $('select[name="postage"]').val(porte).trigger('change');
    }

    const cnaes = function () {
        let cnae = [];
        for (let cod in data_download.atividade_economica) {
            $("select[name='cnae']").each(function () {
                $(this).children("option").each(function () {
                    if ($(this).html() === data_download.atividade_economica[cod]) {
                        cnae.push($(this).val());
                    }
                });
            });
        }
        $('select[name="cnae"]').val(cnae).trigger('change');
    }

    const legalNature = function () {
        let naturezasJuridica = [];
        for (let natJud in data_download.natureza_juridica) {
            $("select[name='legalNature']").each(function () {
                $(this).children("option").each(function () {
                    if ($(this).html() === data_download.natureza_juridica[natJud]) {
                        naturezasJuridica.push($(this).val())
                    }
                });
            });
        }
        console.log(naturezasJuridica)
        $('select[name="legalNature"]').val(naturezasJuridica).trigger('change');
    }

    return {
        init: function () {
            valuesClicked();
        }
    };
}();
