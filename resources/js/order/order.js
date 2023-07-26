const Order = function () {
    Inputmask({
        "mask" : "99/99/9999"
    }).mask("#manual_date");

    $('#manual_date').daterangepicker({
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

    const getCities = () => {
        const successFunction = (response) => {
            response.cities.unshift("Todos");
            $('select[name="city"]').select2({ data: response.cities });
        }
        $('select[name="state"]').on('change', function() {
            $('select[name="city"]').empty();
            $.ajax({
                url : route('address.findCity'),
                type : 'GET',
                data : {
                    uf : $(this).val(),
                },
                success : function(response){
                    successFunction(response)
                }
            });

        });
    }

    const generationTable = () => {
        let columns = [
            {name: 'id', data: 'id'},
            {name: 'Representante', data: 'name_representative'},
            {name: 'cpf_cnpj', data: 'cpf_cnpj'},
            {name: 'linhas', data: 'lines'},
            {name: 'data_de_criacao', data: 'created_at'},
            {name: 'data_de_edicao', data: 'updated_at'},
            {name: 'valor_cobrado', data: 'value_charge'},
            {name: 'status', data: 'status'},
            {name: 'acao', data: null}
        ];
        let columnDefs = [{
            targets: 0,
            title: 'ID',
            class: 'text-center',
            orderable: true
        },
        {
            targets: 1,
            class: 'text-center',
            title: 'Representante',
        },
        {
            targets: 2,
            class: 'text-center',
            title: 'CPF / CNPJ',
        },
        {
            targets: 3,
            class: 'text-center',
            title: 'Linhas',
        },
        {
            targets: 4,
            class: 'text-center',
            title: 'Data de Criação',
            render : function (data, type, full, meta) {
                return formatDateInPtBr(full.created_at);
            }
        },
        {
            targets: 5,
            class: 'text-center',
            title: 'Data de Edição',
            render : function (data, type, full, meta) {
                return formatDateInPtBr(full.updated_at);
            }
        },
        {
            targets: 6,
            class: 'text-center',
            title: 'Valor Cobrado',
            render : function (data, type, full, meta) {
                return 'R$ ' + full.value_charge;
            }
        },
        {
            targets: 7,
            class: 'text-center',
            title: 'Status',
            render : function (data, type, full, meta) {
                switch (full.status) {
                    case 1:
                        return '<span class="ms-2 badge badge-light-info fw-bold">Pendente</span>'
                    case 2:
                        return '<span class="ms-2 badge badge-light-success fw-bold">Pago</span>'
                    case 3:
                        return '<span class="ms-2 badge badge-light-warning fw-bold">Pago Parcialmente</span>'
                    case 4:
                        return '<span class="ms-2 badge badge-light-danger fw-bold">Estornado</span>'
                    case 5:
                        return '<span class="ms-2 badge badge-light-danger fw-bold">Estornado Parcialmente</span>'
                    case 6:
                        return '<span class="ms-2 badge badge-light-danger fw-bold">Cancelado</span>'
                    default:
                        return '<span class="ms-2 badge badge-light-info fw-bold">Pendente</span>'
                }
            }
        },
        {
            targets: 8,
            class: 'text-center',
            title: 'Ação',
            render: function (data, type, full, meta) {
                return '<a href="'+route('order.detail', {id: full.id})+'">'+
                '<i class="bi bi-pencil-square fs-1x"></i>'+
                '</a>'
            }
        }];

        let functionDrawCallback = function () {}

        let functionFinishedCallback = function () {}

        const lengthChange = null;
        const lengthMenu = null;
        const pageLengthDefault = 10;

        Datatable.init($('.table'), route('order.load.grid'), columns, columnDefs, functionDrawCallback, functionFinishedCallback, [[0, 'desc']], false, $("#searchMarketingResearch"), lengthChange, lengthMenu, pageLengthDefault)
    }


    return {
        init: function () {
            generationTable();
            getCities();
        }
    }
}();
