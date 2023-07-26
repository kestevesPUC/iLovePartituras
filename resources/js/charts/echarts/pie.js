let pieChart;

/**
 * Gráfico de "Total de empresas por Porte"
 * @type {{init: PIE.init, setData: setData, updateDataEconomyActivity: updateDataEconomyActivity}}
 */
const PIE = function () {
    /**
     * Constrói o gráfico e insere na tela
     * @param data
     */
    const build = function (data) {
        data = treatGlobalData(data)

        pieChart = echarts.init(document.getElementById('pie'));

        // Specify the configuration items and data for the chart
        let option = {
            title: {
                text: 'Porte',
                subtext: 'Percentual de empresas por porte',
                left: 'center'
            },
            grid: { containLabel: true },
            tooltip: {
                trigger: 'item'
            },
            label: {
                show: true,
                formatter(param) {
                    // correct the percentage
                    return param.name + ' (' + param.percent * 2 + '%)';
                }
            },
            legend: {
                orient: 'vertical',
                left: 'right'
            },
            series: [
                {
                    // selectedMode: 'single',
                    selectedMode: 'multiple',
                    name: 'Empresas',
                    type: 'pie',
                    radius: '50%',
                    data: data,
                    emphasis: {
                        itemStyle: {
                            show:true,
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };

        // Display the chart using the configuration items and data just specified.
        pieChart.setOption(option);

        pieChart.on("click", function (params) {
            selected_pie = params.event.target.selected;
            updateChart_map = true;
            updateChart_bar = true;
            updateChart_barrace  = true;
            updateChart_barrace2 = true;
            updateChart_pie = false;
            porte_click = params.name;

            if($.inArray(porte_click, porte_clicks) == -1) {
                porte_clicks.push(porte_click);
            } else {
                porte_clicks = porte_clicks.filter(v => v !== porte_click)
            }

            treatDataAllCharts();
        });



    }

    const updatedGlobalChart = function (name , data) {
        data = treatGlobalData(data);
        updateChart(name, data);
    }
    /**
     * Trata os dados para o padrão aceito pelo
     * gráfico de pizza/torta
     * @param data Json
     * @returns {{ufs: *[], count: *[]}}
     */
    const treatGlobalData = function (data) {
        const valores = [];
        if (updateChart_pie) {
            for (const uf in data) {
                for (const porte in data[uf]) {
                    let total = 0;
                    for (const atividade in data[uf][porte]) {
                        total += data[uf][porte][atividade];
                    }
                    if (valores[porte]) {
                        valores[porte] += total;
                    } else {
                        valores[porte] = total;
                    }
                }
            }
        } else {
            let total = 0;
            for (const uf in data) {
                for (const porte in data[uf]) {
                    for (const atividade in data[uf][porte]) {
                        total += data[uf][porte][atividade];
                    }
                    valores[porte] = total;
                }
            }
        }

        return [
            {"name": "MEI", "value": valores.MEI},
            {"name": "ME", "value": valores.ME},
            {"name": "Demais", "value": valores.Demais},
            {"name": "Não Informado", "value": valores["Não Informado"]},
            {"name": "EPP", "value": valores.EPP}
        ];
            // updateChart(name, data);
    }


    const updateChart = function (value, data) {
        pieChart.setOption({
            series: {
                selectedMode: 'multiple',
                name: "Empresas " + value,
                data: data
            }
        });
    }

    return {
        init: function (data) {
            build(data);
        },
        updatedGlobalChart
    };
}();

