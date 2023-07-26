<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address.city';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'city_code';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
