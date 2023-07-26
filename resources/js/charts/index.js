let dataLegalNature;
let data_UF_Porte_LegalNature;
let data_UF_Porte_EconomyActivity;
let dataEconomyActivity;

// Filtros
let uf_click              = null;
let porte_click           = null;
let economyActivity_click = null;
let legalNature_click     = null;

//Arrays clicks
let uf_clicks              = [];
let porte_clicks           = [];
let economyActivity_clicks = [];
let legalNature_clicks     = [];

// Variáveis de controle
let updateChart_bar      = true;
let updateChart_map      = true;
let updateChart_pie      = true;
let updateChart_barrace  = true;
let updateChart_barrace2 = true;

// Variaveis para armazenar os dados dos gráficos
let data_UF = [];
let data_Map;
let data_Porte;
let data_BarRace;
let data_BarRace2;

// If 0 == EconomyActivity And = 1 LegalNature ;
let economyActivityOrLegalNature = 0;

// Variáveis para controle de click
let selected_bar = false;
let selected_pie = false;
let selected_barrace = false;
let selected_barrace2 = false;

// Filtro selecionado para download
let data_download;

// Datos para o datatable
let data_regiao = [];
let data_total;


const DATA = function () {

    /**
     * Requisição para pegar os dados das empresas
     * Atividade Econômica no banco de dados
     */
    const queryEconomyActivity = function () {
        $.ajax({
            url: route('charts.economy_activity'),
            type: "GET",
            success: function (response) {
                data_UF_Porte_EconomyActivity = data(response);
            }
        });
    }
    /**
     * Requisição para pegar os dados das empresas
     * Natureza Jurídica no banco de dados
     */
    const queryLegalNature = function () {
        $.ajax({
            url: route('charts.legal_nature'),
            type: "GET",
            beforeSend: function () { blockUI.block(); },
            success: function (response) {
                data_UF_Porte_LegalNature = data(response);
                build();
                dataRegiao(data_UF_Porte_LegalNature);
                blockUI.release();
            }
        });
    }

    /**
     * Metodo que chama os métodos que criam cada gráfico
     * quando a tela é carregada
     */
    const build = function () {
        BAR.init(data_UF_Porte_EconomyActivity);
        MAP.init(data_UF_Porte_EconomyActivity);
        PIE.init(data_UF_Porte_EconomyActivity);
        BAR_RACE.init(treatGlobalDataBarRace(data_UF_Porte_EconomyActivity));
        BAR_RACE2.init(treatGlobalDataBarRace(data_UF_Porte_LegalNature));
    }

    return {
        init: function () {
            queryEconomyActivity();
            queryLegalNature();
        },

    }
}();


/**
 * Trata os dados para o padrão:
 * UF {
 *     Porte 1: {descricao1: 1651, descricao2: 15511, ...},
 *     Porte 2: {descricao1: 1651, descricao2: 15511, ...},
 *     Porte 3: {descricao1: 1651, descricao2: 15511, ...},
 *     ...
 * },
 * UF2 {...},
 *
 * @param data
 * @returns {{}}
 */
function data(data) {
    let mergedData = {};

    for (let key in data) {
        let item = data[key];
        let uf = item.uf;
        let porte = item.porte;
        let descricao = item.descricao;
        let count = item.count;

        if (!mergedData[uf]) {
            mergedData[uf] = {};
        }

        if (!mergedData[uf][porte]) {
            mergedData[uf][porte] = {};
        }

        if (!mergedData[uf][porte][descricao]) {
            mergedData[uf][porte][descricao] = 0;
        }

        mergedData[uf][porte][descricao] += count;
    }

    return mergedData;
}

/**
 * Trata os dados de cada gráfico de maneira geral
 */
function treatDataAllCharts() {
    let filtros = "";
    data_download = [];

    dataEconomyActivity = null;
    dataLegalNature     = null;
    data_UF       = null;
    data_Map      = null;
    data_Porte    = null;
    data_BarRace  = null;
    data_BarRace2 = null;

    dataEconomyActivity = data_UF_Porte_EconomyActivity;
    dataLegalNature     = data_UF_Porte_LegalNature;

    let data = economyActivityOrLegalNature == 0 ? dataEconomyActivity : dataLegalNature;
    data_UF    = data;
    data_Porte = data;
    data_Map   = data;

    // Filtra por UF
    if(uf_clicks != '' && selected_bar) {
        dataEconomyActivity = filterUF(uf_clicks, dataEconomyActivity);
        dataLegalNature     = filterUF(uf_clicks, dataLegalNature);
        data_Porte          = filterUF(uf_clicks, data_Porte);
        data_Map            = filterUF(uf_clicks, data_Map);

    }

    // Filtra por PORTE
    if(porte_clicks != '' && selected_pie) {
        dataEconomyActivity = filterPorte(porte_clicks, dataEconomyActivity);
        data_UF             = filterPorte(porte_clicks, data_UF);
        data_Map            = filterPorte(porte_clicks, data_Map);
        dataLegalNature     = filterPorte(porte_clicks, dataLegalNature);
    }

    // Filtra por ATIVIDADE ECONOMICA
    if(economyActivity_clicks != '' && economyActivityOrLegalNature == 0 && selected_barrace) {
        dataEconomyActivity = filterEconomyActivity(economyActivity_clicks, dataEconomyActivity);
        data_UF             = filterEconomyActivity(economyActivity_clicks, data_UF);
        data_Map            = filterEconomyActivity(economyActivity_clicks, data_Map);
        data_Porte          = filterEconomyActivity(economyActivity_clicks, data_Porte);

    }
    // Filtra por NATUREZA JURÍDICA
    if(legalNature_clicks != '' && economyActivityOrLegalNature == 1 && selected_barrace2) {
        dataLegalNature = filterLegalNature(legalNature_clicks, dataLegalNature);
        data_UF         = filterLegalNature(legalNature_clicks, data_UF);
        data_Map        = filterLegalNature(legalNature_clicks, data_Map);
        data_Porte      = filterLegalNature(legalNature_clicks, data_Porte);
    }

    addDownload();
    filtros = selectedFilters();

    updateCharts(filtros);
    dataRegiao(data_UF);
}

/**
 * Chama a atualização de cada gráfico com os dados
 * já tratados de maneira geral
 * @param filtros
 */
const updateCharts = function (filtros) {
    if (updateChart_bar) {
        BAR.updatedGlobalChart(filtros , data_UF);
    }

    if (updateChart_pie) {
        PIE.updatedGlobalChart(filtros, data_Porte);
    }

    if (updateChart_map) {
        MAP.updatedGlobalChart(filtros, data_Map);
    }

    if (updateChart_barrace) {
        BAR_RACE.updatedGlobalChart(filtros, dataEconomyActivity);
    }

    if (updateChart_barrace2) {
        BAR_RACE2.updatedGlobalChart(filtros, dataLegalNature);
    }

}

//----- Filtros -----//

/**
 * Filtra a UF informada no parâmetro
 * @param ufs String
 * @param data Json
 * @returns {{}}
 */
function filterUF(ufs, data) {

    const ufFiltrada = {};
    for (let uf in ufs) {
        ufFiltrada[ufs[uf]] = data[ufs[uf]];
    }

    return ufFiltrada;
}

/**
 * Filtra o porte informado no parâmetro
 * @param porte
 * @param data
 * @returns {any}
 */
function filterPorte(porte, data) {
    const portesFiltrados = {};
    for (const uf in data) {
        portesFiltrados[uf] = {};
        for (const p in porte) {
            portesFiltrados[uf][porte[p]] = {};
            for (const desc in data[uf][porte[p]]) {
                portesFiltrados[uf][porte[p]] = data[uf][porte[p]];
            }
        }
    }
    return JSON.parse(JSON.stringify(portesFiltrados));
}

/**
 * Filtra a Atividade Econômica informada no parâmetro
 * @param economyActivity String
 * @param data Json
 * @returns {{}}
 */
function filterEconomyActivity(economyActivity, data) {
    const dadosFiltrados = {};

    for (const uf in data) {
        dadosFiltrados[uf] = {};

        for (const porte in data[uf]) {
            dadosFiltrados[uf][porte] = {};

            for (const df in economyActivity) {
                dadosFiltrados[uf][porte][economyActivity[df]] = data[uf][porte][economyActivity[df]] != null ? data[uf][porte][economyActivity[df]] : 0;
            }
        }
    }
    return dadosFiltrados;
}

/**
 * Filtra a Natureza Jurídica informada no parâmetro
 * @param legalNature String
 * @param data Json
 * @returns {{}}
 */
function filterLegalNature(legalNature, data) {
    let dadosFiltrados = {};

    for (const uf in data) {
        dadosFiltrados[uf] = {};

        for (const porte in data[uf]) {
            dadosFiltrados[uf][porte] = {};

            for (const df in legalNature) {
                dadosFiltrados[uf][porte][legalNature[df]] = data[uf][porte][legalNature[df]] != null ? data[uf][porte][legalNature[df]] : 0;
            }
        }
    }

    return dadosFiltrados;
}

//----- End filtros -----//

/**
 * Transforma os dados para o padrão do gráfico Bar-Race
 * @param data
 * @returns {string[][]}
 */
function treatGlobalDataBarRace(data) {
    // Objeto para armazenar as descrições e contagens
    const agrupamento = {};

    // Percorre os dados e realiza o agrupamento
    for (const estado in data) {
        const categorias = data[estado];

        for (const categoria in categorias) {
            const descricaoQuantidades = categorias[categoria];

            for (const descricao in descricaoQuantidades) {
                const quantidade = descricaoQuantidades[descricao];

                if (agrupamento[descricao]) {
                    agrupamento[descricao].count += quantidade;
                } else {
                    agrupamento[descricao] = { count: quantidade, descricao };
                }
            }
        }
    }
    let result = [];
    for (let df in agrupamento) {
        result.push(agrupamento[df])
    }
    result = calcScore(result);
    return result;
}

/**
 * Calcula o score para definir a cor do elemento na barra
 * @param df
 * @returns {string[][]}
 */
function calcScore(df) {
    const result = [['score', 'amount', 'product']];
    // Ordenar por ordem decrescente com base no 'count'
    df.sort((a, b) => b.count - a.count);

    // Calcular o score com base no índice e no tamanho do array
    const scoreRange = df.length - 1;
    const calculateScore = (index) => (100 - (index / scoreRange) * 100).toFixed(1);

    // Adicionar o score, 'count' e 'descricao' aos arrays
    df.forEach(({ count, descricao }, index) => {
        const score = calculateScore(index);
        result.push([score, count, descricao]);
    });

    return result;
}

/**
 * Este método escreve os filtros selecionados em uma variável
 * @returns {string}
 */
function selectedFilters() {

    let filtros = '';
    //Escreve as Ufs selecionadas
    if(uf_clicks != '') {
        filtros += 'UF: ('
        for (let uf in uf_clicks) {
            if(uf == 0) {
                filtros += uf_clicks[uf];
            } else {
                filtros += ', ' + uf_clicks[uf]
            }
        }
        filtros += ')\n';
    }

    //Escreve os Portes selecionados
    if(porte_clicks != '') {
        filtros += ' Porte: ('
        for (let porte in porte_clicks) {
            if(porte == 0) {
                filtros += porte_clicks[porte];
            } else {
                filtros += ', ' + porte_clicks[porte]
            }
        }
        filtros += ')\n'
    }

    //Escreve as Atividades Econômicas selecionadas
    if(economyActivity_clicks != '') {
        filtros += ' Atividade Econômica: ('
        for (let atddEconomica in economyActivity_clicks) {
            if(atddEconomica == 0) {
                filtros += economyActivity_clicks[atddEconomica];
            } else {
                filtros += ', ' + economyActivity_clicks[atddEconomica]
            }
        }
        filtros += ')\n'
    }

    //Escreve as Naturezas Jurídicas selecionadas
    if(legalNature_clicks != '') {
        filtros += ' Natureza Jurídica: ('
        for (let natJuridica in legalNature_clicks) {
            if(natJuridica == 0) {
                filtros += legalNature_clicks[natJuridica];
            } else {
                filtros += ', ' + legalNature_clicks[natJuridica]
            }
        }
        filtros += ')\n'
    }

    return filtros;
}

function addDownload() {
    data_download.uf = uf_clicks;
    data_download.porte = porte_clicks;
    data_download.natureza_juridica = legalNature_clicks;
    data_download.atividade_economica = economyActivity_clicks;
}

function dataRegiao(data) {
    let total = 0;
    let norte = {}, nordeste = {}, sul = {}, sudeste = {}, centroOeste = {};
    for (let uf in data) {
        if ($.inArray(uf, ['PA', 'AM', 'TO', 'RO', 'AC',  'AP', 'RR']) != -1) {
            // Norte
            norte[uf] = {};
            let value = 0;
            for (let porte in data[uf]) {

                for (let desc in data[uf][porte]) {
                    value += data[uf][porte][desc];
                    total += value;
                }
            }
            norte[uf] = value;
        } else if ($.inArray(uf, ['BA', 'PE', 'CE', 'MA', 'PB', 'RN', 'PI', 'AL']) != -1) {
            // Nordeste
            nordeste[uf] = {};
            let value = 0;
            for (let porte in data[uf]) {

                for (let desc in data[uf][porte]) {
                    value += data[uf][porte][desc];
                    total += value;
                }
            }
            nordeste[uf] = value;
        } else if ($.inArray(uf, ['PR', 'RS', 'SC']) != -1) {
            // Sul
            sul[uf] = {};
            let value = 0;
            for (let porte in data[uf]) {

                for (let desc in data[uf][porte]) {
                    value += data[uf][porte][desc];
                    total += value;
                }
            }
            sul[uf] = value;
        } else if ($.inArray(uf, ['SP', 'MG', 'RJ', 'ES']) != -1) {
            // Sudeste
            sudeste[uf] = {};
            let value = 0;
            for (let porte in data[uf]) {

                for (let desc in data[uf][porte]) {
                    value += data[uf][porte][desc];
                    total += value;
                }
            }
            sudeste[uf] = value;
        }  else if ($.inArray(uf, ['GO', 'MT', 'DF', 'MS']) != -1) {
            // Centro-Oeste
            centroOeste[uf] = {};
            let value = 0;
            for (let porte in data[uf]) {

                for (let desc in data[uf][porte]) {
                    value += data[uf][porte][desc];
                    total += value;
                }
            }
            centroOeste[uf] = value;
        }
    }

    data_regiao['norte'] = norte;
    data_regiao['nordeste'] = nordeste;
    data_regiao['sul'] = sul;
    data_regiao['sudeste'] = sudeste;
    data_regiao['centro-oeste'] = centroOeste;
    data_total = total;
}
