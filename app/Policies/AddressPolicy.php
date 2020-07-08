<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
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

    public function update(User $user, Address $address)
    {
        return $user->belongsToOrganization($address->contact->organization_id);
    }
    
    public function delete(User $user, Address $address)
    {
        return $user->belongsToOrganization($address->contact->organization_id);
    }
}
