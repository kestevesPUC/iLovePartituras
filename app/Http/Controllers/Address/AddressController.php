<?php

namespace App\Http\Controllers\Address;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Repositories\Address\AddressRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;

class AddressController extends Controller
{
    protected $request;
    protected $addressRepo;

    public function __construct(Request $request, AddressRepo $addressRepo)
    {
        $this->request = $request;
        $this->addressRepo = $addressRepo;
    }

    /**
     * Recupera todas os estados do Brasil
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getStates()
    {
        $addressRepo = app()->make(AddressRepo::class);
        $states = $addressRepo->getStates();
        return Response::json([
            'success' => true,
            'message' => 'Estados recuperados com sucesso!',
            'cities' => $states
        ]);
    }

    /**
     * Recupera as cidades através de uma requisição informando a UF
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCities()
    {
        $addressRepo = app()->make(AddressRepo::class);
        $cities = $addressRepo->getCities($this->request->get('uf'));
        return Response::json([
            'success' => true,
            'message' => 'Cidades recuperadas com sucesso!',
            'cities' => $cities
        ]);
    }

    /**
     * Recupera os bairros atrabés de uma requisição informando o município/cidade
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getNeighborhood()
    {
        $addressRepo = app()->make(AddressRepo::class);
        $neighborhood = $addressRepo->getNeighborhood($this->request->get('city'));

        return Response::json([
            'success' => true,
            'message' => 'Bairros recuperadas com sucesso!',
            'neighborhood' => $neighborhood
        ]);
    }
}
