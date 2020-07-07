<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'street_address',
        'state',
        'city',
        'zip',
        'contact_id'
    ];


    public function contacts()
    {
        return $this->belongsTo(Contact::class);
    }
}
