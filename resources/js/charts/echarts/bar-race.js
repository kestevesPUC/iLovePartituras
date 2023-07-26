let bar_raceChart;
/**
 * Gráfico de "Total de empresas por Atividade Econômica"
 * @type {{init: BAR_RACE.init, setData_porte: setData_porte, setData: setData_UF}}
 */
const BAR_RACE = function () {
    let option;
    /**
     * Constrói o gráfico e insere na tela
     * @param data
     */
    const build = function (data) {
        bar_raceChart = echarts.init(document.getElementById('bar-race'));

        option = {
            title: {
                text: 'Empresas por Atividade Econômica',
                left: 'center'
            },
            dataset: {
                source: data,
            },
            grid: {containLabel: true,},
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
                    maintainAspectRatio: false,
                    y: {
                        beginAtZero: true
                    },
                }
            },
            dataZoom: [{
                // type: 'inside',
                type: 'slider',
                id: 'insideY',
                yAxisIndex: 0,
                start: 0,
                end: 0.3,
                width: 10,
                right: 10,
                handleSize: 20,
                zoomOnMouseWheel: false,
                moveOnMouseMove: true,
                moveOnMouseWheel: true
            }],
            series: [
                {
                    progressive: 3000,
                    select: {
                        itemStyle: {
                            borderColor: '#FFF',
                            color: '#242c64'
                        },
                    },
                    // realtimeSort: true,
                    selectedMode: 'multiple',
                    // selectedMode: 'single',
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

        bar_raceChart.setOption(option);
        //Ação de click no gráfico
        bar_raceChart.on("click", function (params) {
            economyActivity_click = params.value[2];
            selected_barrace = params.event.target.selected;
            economyActivityOrLegalNature = 0;
            updateChart_bar = true;
            updateChart_map = true;
            updateChart_pie = true;
            updateChart_barrace2 = true;
            updateChart_barrace  = false;


            if($.inArray(economyActivity_click, economyActivity_clicks) == -1) {
                economyActivity_clicks.push(economyActivity_click);
            } else {
                economyActivity_clicks = economyActivity_clicks.filter(v => v !== economyActivity_click)
            }

            treatDataAllCharts();

        });
    }


    const updatedGlobalChart = function (filtros, data) {
        data = treatGlobalDataBarRace(data);
        setDataGlobal(filtros, data);
    }

    const setDataGlobal = function (name, data) {
        bar_raceChart.setOption({
            title: {
                subtext: name
            },
            dataset: {
                source: data,
            },
            label: {
                show: true,
                precision: 1,
                position: 'right',
                valueAnimation: true,
                fontFamily: 'monospace'
            },
            series: [
                {
                    // realtimeSort: true,
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
        });
    }


    return {
        init: function (data) {
            build(data);
        },
        updatedGlobalChart
    };
}();





