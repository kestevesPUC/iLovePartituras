<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'invoice.nfse_provider';
    protected $primaryKey = 'id_nfse';
    public $timestamps = false;

    protected $fillable = [
        'id_nfse',
        'inscription_provider',
        'reason_social_provider',
        'name_fantasy_provider',
        'cnpj_provider',
        'address_provider',
        'number_address_provider',
        'complement_address_provider',
        'neighborhood_provider',
        'city_provider',
        'uf_provider',
        'cep_provider',
        'email_provider',
        'telephone_provider',
    ];

    public function nfse()
    {
        return $this->belongsTo(Nfse::class, 'id', 'id_nfse');
    }
}
