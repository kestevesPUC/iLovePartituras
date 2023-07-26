<?php

namespace App\Http\Controllers;

use App\Helpers\Constants\ContractConstants;
use App\Http\Controllers\DefaultController;
use App\Models\MarketingResearch\Order;
use App\Repositories\MarketingReserach\NewCompanie\MarketingResearchRepo;
use App\Service\Files\FilesService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use function view;

class MarketingResearchController extends Controller
{

    protected $request;
    protected $marketingResearchRepo;

    public function __construct(Request $request, MarketingResearchRepo $marketingResearchRepo)
    {
        $this->setModuleName('MarketingResearchRepo');
        $this->setCrumbTitle('MarketingResearchRepo');
        $this->setTitle('Pesquisa de Mercado');

        parent::__construct();

        $this->request = $request;
        $this->marketingResearchRepo = $marketingResearchRepo;
    }

    public function marketingResearchlist()
    {
        $this->setTitle('Pesquisa de Mercado');

        $pedidos = Order::query()
            ->select('*');

        if (!in_array(Session::get('usuario.id_grupo'), [ContractConstants::GROUP_ADMIN, ContractConstants::GROUP_COMMERCIAL_LEADER])) {
            $pedidos->where('id_usuario', Session::get('usuario.pessoa_id'));
        }
        $pedidos = $pedidos->get();


        return view('MarketingResearch.newCompanie.marketingResearchList')
            ->with(['metaTags' => $this->metaTags])
            ->with(['pedidos' => $pedidos]);
    }

    function loadGrid()
    {
        return $this->marketingResearchRepo->loadGrid($this->request->all());
    }

    function openFile($id)
    {
        try{
            $fileName = 'pedido_' . $id . '.xlsx';
            return (new FilesService())->getFile($fileName, $fileName, 'DIR_MARKET');
        } catch (QueryException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Erro ao realizar consulta, tente novamente mais tarde.',
            ], 400);
        }
        catch (\Exception $e) {
            return Response::json([
                'draw' => 10,
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    function generateTicket()
    {
        dd($this->request->all());
    }
    function sendValueTicket()
    {
        return Response::json(['success' => true]);
//        return view('MarketingResearch.newCompanie.modals.mdlTicket')
//            ->with('id', Arr::get($this->request->all(), 'id'))
//            ->with('status', Arr::get($this->request->all(), 'status'));
    }
}
