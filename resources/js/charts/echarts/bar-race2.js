let bar_race2Chart;

/**
 * Grafico "Total de empresas por Natureza Jurídica"
 * @type {{init: BAR_RACE2.init}}
 */
const BAR_RACE2 = function() {
    let dataBar;

    /**
     * Constrói o gráfico e insere na tela
     * @param data
     */
    const build = function (data) {
        dataBar = data;
        bar_race2Chart = echarts.init(document.getElementById('bar-race2'));

        option = {
            title: {
                text: 'Empresas por Natureza Jurídica',
                left: 'center'
            },
            dataset: {
                source: data
            },
            grid: { containLabel: true,},
            xAxis: {
                name: 'amount',
                max: 'dataMax',
                formatter: function (n) {
                    return Math.round(n) + '';
                },
            },
            tooltip: {
                trigger: 'item'

            },
            yAxis: {
                axisLabel: {
                    margin: 20,
                    width: 120,
                    overflow: "truncate"
                },
                overflowY: 'scroll',
                type: 'category',
                inverse: true,
            },
            visualMap: {
                orient: 'horizontal',
                left: 'center',
                min: 5,
                max: 100,
                text: ['High Score', 'Low Score'],
                // Map the score column to color
                dimension: 0,
                inRange: {
                    color: ['#242c64', '#2699fc', '#7bb1e8']
                }
            },
            options: {
                scales: {
                    maintainAspectRatio:false,
                    y: {
                        beginAtZero: true
                    },
                }
            },
            dataZoom:[ {
                // type: 'inside',
                type: 'slider',
                id: 'insideY',
                yAxisIndex: 0,
                start: 0,
                end: 5,
                width: 10,
                right: 10,
                handleSize: 20,
                zoomOnMouseWheel: false,
                moveOnMouseMove: true,
                moveOnMouseWheel: true
            }],
            series: [
                {
                    select: {
                        itemStyle: {
                            borderColor: '#FFF',
                            color: '#242c64'
                        },
                    },
                    // realtimeSort: true,
                    // selectedMode: 'single',
                    // realtimeSort: true,
                    selectedMode: 'multiple',
                    seriesLayoutBy: 'column',
                    type: 'bar',
                    encode: {
                        // Map the "amount" column to X axis.
                        x: 'amount',
                        // Map the "product" column to Y axis
                        y: 'product'
                    },
                    label: {
                        show: true,
                        precision: 1,
                        position: 'right',
                        valueAnimation: true,
                        fontFamily: 'monospace'
                    }
                }
            ],
        };

        bar_race2Chart.setOption(option);

        //Ação de click no gráfico
        bar_race2Chart.on("click", function (params) {
            selected_barrace2 = params.event.target.selected
            economyActivityOrLegalNature = 1;
            updateChart_barrace2 = false;
            updateChart_map = true;
            updateChart_pie = true;
            updateChart_barrace = true;
            updateChart_bar = true;
            legalNature_click = params.name;

            if($.inArray(legalNature_click, legalNature_clicks) == -1) {
                legalNature_clicks.push(legalNature_click);
            } else {
                legalNature_clicks = legalNature_clicks.filter(v => v !== legalNature_click)
            }

            treatDataAllCharts();
        });
    }

    const updatedGlobalChart = function (filtros, data) {
        data = treatGlobalDataBarRace(data);
        dataBar = data;
        updateData(data, filtros);
    }

    /**
     * Atualiza o gráfico
     * @param data Array -> [[descricao, value],...]
     * @param name String
     */
    function updateData(data, name) {
        bar_race2Chart.setOption({
            title: {
                subtext: 'Empresas ' + name,
                left: 'center'
            },
            dataset: {
                source: data,
            },
        });
    }

    return {
        init: function (data) {
            build(data);
        },
        updatedGlobalChart
    };
}();
