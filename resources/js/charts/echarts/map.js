let mapChart;
const MAP = function () {
    /**
     * Constrói o gráfico e insere na tela
     * @param data
     */
    const build = function (data) {
        // Prepare demo data
        // Data is joined to map using value of 'hc-key' property by default.
        // See API docs for 'joinBy' for more info on linking data and map.
        // https://code.highcharts.com/

        data = treatGlobalData(data)


        // Create the chart
        mapChart = Highcharts.mapChart('map', {
            chart: {
                renderTo: 'container',
                map: 'countries/br/br-all',
                backgroundColor: '#98bff5',
                animation: true,
            },
            label: {
                show: false
            },
            title: {
                text: 'Empresas por estado'
            },
            enableMouseTracking: true,
            subtitle: {
                text: 'Brasil'
            },

            mapNavigation: {
                enabled: true,
                alignTo: 'spacingBox',
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },
            colorAxis: {
                min: 0
            },
            border:{
            },
            series: [{
                data: data,
                name: 'Brasil',
                events: {
                    click: function (e) {
                        eventClick(e.point.properties['postal-code']);
                    }
                },
                states: {
                    hover: {
                        color: '#242c64'
                    },
                },
            },],

        });

    }

    /**
     * Quando há um click no mapa este método atribiu valores à
     * variáveis de controle e chama o tratamento dos dados.
     * @param e
     */
    function eventClick(e) {
        uf_click = e;
        selected_bar = true;
        updateChart_bar = true;
        updateChart_pie = true;
        updateChart_barrace = true;
        updateChart_barrace2 = true;
        updateChart_map = false;

        if($.inArray(uf_click, uf_clicks) == -1) {
            uf_clicks.push(uf_click);
        } else {
            uf_clicks = uf_clicks.filter(v => v !== uf_click)
        }
        treatDataAllCharts();
    }

    function update(data, name) {
        mapChart.update({
            chart: {
                map: 'countries/br/br-all'
            },
            label: {
                show: false
            },
            title: {
                text: 'Empresas no Brasil',
                textColor: '#FFF'
            },
            enableMouseTracking: true,
            subtitle: {
                text: name
            },

            mapNavigation: {
                enabled: true,
                alignTo: 'spacingBox',
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },
            colorAxis: {
                min: 0
            },
            series: [{
                data: data,
                name: name,
                events: {
                    click: function (e) {
                        eventClick(e.point.properties['postal-code']);
                    }
                },
                states: {
                    hover: {
                        color: '#242c64'
                    },
                },
            },],
        })
    }


    const updatedGlobalChart = function (name, data) {
        data = treatGlobalData(data)
        update(data, name)
    }

    /**
     * Trata os dados para o padrão aceito pelo
     * gráfico de Mapa
     * @param data Json
     * @returns {{ufs: *[], count: *[]}}
     */
    function treatGlobalData(data) {
        const resultado = [];

        for (const uf in data) {
            let total = 0;
            for (const porte in data[uf]) {
                for (const desc in data[uf][porte]) {
                    total += data[uf][porte][desc];
                }
                resultado.push(["br-" + uf.toLocaleLowerCase(),total]);
            }
        }

        return resultado;
    }

    return {
        init: function (data) {
            build(data);
        },
        updatedGlobalChart
    }
}();
