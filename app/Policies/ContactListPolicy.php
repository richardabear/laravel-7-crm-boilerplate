<?php

namespace App\Policies;

use App\Models\ContactList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactListPolicy
{
    use HandlesAuthorization;

    public function update(User $user, ContactList $list)
    {
        return $user->belongsToOrganization($list->organization_id);
    }

    public function delete(User $user, ContactList $list)
    {
        return $user->belongsToOrganization($list->organization_id);
    }
}
