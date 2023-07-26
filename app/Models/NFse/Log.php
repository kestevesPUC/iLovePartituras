<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'invoice.logs';
    protected $primaryKey = 'id_nfse';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_nfse',
        'id_user',
        'date',
        'description'
    ];

    public function nfse()
    {
        return $this->belongsTo(Nfse::class, 'id', 'id_nfse');
    }
}
