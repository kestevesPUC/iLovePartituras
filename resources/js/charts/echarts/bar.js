let barChart;

/**
 * Gráfico com "Total de empresas por estado"
 * @type {{init: BAR.init, setData: setData, setDataUfLegalNature: setDataUfLegalNature, updateDataEconomyActivity: updateDataEconomyActivity}}
 */
const BAR = function () {
    /**
     * Constrói o gráfico e insere na tela
     * @param data
     */
    const build = function (data) {
        data = treatDataGlobal(data);

        // Initialize the echarts instance based on the prepared dom
        barChart = echarts.init(document.getElementById('bar'));
        barChart.hideLoading();
        // Specify the configuration items and data for the chart
        let option = {
            title: {
                text: "Empresas por estado",
                left: 'center'
            },
            xAxis: {
                type: "category",
                axisTick: {
                    alignWithLabel: true
                },
                data: data.ufs,
            },
            color: "#7bb1e8",
            yAxis: {
                type: "value"
            },
            tooltip: {
                trigger: "item"
            },
            grid: { containLabel: true },
            legend: {
                data: data.count
            },
            series: [
                {
                    select: {
                        itemStyle: {
                            borderColor: '#FFF',
                            color: '#0354a8'
                        },
                    },
                    // realtimeSort: true,
                    // selectedMode: 'single',
                    selectedMode: 'multiple',
                    seriesLayoutBy: 'column',
                    emphasis: {
                        itemStyle: {
                            color: "#242c64"
                        }
                    },
                    data: data.count,
                    type: 'bar'
                },
                {
                    label: {
                        show: true,
                        precision: 1,
                        valueAnimation: true,
                        fontFamily: 'monospace'
                    }
                },
                // {
                //     data: count,
                //     type: 'line',
                //     color: '#A4BC3C',
                //     smooth: true,
                //     symbolSize: 2
                // }
            ],
        };

        // Display the chart using the configuration items and data just specified.
        barChart.setOption(option);

        barChart.on("click", function (params) {
            uf_click = params.name;
            selected_bar    = params.event.target.selected;
            updateChart_map = true;
            updateChart_pie = true;
            updateChart_barrace  = true;
            updateChart_barrace2 = true;
            updateChart_bar = false;

            if($.inArray(uf_click, uf_clicks) == -1) {
                uf_clicks.push(uf_click);
            } else {
                uf_clicks = uf_clicks.filter(v => v !== uf_click)
            }

            treatDataAllCharts();
        });
    }


    const updatedGlobalChart = function (name, data) {
        data = treatDataGlobal(data)
        updateChart(data.ufs, data.count, name);
    }

    /**
     * Trata os dados para o padrão aceito pelo
     * gráfico de barras
     * @param data Json
     * @returns {{ufs: *[], count: *[]}}
     */
    function treatDataGlobal(data) {
        let ufs = []
        let count = [];

        for (const uf in data) {
            ufs.push(uf);
            let cont = 0;
            for(const porte in data[uf]) {
                for(const desc in data[uf][porte]) {
                    cont += data[uf][porte][desc];
                }
            }
            count.push(cont);
        }

        return {ufs,count}
    }

    const updateChart = function (uf, value, name) {
        barChart.setOption({
            xAxis: {
                data: uf,
            },
            series: [
                {
                    // Find series by name
                    name: name,
                    data: value
                }
            ]
        });
    }

    return {
        init: function (data) {
            build(data);
        },
        updatedGlobalChart
    }
}();
