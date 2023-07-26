<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPortion extends Model
{
    protected $table = 'public.order_portion';
    protected $primaryKey = ['id_order', 'portion'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_order',
        'portion',
        'status',
        'date_emission',
        'date_competence',
        'date_due',
        'date_payment',
        'value_title',
        'value_charge',
        'value_payment',
        'value_reversal',
        'tid'
    ];

    public function installments()
    {
        return $this->belongsTo(Order::class, 'id', 'id_order');
    }
}
