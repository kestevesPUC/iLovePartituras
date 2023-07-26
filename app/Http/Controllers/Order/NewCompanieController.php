<?php namespace App\Http\Controllers\Web\MarketingResearch\NewCompanie;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Repositories\MarketingReserach\NewCompanie\NewCompanieRepo;
use App\Repositories\Report\AddressRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use function app;
use function App\Http\Controllers\Web\NewCompanie\dd;
use function view;

class NewCompanieController extends Controller
{
    protected $request;
    protected $newCompanieRepo;

    public function __construct(Request $request, NewCompanieRepo $newCompanieRepo)
    {

        $this->request = $request;
        $this->newCompanieRepo = $newCompanieRepo;
    }

    public function download()
    {
        try {
            $class          = 'App\Exports\\' . $this->request->json()->get('classInput');
            $downloadReport = new $class($this->request->json()->all());
            return $downloadReport->buildReport();
        }
        catch (QueryException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Erro ao realizar consulta, tente novamente mais tarde.',
            ], 400);
        }
        catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    public function exportarXlsx()
    {
        $rfbRepo = app()->make(RfbRepo::class);
        $cnae = Arr::prepend($rfbRepo->getCnaes(), 'Todos', '');
        $states = Arr::pluck(Util::getBrazilianStates(), 'nomeEstado', 'sigla');
        $states = Arr::prepend($states, 'Todos', '');

        return view('MarketingResearch.newCompanie.exportarXlsx')
            ->with(['metaTags' => $this->metaTags])
            ->with(['cnae' => $cnae])
            ->with(['states' => $states]);
    }


}
