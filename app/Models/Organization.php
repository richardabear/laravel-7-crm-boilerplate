<?php

namespace App\Models;

use App\Models\Contact;
use App\Models\ContactList;
use RichardAbear\Syndicate\Contracts\OrganizationInterface;
use RichardAbear\Syndicate\Models\Organization as OrganizationModel;

class Organization extends OrganizationModel implements OrganizationInterface
{
    public function lists()
    {
        return $this->hasMany(ContactList::class);
    }

    public function contacts()
    {
        return $this->hasManyThrough(Contact::class, ContactList::class);
    }
}
