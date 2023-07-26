<?php namespace App\Repositories;

use  App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;

class AppRepo extends Controller
{

    protected function applyOrdering(array $columns, array $sort, &$query)
    {
        foreach ($sort as $orderSort) {
            $query->orderBy($columns[(int)$orderSort['column']], $orderSort['dir']);
        }
    }

    protected function insertTableFlowOrLog(string $tableName, bool $log, array $data)
    {
        try {
            if($log) {
                return DB::table('public.'. $tableName. '_log')->insert($data);
            } else {
                return DB::table('public.'. $tableName. '_fluxo')->insert($data);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function insertLog(string $tableName, array $data)
    {
        try {
            return DB::table('log.'. $tableName)->insert($data);

        } catch (Exception $e) {
            throw $e;
        }
    }
}
