<?php namespace App\Repositories\Order;

use App\Helpers\Constants\SystemConstants;
use App\Helpers\Util;
use App\Models\NFse\NFse;
use App\Models\Order\Order;
use App\Models\Order\OrderLog;
use App\Models\Order\OrderPortion;
use App\Repositories\Address\AddressRepo;
use App\Repositories\AppRepo;
use App\Repositories\NFse\NFseRepo;
use App\Repositories\Person\PersonRepo;
use App\Service\Order\OrderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class OrderRepo extends AppRepo
{
    private $service;
    private $order;
    private $nfseRepo;

    public function __construct ()
    {
        $this->service = new OrderService;
        $this->order = new Order();
        $this->nfseRepo = new NFseRepo();
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
            dd($e);
            return ['success' => false, 'message' => "Não foi possível realizar a operação. Motivo: " . $e->getMessage()];
        }
    }

    private function makeOrder (array $data) : array
    {
        return $this->service->makeOrder($data);
    }

    public function saveOrder (object $user, object $salesman = null, object $data) : object
    {
        $order = new Order();
        $order->id_user = Auth::id();
        $order->lines = $data->order_lines;
        $order->status = SystemConstants::ID_STATUS_TRANSACTION_PENDING;
        $order->id_person = Session::get('usuario')->id_person;
        $order->parcels = 1;
        $order->emission_note_id = $user->id_person;
        $order->id_salesman = $salesman->id ?? null;
        $order->payment_option = $data->type_payment;
        $order->title_value = $data->order_value;
        $order->charge_value = $data->order_value;
        $order->name_file = $data->name_file;
        $order->save();

        $this->saveOrderInstallment($order);

        return $order;
    }

    private function saveOrderInstallment (object $order)
    {
        for ($i = 1; $i <= $order->parcels; ++$i) {
            $data = new \DateTime();
            $portion = new OrderPortion();
            $portion->id_order = $order->id;
            $portion->portion = $i;
            $portion->status = 1;
            $portion->date_emission = $data->format('Y-m-d');
            $portion->date_competence = $data->format('Y-m-d');
            $data->add(new \DateInterval('P5D'));
            $portion->date_due = $data->format('Y-m-d');
            //Alterar depois de finalizado
            $portion->value_title = $order->charge_value;
            $portion->value_charge = $order->charge_value;
            if ($order->id_forma_pagamento == SystemConstants::CREDIT_CARD) {
                $portion->tid = $order->tid;
            }
            $portion->save();
            $portion->installments();
        }
    }

    public function automaticLowOrder (array $transaction)
    {
        $transaction = Util::convertObjectToArray(Arr::get($transaction, 'transacao') ?? $transaction);
        $order = $this->order::find(Arr::get($transaction, 'referencia'));
        $return = false;
        switch (Arr::get($transaction, 'status.id')) {
            case SystemConstants::ID_STATUS_TRANSACTION_FINISHED:
                //Make sync with bank krypton
                $this->service->syncBankingKrypton($transaction);
                break;
            case SystemConstants::ID_STATUS_TRANSACTION_PAID:
            case SystemConstants::ID_STATUS_TRANSACTION_PARTIAL_PAID:
            case SystemConstants::ID_STATUS_TRANSACTION_AUTHORIZED:
            case SystemConstants::ID_STATUS_TRANSACTION_APPROVED:
                //Make low order
                $this->lowOrder([
                    'id_pedido' => $order->id,
                    'tipo_baixa' => SystemConstants::NORMAL,
                    'valor_pagamento' => $order->valor_cobranca,
                    'status' => SystemConstants::ID_STATUS_TRANSACTION_PAID,
                    'automatic' => true,
                ]);
                $return = true;
            break;
            default:
                $return = false;
            break;
        }
        return $return;
    }

    private function manualLowOrder (array $data) : array
    {
        $order = $this->order::find($data[1]->value);

        //Make low order
        $this->lowOrder([
            'id_order' => $order->id,
            'low_type' => $data[2]->value,
            'value_payment' => $data[4]->value,
            'status' => SystemConstants::ID_STATUS_TRANSACTION_PAID,
            'automatic' => false,
        ]);

        //Case be in production make sync bank krypton
        if(app()->environment('production')){
            $this->service->arrayBank($data);
        }

        return ['success' => true, 'message' => 'Pedido baixado com sucesso!'];
    }

    private function lowOrder (array $orderData) : array
    {
        $order = $this->order::find(Arr::get($orderData, 'id_order'));
        $order->low_type = Arr::get($orderData, 'low_type');
        $order->low_date = Carbon::now()->format('Y-m-d H:i:s');
        $order->payment_value = Arr::get($orderData, 'value_payment');
        $order->status = Arr::get($orderData, 'status');
        $order->save();

        if(!NFse::query()->where('id_order', Arr::get($orderData, 'id_order'))->first()){
            $this->nfseRepo->issue(['id_order' => Arr::get($orderData, 'id_order'), 'automatic' => Arr::get($orderData, 'automatic')]);
        }

        return ['success' => true, 'message' => 'Baixa manual realizada com sucesso!'];
    }

    public function getOrder (int $id)
    {
        return $this->order->from('public.order as O')
            ->select(
                'O.*',
                // 'PRE.nome as plano',
                'PP.date_emission as data_emissao',
                'PP.date_competence as data_competencia',
                'PP.date_due as data_vencimento',
                'PP.date_payment as data_pagamento',
                'CLIE.street as cliente_logradouro',
                'CLIE.number as cliente_numero',
                'CLIE.complement as cliente_complemento',
                'CLIE.neighborhood as cliente_bairro',
                'CLIE.cep as cliente_cep',
                'CLIE.city_code as cliente_codigo_municipio',
                'CLIE.uf as cliente_uf',
                'CLIE.country_code as cliente_codigo_pais',
                'CLIM.name as cliente_municipio',
                'CLI.id_type as cliente_tipo',
                'CLI.name as cliente_nome',
                'CLI.email as cliente_email',
                'CLI.pis',
                'CLIPF.cpf as cliente_cpf',
                DB::raw("TO_CHAR(\"CLIPF\".birth_date, 'DD/MM/YYYY') as cliente_data_nascimento"),
                'CLIPF.rg as cliente_rg',
                'CLIPF.issuing_agency as cliente_orgao_expedidor',
                'CLIPF.date_issui as cliente_data_expedicao',
                'CLIPF.cei as cliente_cei',
                'CLIPJ.cnpj as cliente_cnpj',
                'CLIPJ.state_registration as cliente_inscricao_estadual',
                'CLIPJ.municipal_registration as cliente_inscricao_municipal',
                'CLIPJ.fantasy_name as cliente_nome_fantasia',
                'CLIPJ.tax_substitution as cliente_substituicao_tributaria',
                'CLIPJ.website  as cliente_website',
                'CLIPJ.name_contact as cliente_nome_contato',
                'CLIPJ.telephone_contact as cliente_telefone_contato',
                'CLIPJ.email_contact as cliente_email_contato',
                'EMIE.street as emissao_nota_logradouro',
                'EMIE.number as emissao_nota_numero',
                'EMIE.complement as emissao_nota_complemento',
                'EMIE.neighborhood as emissao_nota_bairro',
                'EMIE.cep as emissao_nota_cep',
                'EMIE.city_code as emissao_nota_codigo_municipio',
                'EMIE.uf as emissao_nota_uf',
                'EMIE.country_code as emissao_nota_codigo_pais',
                'EMIM.name as emissao_nota_municipio',
                'EMI.id_type as emissao_nota_tipo',
                'EMI.name as emissao_nota_nome',
                'EMI.email as emissao_nota_email',
                'EMI.pis',
                'EMIPF.cpf as emissao_nota_cpf',
                DB::raw("TO_CHAR(\"EMIPF\".birth_date, 'DD/MM/YYYY') as emissao_nota_data_nascimento"),
                'EMIPF.rg as emissao_nota_rg',
                'EMIPF.issuing_agency as emissao_nota_orgao_expedidor',
                'EMIPF.date_issui as emissao_nota_data_expedicao',
                'EMIPF.cei as emissao_nota_cei',
                'EMIPJ.cnpj as emissao_nota_cnpj',
                'EMIPJ.state_registration as emissao_nota_inscricao_estadual',
                'EMIPJ.municipal_registration as emissao_nota_inscricao_municipal',
                'EMIPJ.fantasy_name as emissao_nota_nome_fantasia',
                'EMIPJ.tax_substitution as emissao_nota_substituicao_tributaria',
                'EMIPJ.website  as emissao_nota_website',
                'EMIPJ.name_contact as emissao_nota_nome_contato',
                'EMIPJ.telephone_contact as emissao_nota_telefone_contato',
                'EMIPJ.email_contact as emissao_nota_email_contato'
            )
        //pedido parcela
            ->join('public.order_portion as PP', function ($join) {
                $join->on('O.id', '=', 'PP.id_order');
                $join->whereRaw('"PP"."portion" = 1');
            })
        //cliente
            ->join('contact.person as CLI', 'O.id_person', '=', 'CLI.id')
            ->leftJoin('contact.physical_person as CLIPF', 'CLI.id', '=', 'CLIPF.id')
            ->leftJoin('contact.juridical_person as CLIPJ', 'CLI.id', '=', 'CLIPJ.id')
            ->leftJoin('address.address as CLIE', 'CLI.id', '=', 'CLIE.id_person')
            ->leftJoin('address.city as CLIM', DB::raw('"CLIE".city_code::integer'), '=', 'CLIM.id')

        //emissao_nota
            ->join('contact.person as EMI', 'O.emission_note_id', '=', 'EMI.id')
            ->leftJoin('contact.physical_person as EMIPF', 'EMI.id', '=', 'EMIPF.id')
            ->leftJoin('contact.juridical_person as EMIPJ', 'EMI.id', '=', 'EMIPJ.id')
            ->leftJoin('address.address as EMIE', 'EMI.id', '=', 'EMIE.id_person')
            ->leftJoin('address.city as EMIM', DB::raw('"EMIE".city_code::integer'), '=', 'EMIM.id')
            ->where('O.id', '=', $id)
        //->toSql();
            ->first() ?? $this->order::query()
            ->from('public.order as O')
            ->select(
                'O.*',
                // 'PRE.nome as plano',
                'PP.date_emission as data_emissao',
                'PP.date_competence as data_competencia',
                'PP.date_due as data_vencimento',
                'PP.date_payment as data_pagamento',
                'CLIE.street as cliente_logradouro',
                'CLIE.number as cliente_numero',
                'CLIE.complement as cliente_complemento',
                'CLIE.neighborhood as cliente_bairro',
                'CLIE.cep as cliente_cep',
                'CLIE.city_code as cliente_codigo_municipio',
                'CLIE.uf as cliente_uf',
                'CLIE.country_code as cliente_codigo_pais',
                'CLIM.name as cliente_municipio',
                'CLI.id_type as cliente_tipo',
                'CLI.name as cliente_nome',
                'CLI.email as cliente_email',
                'CLI.pis',
                'CLIPF.cpf as cliente_cpf',
                DB::raw("TO_CHAR(\"CLIPF\".birth_date, 'DD/MM/YYYY') as cliente_data_nascimento"),
                'CLIPF.rg as cliente_rg',
                'CLIPF.issuing_agency as cliente_orgao_expedidor',
                'CLIPF.date_issui as cliente_data_expedicao',
                'CLIPF.cei as cliente_cei',
                'CLIPJ.cnpj as cliente_cnpj',
                'CLIPJ.state_registration as cliente_inscricao_estadual',
                'CLIPJ.municipal_registration as cliente_inscricao_municipal',
                'CLIPJ.fantasy_name as cliente_nome_fantasia',
                'CLIPJ.tax_substitution as cliente_substituicao_tributaria',
                'CLIPJ.website  as cliente_website',
                'CLIPJ.name_contact as cliente_nome_contato',
                'CLIPJ.telephone_contact as cliente_telefone_contato',
                'CLIPJ.email_contact as cliente_email_contato',
                'EMIE.street as emissao_nota_logradouro',
                'EMIE.number as emissao_nota_numero',
                'EMIE.complement as emissao_nota_complemento',
                'EMIE.neighborhood as emissao_nota_bairro',
                'EMIE.cep as emissao_nota_cep',
                'EMIE.city_code as emissao_nota_codigo_municipio',
                'EMIE.uf as emissao_nota_uf',
                'EMIE.country_code as emissao_nota_codigo_pais',
                'EMIM.name as emissao_nota_municipio',
                'EMI.id_type as emissao_nota_tipo',
                'EMI.name as emissao_nota_nome',
                'EMI.email as emissao_nota_email',
                'EMI.pis',
                'EMIPF.cpf as emissao_nota_cpf',
                DB::raw("TO_CHAR(\"EMIPF\".birth_date, 'DD/MM/YYYY') as emissao_nota_data_nascimento"),
                'EMIPF.rg as emissao_nota_rg',
                'EMIPF.issuing_agency as emissao_nota_orgao_expedidor',
                'EMIPF.date_issui as emissao_nota_data_expedicao',
                'EMIPF.cei as emissao_nota_cei',
                'EMIPJ.cnpj as emissao_nota_cnpj',
                'EMIPJ.state_registration as emissao_nota_inscricao_estadual',
                'EMIPJ.municipal_registration as emissao_nota_inscricao_municipal',
                'EMIPJ.fantasy_name as emissao_nota_nome_fantasia',
                'EMIPJ.tax_substitution as emissao_nota_substituicao_tributaria',
                'EMIPJ.website  as emissao_nota_website',
                'EMIPJ.name_contact as emissao_nota_nome_contato',
                'EMIPJ.telephone_contact as emissao_nota_telefone_contato',
                'EMIPJ.email_contact as emissao_nota_email_contato'
            )
        //pedido parcela
            ->join('public.order_portion as PP', function ($join) {
                $join->on('O.id', '=', 'PP.id_order');
                $join->whereRaw('"PP"."portion" = 1');
            })
        //cliente
            ->join('contact.person as CLI', 'O.id_person', '=', 'CLI.id')
            ->leftJoin('contact.physical_person as CLIPF', 'CLI.id', '=', 'CLIPF.id')
            ->leftJoin('contact.juridical_person as CLIPJ', 'CLI.id', '=', 'CLIPJ.id')
            ->leftJoin('address.address as CLIE', 'CLI.id', '=', 'CLIE.id_person')
            ->leftJoin('address.city as CLIM', DB::raw('"CLIE".city_code::integer'), '=', 'CLIM.id')

        //emissao_nota
            ->join('contact.person as EMI', 'O.emission_note_id', '=', 'EMI.id')
            ->leftJoin('contact.physical_person as EMIPF', 'EMI.id', '=', 'EMIPF.id')
            ->leftJoin('contact.juridical_person as EMIPJ', 'EMI.id', '=', 'EMIPJ.id')
            ->leftJoin('address.address as EMIE', 'EMI.id', '=', 'EMIE.id_person')
            ->leftJoin('address.city as EMIM', DB::raw('"EMIE".city_code::integer'), '=', 'EMIM.id')
            ->where('O.id', '=', $id)
            ->first();
    }

    public function saveOrderLog (array $data)
    {
        $orderLog = new OrderLog();
        $orderLog->id_user =  Arr::get($data, 'id_user', Auth::id() ?? SystemConstants::USER_SYSTEM);
        $orderLog->id_order = Arr::get($data, 'id_order');
        $orderLog->description = Arr::get($data, 'description') ?? 'Realizou a emissão do lote da NFS-e manualmente.';
        $orderLog->save();
    }

    private function getBankSlipUrl (array $data) : string
    {
        $url = $this->service->getBankSlipUrl(Hashids::decode($data['reference'])[0]);
        if (empty($url)) {
            die('Boleto indisponível para download');
        }
        return $url;
    }

    private function index (array $data) : object
    {
        $addressRepo = app()->make(AddressRepo::class);
        $cnae = Arr::prepend($addressRepo->getCnaes(), 'Todos', '');
        $states = Arr::pluck(Util::getBrazilianStates(), 'nomeEstado', 'sigla');
        $states = Arr::prepend($states, 'Todos', '');
        $orders = $this->getUserOrder($data)->get();
        $legalNature =  Arr::prepend($addressRepo->getLegalNatures(), 'Todos', '');

        return view('order.index')
        ->with(['orders' => $orders])
        ->with(['states' => $states])
        ->with(['cnae' => $cnae])
        ->with(['legalNature' => $legalNature]);
    }


    private function getUserOrder (array $data) : object
    {
        return $this->order::query()
        ->select('O.*', 'P.name as name_representative','PP.cpf', 'JP.cnpj')
        ->from('public.order as O')
        ->join('contact.person as P', 'O.emission_note_id', 'P.id')
        ->leftJoin('contact.physical_person as PP', 'P.id', 'PP.id')
        ->leftJoin('contact.juridical_person as JP', 'P.id', 'JP.id')
        ->where('O.id_user', Auth::id());
    }

    private function orderDetail (array $data) : object
    {
        $personRepo = new PersonRepo();
        $order = $this->order::find($data['id_order']);
        $client = $personRepo->onGet(["id_person" => $order->id_person], 'getPerson');
        $emission = $personRepo->onGet(["id_person" => $order->emission_note_id], 'getPerson');
        $invoices = $this->nfseRepo->getDataNFse(true, $order->id);
        $history = $this->getHistoryOrder($data);

        return view('order.orderDetail')
        ->with(['status' => $this->getStatusPayment($order->status)])
        ->with(['order' => $order])
        ->with(['client' => $client])
        ->with(['history' => $history])
        ->with(['emission' => $emission])
        ->with(['invoices' => $invoices]);
    }

    private function downloadFile (array $data)
    {
        $id = $data['id_order'];
        $file_url = $this->order::find($id)->name_file;

        $response = [
            'Content-Type' => Storage::disk('DIR_ORD')->mimetype('order/'.Session::get('usuario')->id_person.'/'.$file_url),
            'Content-Length' => Storage::disk('DIR_ORD')->size('order/'.Session::get('usuario')->id_person.'/'.$file_url),
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$file_url}",
            'Content-Transfer-Encoding' => 'binary',
        ];

        return Response::make(Storage::disk('DIR_ORD')->get('order/'.Session::get('usuario')->id_person.'/'.$file_url), 200, $response);
    }

    private function loadGrid (array $data) : array
    {
        $tables = $this->getUserOrder($data);

        $columns = array(
            0 => 'id',
            1 => 'name_representative',
            2 => 'cpf_cnpj',
            3 => 'lines',
            4 => 'created_at',
            5 => 'updated_at',
            6 => 'value_charge',
            7 => 'status',
        );

        $tables = $this->aplyFilter($data, $tables);

        $this->applyOrdering($columns, $data['order'], $tables);

        $iTotalRecords = $tables->count();
        $tables->offset(Arr::get($data, 'start'))->limit(Arr::get($data, 'length'));

        $dataTables = new Collection();
        foreach ($tables->get() as $t) {
            $dataTables->add([
                'id' => $t->id,
                'name_representative' => $t->name_representative,
                'cpf_cnpj' => $t->cpf ?? $t->cnpj,
                'lines' => $t->lines,
                'created_at' => $t->created_at,
                'updated_at' => $t->updated_at,
                'value_charge' => $t->charge_value,
                'status' => $t->status ,
            ]);
        }

        return array(
            "draw" => intval(Arr::get($data, 'draw')),
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
            "data" => $dataTables->toArray(),
        );
    }

    private function aplyFilter (array $data, object $collection) : object
    {
        if(Arr::get($data, 'id', 0) != 0){
            $collection = $collection->where('O.id', $data['id']);
        }

        if(Arr::get($data, 'client', 0) != 0){
            $collection =  $collection->where('P.name', 'like', "%".$data['client']."%");
        }

        if(Arr::get($data, 'cpf_cnpj', 0) != 0){
            if(strlen(Util::removerMaskDocument($data['cpf_cnpj'])) == 11){
                $collection =  $collection->where('PP.cpf', Util::removerMaskDocument($data['cpf_cnpj']));
            }else{
                $collection =  $collection->where('JP.cnpj', Util::removerMaskDocument($data['cpf_cnpj']));
            }
        }

        if(Arr::get($data, 'period_begin', 0) != 0){
            $collection =  $collection->where('O.created_at', '>=', $data['period_begin']);
        }

        if(Arr::get($data, 'period_end', 0) != 0){
            $collection =  $collection->where('O.created_at', '<=', $data['period_end']);
        }

        return $collection;
    }

    private function getHistoryOrder (array $data) : array|object
    {
        return OrderLog::query()
        ->select('public.order_log.*', 'P.name')
        ->join('access_control.user as U', 'public.order_log.id_user', 'U.id')
        ->join('contact.person as P', 'U.id_person', 'P.id')
        ->where('public.order_log.id_order', $data['id_order'])->get();
    }

    private function getStatusPayment (int $status) : string
    {
        switch ($status) {
            case 1:
                return trans('request.status.pending');
                break;
            case 2:
                return trans('request.status.paid');
                break;
            case 3:
                return trans('request.status.partial_paid');
                break;
            case 4:
                return trans('request.status.reversed');
                break;
            case 5:
                return trans('request.status.partial_reversed');
                break;
            case 6:
                return trans('request.status.canceled');
                break;
        }
    }
}
