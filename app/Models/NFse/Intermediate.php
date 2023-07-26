<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intermediate extends Model
{
    protected $table = 'invoice.nfse_intermediary';
    protected $primaryKey = 'id_nfse';
    public $timestamps = false;

    protected $fillable = [
        'id_nfse',
        'reason_social_intermediary_service',
        'registration_municipal_intermediary',
        'cnpj_intermediary_service',
    ];

    public function nfse()
    {
        return $this->belongsTo(Nfse::class, 'id', 'id_nfse');
    }
}
