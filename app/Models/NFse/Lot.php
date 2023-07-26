<?php

namespace App\Models\NFse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $table = 'invoice.lot';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'environment',
        'id_user',
        'date_creation',
        'date_receipt',
        'protocol',
        'status',
        'number_lot'
    ];

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }


    public function note()
    {
        return $this->hasMany(Nfse::class, 'id_lot', 'id');
    }
}
