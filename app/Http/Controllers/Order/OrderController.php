<?php namespace App\Http\Controllers\Order;

use App\Repositories\Order\OrderRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\NFse\NFseRepo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    protected $request;
    protected $orderRepo;
    protected $nfseRepo;

    public function __construct(Request $request, OrderRepo $orderRepo, NFseRepo $nfseRepo)
    {
        $this->request = $request;
        $this->orderRepo = $orderRepo;
        $this->nfseRepo = $nfseRepo;
    }

    public function index ()
    {
        return $this->orderRepo->onGet(['page' => $this->request->page], 'index');
    }

    public function download ()
    {
        try {
            $class          = 'App\Exports\\' . Arr::get($this->request->toArray(),'classInput');
            $downloadReport = new $class($this->request->toArray());

            return json_encode($downloadReport->buildReport());

        } catch (QueryException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Erro ao realizar consulta, tente novamente mais tarde.',
            ], 400);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function makeOrder ()
    {
        return json_encode($this->orderRepo->onPost(json_decode($this->request->data), 'makeOrder'));
    }

    public function manualLowOrder ()
    {
        return json_encode($this->orderRepo->onPost(json_decode($this->request->data), 'manualLowOrder'));
    }

    public function bankslip ()
    {
        return redirect($this->orderRepo->onGet(json_decode($this->request->data), 'getBankSlipUrl'));
    }

    public function orderDetail (string $id)
    {
        return $this->orderRepo->onGet(['id_order' => $id], 'orderDetail');
    }

    public function actionNfse (string $numberNfse, string $cnpjProvider, int $action, string $emailTaker = null)
    {
        return $this->nfseRepo->actionNfse(['action' => $action, 'number_nfse' => $numberNfse, 'cnpj_provider' => $cnpjProvider, 'email_taker' => $emailTaker, 'other' => $this->request->other ?? '']);
    }

    public function downloadFile (string $id)
    {
        return $this->orderRepo->onGet(['id_order' => $id], 'downloadFile');;
    }

    public function loadGrid ()
    {
        return $this->orderRepo->onGet($this->request->all(), 'loadGrid');
    }
}
