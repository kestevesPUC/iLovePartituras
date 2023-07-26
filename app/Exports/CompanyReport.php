<?php

namespace App\Exports;

use App\Helpers\Util;
use App\Models\Address\Rfb\Company;
use App\Models\Order\Order;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyReport extends SheetReports
{
    private $lines = 0;
    private $valuePerLine = 0.03;
    private $fileValue;
    private $id_pedido;
    private $preOrderTotalPrice;
    private $preOrderCountLinesTotal;
    private $data;

    public function buildReport(): array
    {
        if (isset($this->options['cityName']) && !empty($this->options['cityName'])) {
            $this->options['cityName'] = str_replace('Todos', '', Arr::get($this->options, 'cityName'));
        }

        $titleSheet = 'EMPRESAS';

        if (Arr::get($this->options, 'state')) {
            $titleSheet .= ' DE ';
            $titleSheet .= Arr::get($this->options, 'cityName')
                ? (Arr::get($this->options, 'cityName') . ' - ' . Arr::get($this->options, 'state'))
                : ('- ' . Arr::get($this->options, 'state'));
        }

        $this->buildHeader('Empresas', $titleSheet, false, 0, $this->getHeader());
        $this->buildData(0, 4, "A3:U3", $this->popularTable($this->data = $this->getData()));

        $file = parent::buildReport();
        return ['success' => true, 'file' => $file, 'lines' => $this->lines];
    }

    private function getHeader()
    {
        return [
            ['coordinate' => 'A3', 'value' =>                   "CNPJ"],
            ['coordinate' => 'B3', 'value' =>           "Razão Social"],
            ['coordinate' => 'C3', 'value' =>          "Nome Fantasia"],
            ['coordinate' => 'D3', 'value' =>              "Cód. CNAE"],
            ['coordinate' => 'E3', 'value' =>                   "CNAE"],
            ['coordinate' => 'F3', 'value' =>      "Natureza Jurídica"],
            ['coordinate' => 'G3', 'value' =>                    "Cep"],
            ['coordinate' => 'H3', 'value' =>                 "Estado"],
            ['coordinate' => 'I3', 'value' =>                 "Cidade"],
            ['coordinate' => 'J3', 'value' =>                 "Bairro"],
            ['coordinate' => 'K3', 'value' =>             "Logradouro"],
            ['coordinate' => 'L3', 'value' =>                 "Numero"],
            ['coordinate' => 'M3', 'value' =>            "Complemento"],
            ['coordinate' => 'N3', 'value' =>             "Telefone 1"],
            ['coordinate' => 'O3', 'value' =>             "Telefone 2"],
            ['coordinate' => 'P3', 'value' =>                  "Email"],
            ['coordinate' => 'Q3', 'value' =>   "Tipo Estabelecimento"],
            ['coordinate' => 'R3', 'value' =>      "Opta pelo Simples"],
            ['coordinate' => 'S3', 'value' =>                  "Porte"],
            ['coordinate' => 'T3', 'value' =>          "Data Abertura"],
            ['coordinate' => 'U3', 'value' =>         "Capital Social"]
        ];
    }

    private function popularTable($companies)
    {
        $arrayData = [];
        foreach ($companies as $company) {
            $arrayData[] = [
                ['coordinate' => 'A', 'value' => Util::setMaskCpfCnpj($company->cnpj),   'type' => 's'],
                ['coordinate' => 'B', 'value' => $company->razao_social,                 'type' => 's'],
                ['coordinate' => 'C', 'value' => $company->nome_fantasia,                'type' => 's'],
                ['coordinate' => 'D', 'value' => $company->cod_cnae,                     'type' => 's'],
                ['coordinate' => 'E', 'value' => $company->cnae,                         'type' => 's'],
                ['coordinate' => 'F', 'value' => $company->natureza_juridica,            'type' => 's'],
                ['coordinate' => 'G', 'value' => Util::setMaskZipCode($company->cep),    'type' => 's'],
                ['coordinate' => 'H', 'value' => $company->uf,                           'type' => 's'],
                ['coordinate' => 'I', 'value' => $company->cidade,                       'type' => 's'],
                ['coordinate' => 'J', 'value' => $company->bairro,                       'type' => 's'],
                ['coordinate' => 'K', 'value' => $company->empresas_logradouro,          'type' => 's'],
                ['coordinate' => 'L', 'value' => $company->empresa_numero,               'type' => 's'],
                ['coordinate' => 'M', 'value' => $company->empresa_complemento,          'type' => 's'],
                ['coordinate' => 'N', 'value' => Util::setMaskPhone($company->telefone1),'type' => 's'],
                ['coordinate' => 'O', 'value' => Util::setMaskPhone($company->telefone2),'type' => 's'],
                ['coordinate' => 'P', 'value' => $company->email,                        'type' => 's'],
                ['coordinate' => 'Q', 'value' => $company->tipo_estabelecimento,         'type' => 's'],
                ['coordinate' => 'R', 'value' => $company->opc_simples,                  'type' => 's'],
                ['coordinate' => 'S', 'value' => $company->porte,                        'type' => 's'],
                ['coordinate' => 'T', 'value' => $company->data_inicio_ativ,             'type' => 's'],
                ['coordinate' => 'U', 'value' => "R$ " . $company->capital_social,       'type' => 's'],
            ];
            $this->lines++;

        }
        $this->fileValue = $this->lines * $this->valuePerLine;
        $this->preOrderCountLinesTotal = count($arrayData);

        return $arrayData;
    }

    private function setOrder()
    {
        try {
            $solicitacao = $this->recoverOrder();

            is_null($solicitacao) ? $solicitacao = 1 : $solicitacao = ++$solicitacao->id_solicitacao;
            return Order::query()
                ->insertGetId([
                    'id_user' => Session::get('usuario.id'),
                    'lines' => $this->lines,
                    'status' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'solicitation_id' => $solicitacao,
                    'person_id' => Session::get('usuario.pessoa_id'),
                    'payment_option' => 1
                ]);
        } catch (\Exception $e) {

        }
    }

    private function recoverOrder()
    {
        try {
            return Order::query()
                ->select('solicitation_id')
                ->where('id_user', Session::get('usuario.pessoa_id'))
                ->orderBy('solicitation_id', 'desc')
                ->limit(1)
                ->get()
                ->first();
        } catch (\Exception $e) {
        }
    }

    private function getData()                                                      // CNPJ LINK 11508222000136
    {
        try {
            if (isset($this->options['state']) && !empty($this->options['state'])) {
                $this->options['state'] = str_replace('Todos', '', Arr::get($this->options, 'state'));
            }
            if (isset($this->options['city_code']) && !empty($this->options['city_code'])) {
                $this->options['city_code'] = str_replace('Todos', '', Arr::get($this->options, 'city_code'));
            }

            if (isset($this->options['phone']) && !empty($this->options['phone'])) {
                $aux = explode(" ", $this->options['phone']);
                $ddd = Util::removerMaskTel(Arr::get($aux, 0));
                $phone = Util::removerMaskTel(Arr::get($aux, 1));
            }

            if (isset($this->options['cep']) && !empty($this->options['cep'])) {
                $this->options['cep'] = Util::removeMaskZipCode(Arr::get($this->options, 'cep'));

            }

            if (isset($this->options['neighborhood']) && !empty($this->options['neighborhood'])) {
                $neighborhood = str_replace("BAIRRO ", "", Arr::get($this->options, 'neighborhood'));
                $neighborhood = str_replace('B. ', "", $neighborhood);
                $neighborhood = str_replace('REGIÃO ', "", $neighborhood);
                $this->options['neighborhood'] = str_replace('REGIAO ', "", $neighborhood);

            }

            if (isset($this->options['type_establishment']) && !empty($this->options['type_establishment'])) {
                $this->options['type_establishment'] = str_replace('Todos', '', Arr::get($this->options, 'type_establishment'));
            }

            if (isset($this->options['simple']) && !empty($this->options['simple'])) {
                $this->options['simple'] = str_replace('Todos', '', Arr::get($this->options, 'simple'));
            }

            if (isset($this->options['postage']) && !empty($this->options['postage'])) {
                $this->options['postage'] = str_replace('Todos', '', Arr::get($this->options, 'postage'));
            }

            if (isset($this->options['cnae']) && !empty($this->options['cnae'])) {
                $this->options['cnae'] = str_replace('Todos', '', Arr::get($this->options, 'cnae'));
            }

            if (isset($this->options['legalNature']) && !empty($this->options['legalNature'])) {
                $this->options['legalNature'] = str_replace('Todos', '', Arr::get($this->options, 'legalNature'));
            }

            if (isset($this->options['inptCapitalMin']) && !empty($this->options['inptCapitalMin'])) {
                $capitalMin = Util::removeFormatRealMoneyStringToValue(Arr::get($this->options, 'inptCapitalMin'));
                $this->options['inptCapitalMin'] == 0.0 ? $capitalMin = str_replace(',', '.', str_replace('.', '', Arr::get($this->options, 'inptCapitalMin'))) : $capitalMin;

            }

            if (isset($this->options['inptCapitalMax']) && !empty($this->options['inptCapitalMax'])) {
                $capitalMax = Util::removeFormatRealMoneyStringToValue(Arr::get($this->options, 'inptCapitalMax'));
                $capitalMax == 0.0 ? $capitalMax = str_replace(',', '.', str_replace('.', '', Arr::get($this->options, 'inptCapitalMax'))) : $capitalMax;
                $this->options['inptCapitalMax'] == '0.0' ? "" : $capitalMax;
            }

            $companies = Company::query()
                ->selectRaw("empresas.cnpj, empresas.nome_fantasia, empresas.razao_social, empresas.cnae_fiscal as cod_cnae,
                 \"C\".descricao as cnae,
                  nj.descricao as natureza_juridica, empresas.cep, empresas.uf,
                \"M\".descricao as cidade, empresas.bairro, concat(empresas.ddd_1, ' ' ,empresas.telefone_1) as telefone1,
                concat(empresas.ddd_2, ' ' ,empresas.telefone_2 ) as telefone2, empresas.email, empresas.capital_social,
                (CASE
                    WHEN empresas.porte = 1 AND empresas.opc_mei::text = 'S'::text THEN 'MEI'::text
                    WHEN empresas.porte = 1 AND empresas.opc_mei::text <> 'S'::text THEN 'ME'::text
                    WHEN empresas.porte = 3 THEN 'EPP'::text
                    WHEN empresas.porte = 5 THEN 'Demais'::text
                    ELSE 'Não Informado'::text
                END) as porte,
                TO_CHAR(empresas.data_inicio_ativ, 'DD/MM/YYYY') as data_inicio_ativ,
                CASE WHEN empresas.matriz_filial = 1 THEN 'MATRIZ'
                    ELSE 'FILIAL' END as tipo_estabelecimento,
                CASE WHEN empresas.opc_simples = 'S' THEN 'SIM'
                    ELSE 'NÃO' END as opc_simples, empresas.logradouro as empresas_logradouro, empresas.numero as empresa_numero,
                     empresas.complemento as empresa_complemento")
                ->join('cnaes as C', 'empresas.cnae_fiscal', '=', 'C.cod_cnae')
                ->join('municipios as M', 'empresas.cod_municipio', '=', 'M.cod_municipio')
                ->join('natureza_juridica as nj', 'empresas.cod_nat_juridica', '=', DB::raw("nj.cod_nat_juridica::int"));

            if (isset($this->options['cnpj']) && !empty($this->options['cnpj'])) {
                $companies->where('cnpj', Util::removerMaskDocument(Arr::get($this->options, 'cnpj')));
            } else {
                $companies->where('empresas.situacao', 2);

                if (Arr::get($this->options, 'state')) {
                    $companies->where('M.uf', Arr::get($this->options, 'state'));
                }

                if (Arr::get($this->options, 'city_code')) {
                    $companies->where('M.cod_municipio', Arr::get($this->options, 'city_code'));
                }

                if (Arr::get($this->options, 'neighborhood')) {
                    $companies->where('bairro', 'ilike', '%' . Arr::get($this->options, 'neighborhood') . '%');
                }

                if (Arr::get($this->options, 'type_establishment')) {
                    $companies->where('empresas.matriz_filial', Arr::get($this->options, 'type_establishment'));
                }

                if (Arr::get($this->options, 'simple')) {
                    $companies->where('empresas.opc_simples', Arr::get($this->options, 'simple'));
                }

                if (Arr::get($this->options, 'postage')) {
                    $companies->where('empresas.porte', Arr::get($this->options, 'postage'));
                }

                if (Arr::get($this->options, 'cnae')) {
                    $companies->whereIn('empresas.cnae_fiscal', (array)Arr::get($this->options, 'cnae'));
                }

                if (Arr::get($this->options, 'legalNature')) {
                    $companies->whereIn('empresas.cod_nat_juridica', (array)Arr::get($this->options, 'legalNature'));
                }

                if (Arr::get($this->options, 'openDateBegin')) {
                    $companies->whereBetween('empresas.data_inicio_ativ', [Carbon::createFromFormat('d/m/Y H:i:s', Arr::get($this->options, 'openDateBegin') . " 00:00:00")->format("Y-m-d H:i:s"), Carbon::createFromFormat('d/m/Y H:i:s', Arr::get($this->options, 'openDateEnd') . " 00:00:00")->format("Y-m-d H:i:s")]);
                }

                if (Arr::get($this->options, 'corporateName')) {
                    $companies->where('empresas.razao_social', 'ilike', '%' . Arr::get($this->options, 'corporateName') . '%');
                }

                if (Arr::get($this->options, 'email')) {
                    $companies->where('empresas.email', 'ilike', '%' . Arr::get($this->options, 'email') . '%');
                }

                if (Arr::get($this->options, 'phone')) {
                    $companies->where('empresas.telefone_1', 'ilike', '%' . $phone . '%');
                }

                if (Arr::get($this->options, 'ddd')) {
                    $companies->where('empresas.ddd_1', 'ilike', '%' . $phone . '%');
                }

//                if (Arr::get($this->options, 'ddd')) {
//                    $companies->where('empresas.ddd_2', 'ilike','%' . $phone . '%');
//                }
//
//                if (Arr::get($this->options, 'phone')) {
//                    $companies->where('empresas.telefone_2', 'ilike','%' . $phone . '%');
//                }

                if (Arr::get($this->options, 'cep')) {
                    $companies->where('empresas.cep', Arr::get($this->options, 'cep'));
                }

                if (Arr::get($this->options, 'inptCapitalMin')) {
                    $companies->where('empresas.capital_social', '>=', $capitalMin);
                }

                if (Arr::get($this->options, 'inptCapitalMax')) {
                    $companies->where('empresas.capital_social', '<=', $capitalMax);
                }
            }

            return $companies->get();

        } catch (\Exception $e) {
            dd($e);
        }
    }


}
