<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'invoice.nfse_service';
    protected $primaryKey = 'id_nfse';
    public $timestamps = false;

    protected $fillable = [
        'id_nfse',
        'value_service',
        'value_deduction',
        'value_pis',
        'value_cofins',
        'value_inss',
        'value_ir',
        'value_csll',
        'iss_retain',
        'value_iss',
        'value_iss_retain',
        'other_retentions',
        'value_credit',
        'base_calculation',
        'aliquot',
        'value_net_nfse',
        'value_discount_unconditioned',
        'value_discount_conditioned',
        'item_list_service',
        'code_cnae',
        'code_taxation_city',
        'discription',
        'city_installment_service',
    ];

    public function nfse()
    {
        return $this->belongsTo(Nfse::class, 'id', 'id_nfse');
    }
}
