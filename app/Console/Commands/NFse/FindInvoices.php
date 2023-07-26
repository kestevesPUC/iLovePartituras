<?php namespace App\Console\Commands\NFse;

use App\Repositories\NFse\NFseRepo;
use Illuminate\Console\Command;

class FindInvoices extends Command
{
    protected $signature = 'nfse:consultnfse';
    protected $description = 'Faz a consulta das notas ainda não sincronizadas junto a prefeitura.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $repositorio = new NFseRepo();
        $repositorio->onPost([], 'checkLots');
    }
}
