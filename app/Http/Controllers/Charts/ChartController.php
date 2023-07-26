<?php

namespace App\Http\Controllers\Charts;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Repositories\Address\AddressRepo;
use App\Repositories\Charts\ChartsRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use MongoDB\Driver\Session;

class ChartController extends Controller
{
    private $request;
    private $chartsRepo;

    public function __construct(Request $request, ChartsRepo $chartsRepo)
    {
        $this->request = $request;
        $this->chartsRepo = $chartsRepo;
    }

    public function index()
    {
        $addressRepo = app()->make(AddressRepo::class);
        $cnae = Arr::prepend($addressRepo->getCnaes(), 'Todos', '');
        $states = Arr::pluck(Util::getBrazilianStates(), 'nomeEstado', 'sigla');
        $states = Arr::prepend($states, 'Todos', '');
        $legalNature =  Arr::prepend($addressRepo->getLegalNatures(), 'Todos', '');

        return view('charts.index')
            ->with(['states' => $states])
            ->with(['cnae' => $cnae])
            ->with(['legalNature' => $legalNature])
            ->with(['title' => 'Gráficos']);
    }

    public function queryLegalNature()
    {
        return $this->chartsRepo->queryLegalNature();

    }

    public function queryEconomyActivity()
    {
        return $this->chartsRepo->queryEconomyActivity();

    }
}
