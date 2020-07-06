<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['note', 'contact_id'];
    
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
