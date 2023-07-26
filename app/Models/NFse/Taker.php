<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taker extends Model
{
    protected $table = 'invoice.nfse_taker';
    protected $primaryKey = 'id_nfse';
    public $timestamps = false;

    protected $fillable = [
        'id_nfse',
        'cpf_cnpj_taker',
        'recommendation_cpf_cnpj',
        'inscription_municipal_taker',
        'reason_social_taker',
        'address_taker',
        'number_address_taker',
        'complement_address_taker',
        'neighborhood_taker',
        'city_taker',
        'uf_taker',
        'cep_taker',
        'email_taker',
        'telephone_taker'
    ];

    public function nfse()
    {
        return $this->belongsTo(Nfse::class, 'id', 'id_nfse');
    }
}
