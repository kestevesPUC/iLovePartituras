<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    protected $table = 'invoice.nfse_construction_civil';

    protected $primaryKey = 'id_nfse';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_nfse',
        'code_constructions',
        'art'
    ];

    public function nfse()
    {
        return $this->belongsTo(Nfse::class, 'id', 'id_nfse');
    }
}
