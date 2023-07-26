<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contact.person';
    protected $primaryKey = 'id';

    public function physics()
    {
        return $this->hasOne(PhysicalPerson::class, 'id', 'id');
    }

    public function legal()
    {
        return $this->hasOne(JuridicalPerson::class, 'id', 'id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id_person', 'id');
    }
}
