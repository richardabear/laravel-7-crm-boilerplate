<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Contact $contact)
    {
        return $user->belongsToOrganization($contact->organization_id);
    }

    public function delete(User $user, Contact $contact)
    {
        return $user->belongsToOrganization($contact->organization_id);
    }
}
