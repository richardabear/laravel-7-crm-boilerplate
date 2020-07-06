<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RichardAbear\Syndicate\Models\Organization;

class ContactList extends Model
{
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name', 'organization_id'
    ];

    //
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
