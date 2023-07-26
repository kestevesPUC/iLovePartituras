<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NFse extends Model
{
    protected $table = 'invoice.nfse';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_lot',
        'id_order',
        'environment',
        'number_rps',
        'series_rps',
        'type_rps',
        'date_emission_rps',
        'status_rps',
        'number_rps_substituted',
        'series_rps_substituted',
        'number_nfse',
        'code_verification',
        'date_emission_nfse',
        'competence',
        'number_nfse_replaced',
        'number_nfse_substitute',
        'nature_operation',
        'regime_special_taxation',
        'opting_simple_national',
        'promoter_cultural',
        'other_information',
        'code_city_generating',
        'uf_city_generating',
        'code_cancellation',
        'date_cancellation',
        'xml'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'id_order');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'id_nfse', 'id');
    }

    public function construction()
    {
        return $this->hasOne(Construction::class, 'id_nfse', 'id');
    }

    public function intermediate()
    {
        return $this->hasOne(Intermediate::class, 'id_nfse', 'id');
    }

    public function provider()
    {
        return $this->hasOne(Provider::class, 'id_nfse', 'id');
    }

    public function taker()
    {
        return $this->hasOne(Taker::class, 'id_nfse', 'id');
    }

    public function log()
    {
        return $this->hasMany(Service::class, 'id_nfse', 'id');
    }
}
