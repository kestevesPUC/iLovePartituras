<?php

namespace App\Models\Charts;

use Illuminate\Database\Eloquent\Model;

class LegalNature extends Model
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
    protected $table = 'public.view_natureza_juridica';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = '';

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
