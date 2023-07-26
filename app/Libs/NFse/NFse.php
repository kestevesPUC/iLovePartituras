<?php namespace App\Libs\NFse;

use App\Libs\NFse\Configuration;
use App\Repositories\NFse\NFseRepo;
use Carbon\Carbon;
use Exception;
use NFse\Models\ConsultNFse as MdlConsultNFse;
use NFse\Models\Lot;
use NFse\Models\NFse as mdlNFse;
use NFse\Models\Rps;
use NFse\Service\ConsultBatch;
use NFse\Service\ConsultNFSe;
use NFse\Service\LoteRps;
use NFse\Service\LotStatusConsultation;
use NFse\Service\NFseCancellation;
use NFse\Service\PrintPDFNFse;
use NFse\Service\Rps as RpsService;

class NFse extends Configuration
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string numero do rps
     *
     * consulta lote
     */
    public function consultBatch(string $protocol)
    {
        try {
            $sync = new ConsultBatch($this->settings, $protocol);
            $result = $sync->sendConsultation();

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * @param Object dados para impressão
     *
     * adiciona as rps
     */
    public function sendBatch(object $data)
    {
        try {
            //set Identificacao Rps
            $parameter = new Lot();
            $parameter->rpsLot = $data->lot->number_lot;
            $parameter->rps->number = $data->number_rps;
            $parameter->rps->serie = $data->series_rps;
            $parameter->rps->type = $data->series_rps;
            $parameter->rps->date = date('Y-m-d H:i:s');
            $parameter->rps->nature = $data->nature_operation;
            $parameter->rps->regime = ($data->regime) ?? null;
            $parameter->rps->simple = $data->opting_simple_national;
            $parameter->rps->culturalPromoter = $data->promoter_cultural;
            $parameter->rps->status = $data->status_rps;

            //set serviço
            $parameter->rps->service->itemList = $data->service->item_list_service;
            $parameter->rps->service->municipalityTaxationCode = str_pad($data->service->code_taxation_city, 9, '0', STR_PAD_LEFT);
            $parameter->rps->service->municipalCode = $data->service->city_installment_service;
            $parameter->rps->service->description = ($data->service->discription) ?? 'Não Declarado';
            $parameter->rps->service->serviceValue = ($data->service->value_service) ?? 0;
            $parameter->rps->service->issWithheld = ($data->service->iss_retain) ?? 1;
            $parameter->rps->service->issValue = ($data->service->value_service * $data->service->aliquot) / 100;
            $parameter->rps->service->aliquot = $data->service->aliquot;
            $parameter->rps->service->valueDeductions = ($data->service->value_deduction) ?? 0;
            $parameter->rps->service->otherDeductions = ($data->service->outras_deducoes) ?? 0;
            $parameter->rps->service->valuePis = ($data->service->value_pis) ?? 0;
            $parameter->rps->service->valueConfis = ($data->service->value_cofins) ?? 0;
            $parameter->rps->service->valueINSS = ($data->service->value_inss) ?? 0;
            $parameter->rps->service->valueIR = ($data->service->value_ir) ?? 0;
            $parameter->rps->service->valueCSLL = ($data->service->value_csll) ?? 0;
            $parameter->rps->service->discountCondition = ($data->service->value_discount_conditioned) ?? 0;
            $parameter->rps->service->unconditionedDiscount = ($data->service->value_discount_unconditioned) ?? 0;

            //set tomador
            $parameter->rps->taker->type = $data->taker->recommendation_cpf_cnpj;
            $parameter->rps->taker->name = $data->taker->reason_social_taker;
            $parameter->rps->taker->document = $data->taker->cpf_cnpj_taker;
            $parameter->rps->taker->municipalRegistration = $data->taker->inscription_municipal_taker;
            $parameter->rps->taker->email = $data->taker->email_taker;
            $parameter->rps->taker->phone = preg_replace('/\D/', '', $data->taker->emissao_nota_telefone_contato) != "" ? preg_replace('/\D/', '', $data->taker->emissao_nota_telefone_contato) : '3133276670';
            //set tomador endereço
            $parameter->rps->taker->address->address = $data->taker->address_taker;
            $parameter->rps->taker->address->number = $data->taker->number_address_taker;
            $parameter->rps->taker->address->complement = $data->taker->complement_address_taker;
            $parameter->rps->taker->address->neighborhood = $data->taker->neighborhood_taker;
            $parameter->rps->taker->address->zipCode = $data->taker->cep_taker;
            $parameter->rps->taker->address->state = $data->taker->uf_taker;
            $parameter->rps->taker->address->municipalityCode = ($data->taker->city_taker) ?? 0;

            $lote = (new LoteRps($this->settings, $parameter->rpsLot));
            $rps = (new RpsService($this->settings, $parameter->rps->number . $parameter->rps->serie));

            //set data
            $rps->setRpsIdentification($parameter);
            $rps->setService($parameter);
            $rps->setProvider();
            $rps->setTaker($parameter);

            //realiza chamada
            $signedRps = $rps->getSignedRps();
            $lote->addRps($signedRps);
            $result = $lote->sendLote();

            //Save Xml
            (new NFseRepo())->saveXML($result->response->nfse['xml'], $result->response->nfse['numero'], $result->response->nfse['prestadorCnpj']);

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string numero do rps
     *
     * consulta situação lote
     */
    public function seeBatchStatus(string $numberRps)
    {
        try {
            $rps = new Rps();
            $rps->number = $numberRps;

            $sync = new LotStatusConsultation($this->settings, $rps->number);
            $result = $sync->sendConsultation();

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Object dados de consulta
     *
     * consulta de NFse
     */
    public function consultNFse(object $data)
    {
        try {
            $parameters = new MdlConsultNFse();
            $parameters->startDate = $data->initialDate;
            $parameters->endDate = $data->finalDate;
            $parameters->takerType = $data->type;
            $parameters->document = $data->document;

            $find = new ConsultNFSe($this->settings);
            $result = $find->sendConsultation($parameters);

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Object dados de cancelamento
     *
     * consulta de NFse
     */
    public function nfseCancellation(object $data)
    {
        try {
            $parameters = (object)
                [
                'id' => $data->id_order,
                'numerNFse' => $data->number_nfse,
                'cancellationCode' => 2,
            ];

            $result = new NFseCancellation($this->settings, $parameters);
            $result = $result->sendConsultation();

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Object dados para impressão
     *
     * impressão de NFse
     */
    public function printNFse(object $data, string $type)
    {
        //set logo
        $logoBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAMMAAABJCAYAAACAVJIUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6Rjk2NkY2RDFBOTRDMTFFQjkyMDVFRjBCOTJDNERGRjQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6Rjk2NkY2RDJBOTRDMTFFQjkyMDVFRjBCOTJDNERGRjQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGOTY2RjZDRkE5NEMxMUVCOTIwNUVGMEI5MkM0REZGNCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGOTY2RjZEMEE5NEMxMUVCOTIwNUVGMEI5MkM0REZGNCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PhWNNHsAABD9SURBVHja7F0LcFxl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';

        if($data->competencia <= '2021-04-29 21:00:00') {
            $logoBase64 = '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';
        }

        $nfse = new mdlNFse();
        $nfse->year = substr($data->number_nfse, 0, 4);
        $nfse->number = (float) substr($data->number_nfse, 4);
        $nfse->dateEmission = Carbon::parse($data->date_emission_nfse)->format('d/m/Y');
        $nfse->timeEmission = Carbon::parse($data->date_emission_nfse)->format('H:i:s');
        $nfse->competence = Carbon::parse($data->competence)->format('d/m/Y');
        $nfse->verificationCode = $data->code_verification;
        $nfse->nfseNumberReplaced = ($data->number_nfse_substituida) ?? null;
        $nfse->cancellationCode = trim(($data->code_cancellation) ?? null);

        //prestador
        $nfse->provider->name = $data->reason_social_provider;
        $nfse->provider->cnpj = $data->cnpj_provider;
        $nfse->provider->inscription = trim($data->inscription_provider);
        $nfse->provider->phone = $data->telephone_provider;
        $nfse->provider->email = $data->email_provider;
        //prestador endereço
        $nfse->provider->address->address = $data->address_provider;
        $nfse->provider->address->number = $data->number_address_provider;
        $nfse->provider->address->neighborhood = $data->neighborhood_provider;
        $nfse->provider->address->complement = $data->complement_address_provider;
        $nfse->provider->address->zipCode = $data->cep_provider;
        $nfse->provider->address->city = $data->name_fantasy_provider;
        $nfse->provider->address->state = $data->uf_provider;

        //tomador
        $nfse->taker->name = $data->reason_social_taker;
        $nfse->taker->document = $data->cpf_cnpj_taker;
        $nfse->taker->municipalRegistration = ($data->inscription_municipal_taker) ?? false;
        //tomador endereço
        $nfse->taker->address = $data->address_taker;
        $nfse->taker->number = $data->number_address_taker;
        $nfse->taker->neighborhood = $data->neighborhood_taker;
        $nfse->taker->zipCode = $data->cep_taker;
        $nfse->taker->city = $data->nome_cidade_tomador;
        $nfse->taker->state = $data->uf_taker;

        //dados de serviço
        $nfse->service->description = $data->discription;
        $nfse->service->municipalityTaxationCode = str_pad(trim($data->code_taxation_city ?? $data->service->code_taxation_city), 9, '0', STR_PAD_LEFT);
        $nfse->service->taxCodeDescription = $data->descricao_tributacao_municipal;
        $nfse->service->itemList = $data->item_list_service;
        $nfse->service->itemDescription = $data->descricao_lista_servico;
        $nfse->service->municipalCode = $data->code_city_generating;
        $nfse->service->municipalName = $data->nome_municipio_gerador;
        $nfse->service->nature = $data->nature_operation;
        $nfse->service->specialTaxRegime = $data->regime_special_taxation;

        //valor der serviço
        $nfse->service->serviceValue = $data->value_service;
        $nfse->service->discountCondition = $data->value_discount_conditioned;
        $nfse->service->otherWithholdings = ($data->other_retentions) ?? 0;
        $nfse->service->issValueWithheld = ($data->tax_substitution && $data->city_taker == 3106200) ? ($data->value_iss) : 0;
        $nfse->service->netValue = ($data->value_net_nfse) ?? 0;
        $nfse->service->valueDeductions = ($data->value_deduction) ?? 0;
        $nfse->service->unconditionedDiscount = ($data->value_discount_unconditioned) ?? 0;
        $nfse->service->calculationBase = ($data->base_calculation) ?? 0;
        $nfse->service->aliquot = ($data->aliquot) ?? 0;
        $nfse->service->issValue = ($data->value_iss) ?? 0;
        $nfse->service->valuePis = ($data->value_pis) ?? 0;
        $nfse->service->valueConfis = ($data->value_cofins) ?? 0;
        $nfse->service->valueIR = ($data->value_ir) ?? 0;
        $nfse->service->valueCSLL = ($data->value_csll) ?? 0;
        $nfse->service->valueINSS = ($data->value_inss) ?? 0;

        $nfse->service->simpleNational = ($data->opting_simple_national);

        $print = new PrintPDFNFse($nfse, $logoBase64, '', '', '');

        return $print->getPDF($type);
    }
}
