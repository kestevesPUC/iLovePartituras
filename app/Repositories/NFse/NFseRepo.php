<?php namespace App\Repositories\NFse;

use App\Helpers\Constants\SystemConstants;
use App\Helpers\EmailHelper;
use App\Helpers\Util;
use App\Libs\NFse\NFse;
use App\Models\Helpers\Email;
use App\Models\NFse\Construction;
use App\Models\NFse\Intermediate;
use App\Models\NFse\Log;
use App\Models\NFse\Lot;
use App\Models\NFse\NFse as mdlNFse;
use App\Models\NFse\Provider;
use App\Models\NFse\Service;
use App\Models\NFse\Taker;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class NFseRepo
{
    private $nfse;
    private $dataNfse;

    public function __construct()
    {
        $this->nfse = new NFse();

        if (!\defined('NFE_PATH')) {
            \define('NFE_PATH', storage_path('arquivos-assinador') . '/nota_fiscal/');
        }
    }

    public function onPost (array $data, string $action) : array
    {
        try{
            DB::beginTransaction();
            $response = $this->{$action}($data);
            DB::commit();
            return $response;
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            return ['response' => false, 'message' => $e->getMessage()];
        }
    }

    public function onGet (array $data, string $action) : array|object
    {
        try{
            $response = $this->{$action}($data);
            return $response;
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            return ['success' => false, 'message' => "Não foi possível realizar a operação. Motivo: " . $e->getMessage()];
        }
    }


    public function issue (array $data) : array
    {
        $this->issueNotePerOrder($data['id_order']);
        (new OrderRepo())->saveOrderLog(["id_order" => $data['id_order']]);

        return ['success' => true, 'message' => 'Lote enviado com sucesso!'];
    }

    private function issueNotePerOrder (int $idOrder) : object
    {
        //Obtem dados pedido
        $this->nfse->order = (new OrderRepo())->getOrder($idOrder);

        //gerar um lot
        $this->nfse->order->lot = $this->generateBatchRps($this->nfse->order->id);

        //salva os dados no banco de dados
        $data = $this->insertDataNFseDataBase();
        $data->lot = $this->nfse->order->lot;

        //savar lot
        $result = $this->nfse->sendBatch($data);
        $this->saveLot($result);
        $this->updateNFse($data->id, $result);

        return $result;
    }

    private function generateBatchRps (int $idOrder) : object
    {
        $numberlot = date('y') . $idOrder . date('His');

        $lot = new Lot();
        $lot->environment = ($this->nfse->settings->environment == 'homologacao') ? 'h' : 'p';
        $lot->number_lot = $numberlot;
        $lot->id_user = (Auth::id()) ? Auth::id() : SystemConstants::USER_SYSTEM;
        $lot->date_creation = date('Y-m-d H:i:s');
        $lot->status = SystemConstants::WAITING_TO_SENT;
        $lot->save();

        return $lot;
    }

    private function insertDataNFseDataBase () : object
    {
        //inserir dados da NFse
        $mdlNFse = $this->insertNFseDataBase();
        $mdlNFse = $this->insertServiceDataBase($mdlNFse);
        $mdlNFse = $this->insertProviderDataBase($mdlNFse);
        $mdlNFse = $this->insertTakerDataBase($mdlNFse);

        return $mdlNFse;
    }

    private function insertNFseDataBase () : object
    {
        $mdlNFse = new mdlNFse();

        //set NFse
        $mdlNFse->environment = ($this->nfse->settings->environment == 'homologacao') ? 'h' : 'p';
        $mdlNFse->id_order = $this->nfse->order->id;
        $mdlNFse->number_rps = $this->nfse->order->id . date('s');
        $mdlNFse->id_lot = $this->nfse->order->lot->id;
        $mdlNFse->series_rps = 1;
        $mdlNFse->type_rps = 1;
        $mdlNFse->date_emission_rps = date('Y-m-d H:i:s');
        $mdlNFse->nature_operation = 1;
        $mdlNFse->regime_special_taxation = null;
        $mdlNFse->opting_simple_national = 2;
        $mdlNFse->promoter_cultural = 2;
        $mdlNFse->status_rps = 1;
        $mdlNFse->code_city_generating = SystemConstants::COUNTY_BELO_HORIZONTE;
        $mdlNFse->uf_city_generating = 'MG';
        $mdlNFse->save();

        $construction = new Construction();
        $mdlNFse->construction()->save($construction);
        //set construction
        $mdlNFse->construction = $construction;

        $intermediate = new Intermediate();
        $mdlNFse->Intermediate()->save($intermediate);
        //set intermediate
        $mdlNFse->intermediate = $intermediate;

        return $mdlNFse;
    }

    private function insertServiceDataBase (object $mdlNFse) : object
    {
        $service = new Service();
        $service->value_service = $this->nfse->order->title_value;
        $service->item_list_service = '1.05';
        $service->code_taxation_city = 10500188;
        $service->city_installment_service = SystemConstants::COUNTY_BELO_HORIZONTE;
        $service->discription = "LICENCIAMENTO DE USO DE PROGRAMAS DE COMPUTACAO NAO-CUSTOMIZAVEIS - APOIO NA EMISSAO DE ASSINATURA DIGITAL. \n Valor aproximado dos tributos conforme decreto de Lei 12.741/12 16,43%";
        $service->aliquot = SystemConstants::ALIQUOT;
        $service->iss_retain = ($this->nfse->order->cliente_substituicao_tributaria && $this->nfse->order->emissao_nota_tipo == SystemConstants::TYPE_PERSON_CNPJ && $this->nfse->order->emissao_nota_codigo_municipio == SystemConstants::COUNTY_BELO_HORIZONTE) ? 1 : 2;
        $service->value_iss = number_format($this->nfse->order->title_value * $service->aliquot / 100, 2);
        $service->value_iss_retain = ($service->aliquot == 1) ? $service->value_iss : 0;
        $service->base_calculation = $this->nfse->order->title_value;
        //set service
        $mdlNFse->service()->save($service);
        $mdlNFse->service = $service;

        return $mdlNFse;
    }

    private function insertProviderDataBase (object $mdlNFse) : object
    {
        $provider = new Provider();
        $provider->inscription_provider = '12784350012';
        $provider->reason_social_provider = 'KRYPTON TECH DESENVOLVIMENTO DE SOFTWARE LTDA';
        $provider->name_fantasy_provider = null;
        $provider->cnpj_provider = '40569411000117';
        $provider->address_provider = 'Rua Visconde de Taunay';
        $provider->number_address_provider = '173';
        $provider->complement_address_provider = 'SALA: 202';
        $provider->neighborhood_provider = 'São Lucas';
        $provider->city_provider = SystemConstants::COUNTY_BELO_HORIZONTE;
        $provider->uf_provider = 'MG';
        $provider->cep_provider = '30240300';
        $provider->email_provider = 'atendimento@kryptontech.com.br';
        $provider->telephone_provider = '3132444800';

        $mdlNFse->provider()->save($provider);
        //set provider
        $mdlNFse->provider = $provider;

        return $mdlNFse;
    }

    private function insertTakerDataBase (object $mdlNFse) : object
    {
        $taker = new Taker();
        $taker->cpf_cnpj_taker = ($this->nfse->order->emissao_nota_tipo == 2) ? $this->nfse->order->emissao_nota_cnpj : $this->nfse->order->emissao_nota_cpf;
        $taker->recommendation_cpf_cnpj = ($this->nfse->order->emissao_nota_tipo == 2) ? 1 : 2;
        $taker->inscription_municipal_taker = preg_replace('/\D/', '', $this->nfse->order->emissao_nota_inscricao_municipal);
        $taker->reason_social_taker = $this->nfse->order->emissao_nota_nome;
        $taker->address_taker = $this->nfse->order->emissao_nota_logradouro;
        $taker->number_address_taker = substr($this->nfse->order->emissao_nota_numero, 0, 10);
        $taker->complement_address_taker = $this->nfse->order->emissao_nota_complemento;
        $taker->neighborhood_taker = $this->nfse->order->emissao_nota_bairro;
        $taker->city_taker = $this->nfse->order->emissao_nota_codigo_municipio;
        $taker->uf_taker = $this->nfse->order->emissao_nota_uf;
        $taker->cep_taker = Util::removeMaskZipCode($this->nfse->order->emissao_nota_cep);
        $taker->email_taker = $this->nfse->order->emissao_nota_email;
        $taker->telephone_taker = preg_replace('/\D/', '', $this->nfse->order->emissao_nota_telefone_contato);

        $mdlNFse->taker()->save($taker);
        //set taker
        $mdlNFse->taker = $taker;

        return $mdlNFse;
    }

    public function saveLot (object $result) : object
    {
        try {
            $lot = Lot::find($this->nfse->order->lot->id);
            $lot->environment = ($this->nfse->settings->environment == 'homologacao') ? 'h' : 'p';
            if (isset($result->response->protocolo)) {
                $lot->protocol = $result->response->protocolo;
                $lot->number_lot = $result->response->numeroLote;
                $lot->date_creation = (new Carbon())->format('Y-m-d H:i:s');
                $lot->id_user = (Auth::id()) ? Auth::id() : SystemConstants::USER_SYSTEM;
                $lot->date_receipt = (new Carbon($result->response->dataRecebimento))->format('Y-m-d H:i:s');
                $lot->status = 2;
            } else {
                $lot->status = 5;
                throw new Exception($result->messages->scalar);
            }
            $lot->save();

            return $lot;
        } catch (Exception $ex) {
            $lot = Lot::find($this->nfse->order->lot->id);
            $lot->environment = ($this->nfse->settings->environment == 'homologacao') ? 'h' : 'p';
            $lot->date_creation = (new Carbon())->format('Y-m-d H:i:s');
            $lot->id_user = (Auth::id()) ? Auth::id() : SystemConstants::USER_SYSTEM;
            $lot->protocol = null;
            $lot->date_receipt = null;
            $lot->number_lot = null;
            $lot->status = 6;
            $lot->save();

            return $lot;
        }
    }

    private function updateNFse (int $id, object $result)
    {
        $mdlNFse = mdlNFse::query()->where('id', '=', $id)->first();

        if($result->response->xml){
            $this->dataNfse = (simplexml_load_string($result->response->xml))->ListaNfse->CompNfse->Nfse->InfNfse;
            $data = $this->dataNfse;
        }

        $mdlNFse->number_nfse = $result->numero ?? Arr::get($result->response->nfse, 'numero');
        $mdlNFse->code_verification = $result->codigoVerificacao ?? Arr::get($result->response->nfse, 'codigoVerificacao') ;
        $mdlNFse->date_emission_nfse = Carbon::parse($result->dataEmissao ?? Arr::get($result->response->nfse, 'dataEmissao') )->format('Y-m-d H:i:s');
        $mdlNFse->competence = Carbon::parse($result->competencia ?? Arr::get($result->response->nfse, 'competencia') )->format('Y-m-d H:i:s');

        $rpsIdentification = $result->identificacaoRps ?? $data->IdentificacaoRps;
        $mdlNFse->number_rps = $rpsIdentification->numero ?? $rpsIdentification->Numero;
        $mdlNFse->series_rps = $rpsIdentification->serie ?? $rpsIdentification->Serie;
        $mdlNFse->type_rps = $rpsIdentification->tipo ?? $rpsIdentification->Tipo;

        $mdlNFse->date_emission_rps = $result->dataEmissaoRps ?? $data->DataEmissaoRps;
        $mdlNFse->nature_operation = $result->naturezaOperacao ?? $data->NaturezaOperacao;
        $mdlNFse->regime_special_taxation = $result->regimeEspecialTributacao ?? null;
        $mdlNFse->opting_simple_national = $result->optanteSimplesNacional ?? $data->OptanteSimplesNacional;
        $mdlNFse->promoter_cultural = $result->incentivadorCultural ?? $data->IncentivadorCultural;

        $generatorOrgan = $result->orgaoGerador ?? $data->OrgaoGerador;
        $mdlNFse->code_city_generating = $generatorOrgan->codigoMunicipio ?? $generatorOrgan->CodigoMunicipio;
        $mdlNFse->uf_city_generating = $generatorOrgan->uf ?? $generatorOrgan->Uf;
        $mdlNFse->save();

        $cc = Construction::find($id);
        $mdlNFse->construction()->save($cc);

        $intermediate = Intermediate::find($id);
        $mdlNFse->intermediate()->save($intermediate);

        $this->updateService($id, $mdlNFse, $result);
        $this->updateProvider($id, $mdlNFse, $result);
        $this->updateTaker($id, $mdlNFse, $result);

    }

    private function updateService (int $id, object $mdlNFse, object $result)
    {
        $service = Service::find($id);
        $result = $result->servico ?? $this->dataNfse->Servico;

        $service->code_taxation_city = $result->codigoTributacaoMunicipio ?? $result->CodigoTributacaoMunicipio;
        $service->city_installment_service = $result->codigoMunicipio ?? $result->CodigoMunicipio;
        $service->item_list_service = $result->itemListaServico ?? $result->ItemListaServico;
        $service->discription = $result->discriminacao ?? $result->Discriminacao;

        $values = $result->valores ?? $result->Valores;
        $service->value_net_nfse = $values->valorLiquidoNfse ?? $values->ValorLiquidoNfse;
        $service->value_service = $values->valorServicos ?? $values->ValorServicos;
        $service->aliquot = $values->aliquota ?? $values->Aliquota;
        $service->iss_retain = $values->issRetido ?? $values->IssRetido;
        $service->value_iss = $values->valorIss ?? $values->ValorIss;
        $service->value_iss_retain = $values->valorIssRetido ?? 0;
        $service->value_deduction = $values->valorDeducoes ?? $values->ValorDeducoes;
        $service->value_pis = $values->valorPis ?? $values->ValorPis;
        $service->value_cofins = $values->valorCofins ?? $values->ValorCofins;
        $service->value_inss = $values->valorInss ?? $values->ValorInss;
        $service->value_ir = $values->valorIr ?? $values->ValorIr;
        $service->value_csll = $values->valorCsll ?? $values->ValorCsll;
        $service->base_calculation = $values->baseCalculo ?? $values->BaseCalculo;
        $service->value_discount_conditioned = $values->descontoCondicionado ?? $values->DescontoCondicionado;
        $service->value_discount_unconditioned = $values->descontoIncondicionado ?? $values->DescontoIncondicionado;
        $mdlNFse->service()->save($service);
    }

    private function updateProvider (int $id, object $mdlNFse, object $result)
    {
        $provader = Provider::find($id);
        $result = $result->prestadorServico ?? $this->dataNfse->PrestadorServico;

        $provader->inscription_provider = $result->inscricaoMunicipal ?? $result->IdentificacaoPrestador->InscricaoMunicipal;
        $provader->reason_social_provider = $result->razaoSocial ?? $result->RazaoSocial;
        $provader->name_fantasy_provider = $result->nomeFantasia ?? null;
        $provader->cnpj_provider = $result->cpfCnpj ?? $result->IdentificacaoPrestador->Cnpj;
        $provader->address_provider = $result->endereco ?? $result->Endereco->Endereco;
        $provader->number_address_provider = $result->numero ?? $result->Endereco->Numero;
        $provader->complement_address_provider = $result->complemento ?? $result->Endereco->Complemento;
        $provader->neighborhood_provider = $result->bairro ?? $result->Endereco->Bairro;
        $provader->city_provider = $result->codigoMunicipio ?? $result->Endereco->CodigoMunicipio;
        $provader->uf_provider = $result->uf ?? $result->Endereco->Uf;
        $provader->cep_provider = $result->cep ?? $result->Endereco->Cep;
        $provader->email_provider = $result->email ?? '';
        $provader->telephone_provider = $result->telefone ?? '';
        $mdlNFse->provider()->save($provader);
    }

    private function updateTaker (int $id, object $mdlNFse, object $result)
    {
        $taker = Taker::find($id);
        $result = $result->tomadorServico ?? $this->dataNfse->TomadorServico;

        $taker->cpf_cnpj_taker = $result->cpfCnpj ?? $result->IdentificacaoTomador->CpfCnpj->Cpf ?? $result->IdentificacaoTomador->CpfCnpj->Cnpj;
        $taker->recommendation_cpf_cnpj = ((\strlen(trim($result->cpfCnpj ?? $result->IdentificacaoTomador->CpfCnpj->Cpf ?? $result->IdentificacaoTomador->CpfCnpj->Cnpj)) == 11) ? 1 : 2);
        $taker->inscription_municipal_taker = $result->inscricaoMunicipal ?? null;
        $taker->reason_social_taker = $result->razaoSocial ?? $result->RazaoSocial ;
        $taker->address_taker = $result->endereco ?? $result->Endereco->Endereco ;
        $taker->number_address_taker = substr($result->numero ?? $result->Endereco->Numero, 0, 10);
        $taker->complement_address_taker = $result->complemento ?? $result->Endereco->Complemento;
        $taker->neighborhood_taker = $result->bairro ?? $result->Endereco->Bairro;
        $taker->cep_taker = $result->cep ?? $result->Endereco->Cep;

        $mdlNFse->taker()->save($taker);
    }

    private function checkLots (array $data) : bool
    {
        $notes = $this->queryUnsynchronizedNotes();
        $this->seeNotes($notes);

        return true;
    }

    private function queryUnsynchronizedNotes () : array
    {
        return DB::select('
                SELECT
                    N.id,
                    N.id_order,
                    L.protocol
                FROM
                    invoice.nfse N
                INNER JOIN
                    invoice.lot L ON N.id_lot = L.id
                WHERE
                    N.number_nfse IS NULL AND L.protocol is not null
                ORDER BY
                    N.id LIMIT 1');
    }

    private function seeNotes (array $notes)
    {
        foreach ($notes as $note) {
            $result = (object) $this->nfse->consultBatch($note->protocol);
            if (isset($result->xml)) {
                $nfse = (object) array_pop($result->nfs);
                $document = ((object) $nfse->prestadorServico)->cpfCnpj;
                //save XML
                $this->saveXML($result->xml, $nfse->numero, $document);
                //Data Note
                $this->updateNFse($note->id, $nfse);
                //SET LOG ORDER
                (new OrderRepo())->saveOrderLog(["id_order" =>  $note->id_order]);
            } else {
                $this->sendEmailError($note, $result);
            }
        }
    }

    public function saveXML ($xml, $numberNote, $cpnjProvider)
    {
        $environment = (env('APP_ENV') == 'production') ? 'producao' : 'homologacao';
        $file = Storage::disk('DIR_ORD')->put('nota_fiscal/storage/' . $environment . '/' . $numberNote . '-' . $cpnjProvider . '.xml', $xml);

        if (!$file) {
            return ['success' => false, 'message' => 'Falha ao salvar o arquivo XML'];
        }
    }

    private function sendEmailError(object $note, object $scalar): bool
    {
        //get get document
        $nfse = $this->getDataNFse(false, null, null, null, $note->id_pedido);

        $mdlEmail = new Email();
        $mdlEmail->template = 'emails.errorNFse';
        $mdlEmail->title = '**ATENÇÃO URGENTE (FALHA AO GERAR NFSE Nº PEDIDO ' . $nfse->id_pedido . ')**';
        // $mdlEmail->systemName = System::getName();
        $mdlEmail->parameters =
            [
            'idPedido' => $nfse->id_pedido,
            'nome' => $nfse->razao_social_tomador,
            'nomePrestador' => $nfse->razao_social_prestador,
            'cpfPrestador' => Util::mask((string) $nfse->cnpj_prestador, '##.###.###/####-##'),
            'descricao' => 'Falha ao gerar NFse motivo: ' . $scalar->scalar,
        ];
        $mdlEmail->emails =
            [
            [
                'name' => 'Equipe de Desenvolvimento',
                'email' => 'desenvolvimento@linkcertificacao.com.br',
            ],
        ];

        (new EmailHelper())->sendEmail($mdlEmail);

        return true;
    }

    public function getDataNFse (bool $all = true, string $order = null, $numberNFse = null, $cnpjProvider = null) : object
    {
        $builder = mdlNFse::query()
            ->from('invoice.nfse as N')
            ->select(
                'N.*',
                'L.*',
                'CC.*',
                'I.*',
                'P.*',
                'S.*',
                'T.*',
                'M.name AS nome_cidade_prestador',
                'MU.name AS nome_cidade_tomador',
                'SP.description AS descricao_tributacao_municipal',
                'SV.description AS descricao_lista_servico',
                'MUN.name AS nome_municipio_gerador',
                'PJ.tax_substitution'
            )
        //lote
            ->join('invoice.lot as L', function ($join) {
                $join->on('N.id_lot', '=', 'L.id');
            })
        //nfse_construcao_civil
            ->join('invoice.nfse_construction_civil as CC', function ($join) {
                $join->on('CC.id_nfse', '=', 'N.id');
            })
        //nfse_intermediario
            ->join('invoice.nfse_intermediary as I', function ($join) {
                $join->on('I.id_nfse', '=', 'N.id');
            })
        //nfse_prestador
            ->join('invoice.nfse_provider as P', function ($join) {
                $join->on('P.id_nfse', '=', 'N.id');
            })
        //nfse_servico
            ->join('invoice.nfse_service as S', function ($join) {
                $join->on('S.id_nfse', '=', 'N.id');
            })
        //nfse_tomador
            ->join('invoice.nfse_taker as T', function ($join) {
                $join->on('T.id_nfse', '=', 'N.id');
            })
        //Estados_prestador
            ->join('address.city as M', function ($join) {
                $join->on('M.id', '=', 'P.city_provider');
            })
        //Estados_tomador
            ->join('address.city as MU', function ($join) {
                $join->on('MU.id', '=', 'T.city_taker');
            })
        //Estados_Codigo_municipio_gerador
            ->join('address.city as MUN', function ($join) {
                $join->on('MUN.id', '=', 'S.city_installment_service');
            })
        //Servico_prefeitura
            ->join('invoice.service_prefecture as SP', function ($join) {
                $join->on('S.code_taxation_city', '=', 'SP.code');
            })
        //servico
            ->join('invoice.service as SV', function ($join) {
                $join->on('S.item_list_service', '=', 'SV.code');
            })
        //servico
            ->join('public.order as PE', function ($join) {
                $join->on('PE.id', '=', 'N.id_order');
            })
            ->leftJoin('contact.juridical_person as PJ', 'T.cpf_cnpj_taker', 'PJ.cnpj');
        if ($order) {
            $builder->where('N.id_order', '=', $order);
        }
        if ($numberNFse) {
            $builder->where('N.number_nfse', '=', $numberNFse);
        }
        if ($cnpjProvider) {
            $builder->where('P.cnpj_provider', '=', $cnpjProvider);
        }
        if ($all) {
            return $builder->get();
        }

        return $builder->first();
    }

    public function saveLogNFse (array $data)
    {
        $log = new Log();
        $log->id_nfse = $data['id_nfse'];
        $log->id_user = Auth::id() ?? SystemConstants::USER_SYSTEM;
        $log->date = Carbon::now();
        $log->description = $data['other'] ?? null;
        $log->save();
    }

    public function actionNfse (array $data)
    {
        DB::beginTransaction();
        try{
            switch ($data['action'])
            {
                case SystemConstants::CANCEL:
                    $response = $this->nfseCancellation($data);
                    DB::commit();
                    return $response;
                    break;
                case SystemConstants::DOWLOAD_XML:
                    $response = $this->downloadXmlNFse($data);
                    DB::commit();
                    return $response;
                    break;
                case SystemConstants::DOWLOAD_NFSE:
                    $data['type'] = 'D';
                    $response = $this->viewNfse($data);
                    DB::commit();
                    return $response;
                    break;
                case SystemConstants::VIEW_NFSE:
                    $data['type'] = 'I';
                    $response = $this->viewNfse($data);
                    DB::commit();
                    return $response;
                    break;
                case SystemConstants::MODAL_SEND_EMAIL:
                    $response = $this->modalSendEmail($data);
                    DB::commit();
                    return $response;
                    break;
                case SystemConstants::SEND_EMAIL:
                    $response = $this->sendEmail($data);
                    DB::commit();
                    return json_encode($response);
                    break;
                default:
                    throw new Exception("Falha ao realizar a operação desejada.", 400);
                break;
            }
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            return ['success' => false, 'message' => 'Não foi possível realizar a operação. MOTIVO: ' .$e->getMessage()];
        }
    }

    private function nfseCancellation (array $data) : array
    {
        $invoice = $this->getDataNFse(false, null, Crypt::decrypt($data['number_nfse']), Crypt::decrypt($data['cnpj_provider']));
        $this->saveLogNFse(['id_nfse' => $invoice->id_nfse, 'description' => $data['other']]);
        $result = (object) $this->nfse->nfseCancellation($invoice);
        if (isset($result->xmlCancelamento)) {
            $cancellation = (object) array_pop($result->cancelamento);
            $this->saveXML($result->xmlCancelamento, $cancellation->numeroNFs, $cancellation->cnpjPrestador);

            $mdlNFse = (new mdlNFse())->find($invoice->id_nfse);
            $mdlNFse->date_cancellation = Carbon::now();
            $mdlNFse->code_cancellation = $cancellation->codigoCancelamento ?? 2;
            $mdlNFse->save();

            //SET LOG NFSE
            $this->saveLogNFse(['id_nfse' => $invoice->id_nfse, 'description' => $data['other']]);

            //SET LOG ORDER
            (new OrderRepo())->saveOrderLog(['id_user' => (Auth::id()) ?? SystemConstants::USER_SYSTEM, 'id_order' => $invoice->id_order, 'description' => 'Realizou o cancelamento da NFS-e | MOTIVO : ' . $data['other']]);

            //UPDATE STATUS ORDER
            $order = (new Order())->where('id', $invoice->id_order)->first();
            $order->status = 6;
            $order->save();

            return $result = [
                'success' => true,
                'message' => 'Nota cancelada com sucesso!',
            ];
        } else {
            return $result = [
                'success' => false,
                'message' => $result->scalar,
            ];
        }
    }

    private function downloadXmlNFse (array $data)
    {
        $environment = (env('APP_ENV') == 'production') ? 'producao' : 'homologacao';
        $dir = 'nota_fiscal/storage/' . $environment . '/' . Crypt::decrypt($data['number_nfse']) . '-' . Crypt::decrypt($data['cnpj_provider']);

        $extension = '.xml';
        if (Storage::disk('DIR_ORD')->exists($dir . $extension)) {
            $file_url = $dir . $extension;
            $file_name = basename($file_url);

            $response = [
                'Content-Type' => Storage::disk('DIR_ORD')->mimetype($file_url),
                'Content-Length' => Storage::disk('DIR_ORD')->size($file_url),
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$file_name}",
                'Content-Transfer-Encoding' => 'binary',
            ];

            return Response::make(Storage::disk('DIR_ORD')->get($file_url), 200, $response);
        }

        return ['success' => false, 'message' => 'XML não encontrado', 'file' => null];
    }

    private function viewNfse (array $data) : array
    {
        $nfse = $this->getDataNFse(false, null, Crypt::decrypt($data['number_nfse']), Crypt::decrypt($data['cnpj_provider']));
        return ['success' => true, 'message' => 'View criada com sucesso!', 'view' => $this->nfse->printNFse($nfse, $data['type'])];
    }

    private function modalSendEmail (array $data) : array
    {
        return [
            'success' => true,
            'message' => 'View recuperada com sucesso!',
            'view' => view('order.modals.modalSendEmail', $data)->render(),
            'modal' => 'modalSendEmail',
        ];
    }

    private function sendEmail (array $data) : array
    {
       //get E-mails
       $mails = explode(';', trim($data['other']));
       $emailsArrays = [];

       foreach ($mails as $key => $mail) {
           $emailsArrays += [
               'name' => '',
               'email' => $mail,
           ];
       }

       //get get document
       $nfse = $this->getDataNFse(false, null, Crypt::decrypt($data['number_nfse']), Crypt::decrypt($data['cnpj_provider']));
       //get pdf
       $pdf = $this->nfse->printNFse($nfse, 'S');
       //get xml
       $xml = $this->getXml($nfse->number_nfse, $nfse->cnpj_provider);

       $mdlEmail = new Email();
       $mdlEmail->template = 'emails.nfse';
       $mdlEmail->title = 'NFS-e (Nota Fiscal de Serviço Eletrônica)';
       $mdlEmail->systemName = 'Market Research';
       $mdlEmail->parameters = [
           'nome' => $nfse->reason_social_taker,
           'nomePrestador' => $nfse->reason_social_provider,
           'cpfPrestador' => Util::mask((string) $nfse->cnpj_provider, '##.###.###/####-##'),
           'numeroNFse' => $nfse->number_nfse,
           'descricao' => 'Você acaba de receber uma nova NFS-e (Nota Fiscal de Serviço Eletrônica).
                           Para visualizar a NFS-e click no documento em anexo.',
       ];
       $mdlEmail->emails = [$emailsArrays];
       //opcional
       $mdlEmail->files = [
           [
               'file' => $pdf,
               'name' => 'NFse.pdf',
               'type' => 'application/pdf',
           ],
           [
               'file' => $xml,
               'name' => 'XML.xml',
               'type' => 'application/xml',
           ],
       ];
       (new EmailHelper())->sendEmail($mdlEmail);

        return ['success' => true, 'message' => 'Nota Fiscal de Serviço Eletrônica Enviada com Sucesso!'];
    }

    private function getXml(string $numberNFse, string $cpnjProvider)
    {
        $environment = (env('APP_ENV') == 'production') ? 'producao' : 'homologacao';
        $file = Storage::disk('DIR_ORD')->get('nota_fiscal/storage/' . $environment . '/' . $numberNFse . '-' . $cpnjProvider . '.xml');

        if (!$file) {
            return ['success' => false, 'message' => 'Falha ao recuperar o arquivo XML'];
        }

        return $file;
    }
}
