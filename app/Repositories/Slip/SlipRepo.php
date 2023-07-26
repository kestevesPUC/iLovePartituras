<?php

namespace App\Repositories\Slip;

use App\Helpers\Constants\ContractConstants;
use App\Helpers\Constants\ProductConstants;
use App\Helpers\Constants\SystemConstants;
use App\Helpers\Error;
use App\Helpers\System;
use App\Helpers\Util;
use App\Repositories\AppRepo;
use App\Repositories\Person\PersonRepo;
use App\Repositories\Product\ProductRepo;
use App\Service\Contract\ContractService;
use App\Service\Financial\ItauService;
use App\Service\KryptonPay\SlipBank as KryptonPaySlipBank;
use App\Service\KryptonPay\Pix as KryptonPayPix;
use BradescoApi\Exceptions\BradescoApiException;
use BradescoApi\Exceptions\BradescoRequestException;
use Carbon\Carbon;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use KryptonPay\Api\ApiContext;
use KryptonPay\Api\KryptonPay;
use OpenBoleto\Agente;
use OpenBoleto\Banco\Bradesco;
use OpenBoleto\Banco\Itau;
use OpenBoleto\Banco\Santander;

class Slip extends AppRepo
{
    public const SANTANDER = "033";
    public const BRADESCO = "237";
    public const ITAU = "341";
    public const SICOOB = "756";
    public const KRYPTON_PAY = "888";

    /**
     * @param int $orderId
     * @param $ipAddress
     * @return false|string|void
     */
    public function getSlip(int $orderId, $ipAddress, $options = [])
    {
        try {
            return DB::transaction(function() use ($orderId, $ipAddress, $options) {
                System::saveLogOrFlow('pedido', [
                    'usuario_id' => Auth::id() ?? 1,
                    'pedido_id' => $orderId,
                    'discriminacao' => 'Cliente acessou o link de pagamento! IP de acesso: ' . $ipAddress
                ]);

                $dataCNAB = $this->buildDataCNAB($orderId);
                $slipData = $this->getSlipData($orderId);

                return $this->generateSlip($slipData, $dataCNAB, $options);
            });
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * @param int $orderId
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    private function buildDataCNAB(int $orderId)
    {
        $dataCNAB = DB::table('pedido as P')
            ->join('conta_receber as CR', 'P.id', '=', 'CR.pedido_id')
            ->join('contrato as C', 'P.representacao_id', '=', 'C.pessoa_id')
            ->where('P.id', $orderId)
            ->first([
                'CR.data_envio_cnab',
                'CR.data_vencimento',
                'C.dias_vencimento_boleto',
                'P.opcao_pagamento',
                'CR.tid'
            ]);

        if (!$dataCNAB->data_envio_cnab) {
            $date = Carbon::create($dataCNAB->data_vencimento);
            $dateNow = Carbon::now();
            if ($dataCNAB->data_vencimento < $dateNow) {
                $date = $dateNow->addDay();
            }

            DB::table('conta_receber')
                ->where('id', $orderId)
                ->update([
                    'data_vencimento' => $date->format('Y-m-d'),
                    'data_envio_cnab' => date('Y-m-d'),
                ]);
        }
        return $dataCNAB;
    }

    /**
     * @param int $orderId
     * @return mixed
     * @throws \Exception
     */
    private function getSlipData(int $orderId)
    {
        $dataSlip = DB::selectOne('
            SELECT
                C.data_vencimento,
                C.forma_pagamento,
                C.valor_cobranca as valor2,
                C.valor_cobranca_venda as valor,
                C.tid,
                C.pix_copia_cola,
                CT.tipo,
                E.bairro,
                E.cep,
                E.complemento,
                E.logradouro,
                E.numero,
                E.uf,
                E2.bairro as bairro_cliente,
                E2.bairro as bairro_cliente,
                E2.cep as cep_cliente,
                E2.complemento as complemento_cliente,
                E2.logradouro as logradouro_cliente,
                E2.numero as numero_cliente,
                E2.uf as uf_cliente,
                OP.gateway_link as usa_gateway,
                OP.codigo_operacao,
                OP.agencia_digito,
                OP.agencia,
                OP.carteira,
                OP.chave_acesso,
                OP.codigo_banco,
                OP.codigo_cliente,
                OP.codigo_municipio,
                OP.conta_digito,
                OP.conta,
                OP.instrucoes,
                OP.mora,
                OP.multa,
                P.nome,
                P.tipo as tipo_pessoa,
                P2.email as email_cliente,
                P2.nome as nome_cliente,
                PJ2.nome_fantasia as nome_fantasia,
                P2.tipo as tipo_cliente,
                PD.id,
                PD.opcao_pagamento,
                PD.representacao_id,
                CP.id as boleto_registrado,
                ( CASE WHEN P2.tipo = 1 THEN PF2.cpf ELSE PJ2.cnpj END ) as cpf_cnpj_cliente,
                ( CASE WHEN P.tipo = 1 THEN PF.cpf ELSE PJ.cnpj END ) as cpf_cnpj,
                ( CASE WHEN P3.tipo = 1 THEN PF3.cpf ELSE PJ3.cnpj END ) as cpf_cnpj_split,
                UPPER(
                    (
                        SELECT
                            xmunicipio
                        FROM
                            ibge
                        WHERE
                            (cuf || cmunicipio) = E.codigo_municipio
                        LIMIT
                        1
                    )
                ) as municipio,
                UPPER(
                    (
                        SELECT
                            xmunicipio
                        FROM
                            ibge
                        WHERE
                            (cuf || cmunicipio) = E2.codigo_municipio
                        LIMIT
                        1
                    )
                ) as municipio_cliente,
                (
                    SELECT
                        numero
                    FROM
                        telefone_fax
                    WHERE
                        pessoa_id = P2.id
                    LIMIT
                        1
                ) as cliente_telefone
            FROM
                pedido PD
            INNER JOIN
                opcao_pagamento OP ON PD.opcao_pagamento = OP.id
            INNER JOIN
                conta_receber C ON PD.id = C.pedido_id
            INNER JOIN
                contrato CT ON OP.contrato_id = CT.id
            INNER JOIN
                pessoa P ON CT.pessoa_id = P.id
            LEFT JOIN
                pessoa_fisica PF ON P.id = PF.id
            LEFT JOIN
                pessoa_juridica PJ ON P.id = PJ.id
            LEFT JOIN
                endereco E ON P.id = E.pessoa_id
            INNER JOIN
                pessoa P2 ON PD.emissao_nota_id = P2.id
            LEFT JOIN
                pessoa_fisica PF2 ON P2.id = PF2.id
            LEFT JOIN
                pessoa_juridica PJ2 ON P2.id = PJ2.id
            INNER JOIN
                pessoa P3 ON PD.representacao_id = P3.id
            LEFT JOIN
                pessoa_fisica PF3 ON P3.id = PF3.id
            LEFT JOIN
                pessoa_juridica PJ3 ON P3.id = PJ3.id
            LEFT JOIN
                endereco E2 ON P2.id = E2.pessoa_id
            LEFT JOIN
                cnab_pedido CP ON PD.id = CP.pedido_id AND PD.opcao_pagamento = CP.opcao_pagamento_id
            WHERE
                PD.id = ?', [$orderId]);

        if (!$dataSlip) {
            throw new \Exception('Não foram encontrados os dados necessários para gerar esse boleto.');
        } else if (!in_array($dataSlip->forma_pagamento, [SystemConstants::PAYMENT_TYPE_BILLET, SystemConstants::PAYMENT_TYPE_BANK_PIX])) {
            throw new \Exception('Não é possível realizar este pagamento através do boleto.');
        }

        return $dataSlip;
    }

    /**
     * @param $slipData
     * @param $dataCNAB
     * @return false|string
     * @throws \Exception
     */
    private function generateSlip($slipData, $dataCNAB, $options)
    {
        $descriptionStatement = ['Referente ao pedido nº: ' . $slipData->id, 'VALOR DE ' . Util::FormatRealMoney($slipData->valor), 'Taxa bancária - R$ 0,00'];
        $slipData->instrucoes = explode("\n", $slipData->instrucoes);


        switch ($slipData->codigo_banco) {
            case self::KRYPTON_PAY:
                $slip = $this->generateBankPaymentSlipFromKryptonPay($slipData, $options);
                break;
            default:
                throw new \Exception('Não foi possível gerar o boleto informado, entre em contato com o administrador do sistema.');
        }
        return $slip;
    }


    private function generateBankPaymentSlipFromKryptonPay($slipData, $options)
    {
        $apiContext = new ApiContext();
        $apiContext->setIsSandbox(!app()->environment('production'));
        $apiContext->setApiToken(!app()->environment('production') ? env('TOKEN_KRYPTON_PAY_HOMOLOGATION') : $slipData->chave_acesso);
        $kryptonPayTransaction = (array)KryptonPay::getTransaction($apiContext, $slipData->id);

        if (!isset($kryptonPayTransaction[0])) {
            $products = [];
            $items = app()->make(ProductRepo::class)->getProductsByOrderId($slipData->id);
            foreach ($items as $p) {
                $products[] = [
                    'id' => $p->id,
                    'description' => $p->nome,
                    'quantity' => $p->quantidade,
                    'value' => $p->valor,
                ];

                if ($slipData->opcao_pagamento == ContractConstants::PAYMENT_OPTION_KRYPTON_PAY_KTECH
                    && $p->id == ProductConstants::MARKET_RESEARCH
                ) {
                    Arr::set($options, 'application', ContractConstants::MARKET_RESEARCH_APPLICATION);
                }
            }

            $splitData = [];
            if ($slipData->opcao_pagamento == ContractConstants::PAYMENT_OPTION_KRYPTON_PAY_LINK
                && $slipData->representacao_id != ContractConstants::PERSON_LINK
            ) {
                $slipData->cpf_cnpj_split = ContractService::discoveryParentArContractByDocument($slipData->cpf_cnpj_split);
                $splitData[] = [
                    'document' => $slipData->cpf_cnpj_split,
                    'value' => $slipData->valor,
                ];
                Arr::set($options, 'application', ContractConstants::RENEWAL_APPLICATION);
                Arr::set($options, 'referenceTable', 'eyJpdiI6InRcL09wRUx0cTllb01hM0EzWEZQY2F3PT0iLCJ2YWx1ZSI6ImdXNVdjOUtYb2JqY1JjdGVqNFlsQkE9PSIsIm1hYyI6IjQxNDZhNjM2NTg5YjU1OWYwN2FjNTU1NDgyYWFhYzdmZjNmZTMyOTkxZjkyZWJiZjE3ZjQwOGRkZTEzMmM0ZTIifQ==');
            }

            $client = PersonRepo::searchPersonByDocument(['document' => $slipData->cpf_cnpj_cliente]);
            $slipBankData = [
                'application' => Arr::get($options, 'application'),
                'orderId' => $slipData->id,
                'token' => !app()->environment('production') ? env('TOKEN_KRYPTON_PAY_HOMOLOGATION') : $slipData->chave_acesso,
                'person' => [
                    'type' => Arr::get($client, 'tipo'),
                    'name' => Arr::get($client, 'nome'),
                    'email' => Arr::get($client, 'email'),
                    'phone' => Util::removerMaskTel(Arr::get($client, 'phones.0.numero')),
                    'document' => Arr::get($client, 'person_legal.cnpj') ?? Arr::get($client, 'person_physical.cpf'),
                    'birth' => Arr::get($client, 'person_physical.data_nascimento'),
                    'address' => [
                        'zipCode' => Arr::get($client, 'address.cep'),
                        'street' => Arr::get($client, 'address.logradouro'),
                        'number' => Arr::get($client, 'address.numero'),
                        'complement' => Arr::get($client, 'address.complemento'),
                        'neighborhood' => Arr::get($client, 'address.bairro'),
                        'city' => Arr::get($client, 'address.codigo_municipio'),
                        'state' => Arr::get($client, 'address.uf'),
                    ],
                ],
                'payment' => [
                    'dueDate' => $slipData->data_vencimento,
                    'value' => $slipData->valor,
                ],
                'products' => $products,
                'split' => $splitData,
                'referenceTable' => Arr::get($options, 'referenceTable'),
            ];
            if ($slipData->forma_pagamento == SystemConstants::PAYMENT_TYPE_BILLET) {
                return file_get_contents(((new KryptonPaySlipBank())->process($slipBankData))->opcaoPagamento->url);
            } else if ($slipData->forma_pagamento == SystemConstants::PAYMENT_TYPE_BANK_PIX) {
                return file_get_contents(((new KryptonPayPix())->process($slipBankData))->opcaoPagamento->url);
            }
        } else {
            return file_get_contents($kryptonPayTransaction[0]->opcaoPagamento->url);
        }
    }
}
