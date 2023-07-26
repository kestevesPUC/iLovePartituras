<?php

namespace App\Repositories\Charts;

use App\Models\Charts\EconomyActivity;
use App\Models\Charts\LegalNature;

class ChartsRepo
{
    /**
     * Query na view materialized view_natureza_juridica
     * Retorna a quantidade de empresas por:
     * Uf, Porte, Natureza Jurídica
     * @return mixed[]|void
     */
    public function queryLegalNature()
    {
        try {
            return LegalNature::query()
                ->select("*")
                ->where("uf","!=", "")
                ->where("uf","!=", "EX")
                ->where("uf","!=", "BR")
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Query na view materialized uf_porte_atividade_economica
     * Retorna a quantidade de empresas por:
     * Uf, Porte, Atividade Econômica
     * @return mixed[]|void
     */
    public function queryEconomyActivity()
    {
        try {
            return EconomyActivity::query()
                ->select("*")
                ->where("uf","!=", "")
                ->where("uf","!=", "EX")
                ->where("uf","!=", "BR")
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
