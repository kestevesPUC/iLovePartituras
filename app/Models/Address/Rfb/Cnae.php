<?php

namespace App\Models\Address\Rfb;

use Illuminate\Database\Eloquent\Model;

class Cnae extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'pgsqlRfbWrite';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'public.cnaes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cod_cnae';

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
