<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RichardAbear\Syndicate\Models\Organization;

class ContactList extends Model
{
    //
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
