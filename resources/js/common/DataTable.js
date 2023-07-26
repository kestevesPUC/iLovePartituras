var Datatable = function () {
    var datatable;
    var urlLocation;
    var formSearch;
    var selectRow = null;

    var generateTable = function (table, url, columns, columnDefs = [], functionDrawCallback = null, functionFinishedCallback = null, order = null, stateSave, fSearch, lengthChange, lengthMenu, pageLengthDefault) {
        formSearch = fSearch;
        urlLocation = url;
        datatable = table.DataTable({
            searchDelay: 500,
            processing: true,
            //sDom : 'rt<"bottom"fl><"clear">',
            //"dom": 'Bfrtip',
            "pagingType": "input",
            serverSide: true,
            order: order,
            stateSave: stateSave,
            select: selectRow,
            ajax: {
                url: urlLocation,
                type: 'GET',
                headers: {'Only-Data': true},
                statusCode: {
                    401: function () {
                        return window.location = '/login';
                    },
                    403: function () {
                        return swal.fire('', Lang.get('system.modal_messages.unauthorized_action'), "error");
                    },
                    500: function (response) {
                        return swal.fire('', response.responseJSON.message, "error");
                    },
                }
            },
            columns: columns,
            columnDefs: columnDefs,
            drawCallback: function (settings) {
                if (functionDrawCallback) {
                    functionDrawCallback(settings);
                }
                if (fSearch)
                    fSearch.parent().parent().find('button').prop('disabled', false);
            },
            initComplete: function (settings, json) {
                if (functionFinishedCallback) {
                    functionFinishedCallback(json);
                }
            },
            preDrawCallback: function () {
                if (fSearch)
                    fSearch.parent().parent().find('button').prop('disabled', true);
            },
            language: {
                url: '/assets/plugins/datatables/lang/' + Lang.getLocale() + '.json',
                infoFiltered: "",
                processing: "<i class='m-loader m-loader--primary'></i>",

            },
            lengthMenu: lengthMenu,
            pageLength: pageLengthDefault,
            responsive: true,

            destroy: true,

            retrieve: false,
            paging: true,

            draw: true,
            searching: false,
            lengthChange: lengthChange,
        });
    };

    var enterKeyPress = function () {
        formSearch.keypress((e) => {
            if (e.keyCode === 13) {
                applyFilters();
            }
        })
    }

    var searchButtonClick = function () {
        $('.applySearch').click(function () {
            applyFilters();
            // mApp.blockPage();
        });
    }

    var applyFilters = function () {
        datatable.ajax.url(urlLocation + '?' + formSearch.serialize());
        datatable.ajax.reload();
    }

    var cleanSearchForm = function () {
        $(".cleanSearchForm").on('click', function () {
            formSearch.trigger("reset");
            formSearch.find('.form-select').select2().trigger("refresh");
            datatable.ajax.url(urlLocation);
            datatable.ajax.reload();
        });
    }

    return {
        init: function (table, url, columns, columnDefs = [], functionCallback, functionFinishedCallback = null, order = null, stateSave, fSearch = null, lengthChange = true, lengthMenu = [10, 25, 50, 100], pageLengthDefault = 10) {
            generateTable(table, url, columns, columnDefs, functionCallback, functionFinishedCallback, order, stateSave, fSearch, lengthChange, lengthMenu, pageLengthDefault);
            if (fSearch) {
                searchButtonClick();
                cleanSearchForm();
                enterKeyPress();
            }
        },
        setSelectRow: function (select) {
            selectRow = select;
        }
    };
}();
