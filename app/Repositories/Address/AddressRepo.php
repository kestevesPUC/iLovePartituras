<?php

namespace App\Repositories\Address;

use App\Helpers\Util;
use App\Models\Address\Cities;
use App\Models\Address\Rfb\Cnae;
use App\Models\Address\Rfb\Company;
use App\Models\Address\Rfb\LegalNature;
use App\Repositories\AppRepo;
use Illuminate\Support\Arr;

class AddressRepo extends AppRepo
{
    private $cnaeMdl;
    private $citiesMdl;
    private $companyMdl;
    private $legalNatureMdl;

    public function __construct(Cnae $cnaeMdl, Cities $citiesMdl, Company $companyMdl, LegalNature $legalNatureMdl)
    {
        $this->cnaeMdl = $cnaeMdl;
        $this->citiesMdl = $citiesMdl;
        $this->companyMdl = $companyMdl;
        $this->legalNatureMdl = $legalNatureMdl;
    }

    public function getStates()
    {
        $states = Arr::pluck(Util::getBrazilianStates(), 'nomeEstado', 'sigla');
        return Arr::prepend($states, 'Todos', '');
    }

    public function getCnaes()
    {
        return $this->cnaeMdl::query()
            ->orderBy('descricao')
            ->pluck('descricao', 'cod_cnae')
            ->toArray();
    }

    public function getCities($uf)
    {
        if(!is_array($uf)){
            $uf = [$uf];
        }

        return $this->citiesMdl::query()
            ->join('address.state','state.id','=','city.id_uf')
            ->whereIn('state.uf',$uf)
            ->orderBy('city.name')
            ->get(['city.name as text', 'city.id'])
            ->toArray();
    }

    public function getNeighborhood($city)
    {
        $id = $this->citiesMdl::query()
            ->select('city_code')
            ->where('desc',$city)
            ->limit(1)
            ->get()
            ->first();

        return $this->companyMdl::query()
            ->select('bairro as text')
            ->where('empresas.situacao', 2)
            ->where('cod_municipio as id', $id->city_code)
            ->orderBy('bairro')
            ->get();
    }

    public function getLegalNatures() {
        return $this->legalNatureMdl::query()
            ->orderBy('descricao')
            ->pluck('descricao', 'cod_nat_juridica')
            ->toArray();
    }
}
