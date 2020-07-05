<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public function contacts()
    {
        return $this->belongsTo(Contact::class);
    }
}
