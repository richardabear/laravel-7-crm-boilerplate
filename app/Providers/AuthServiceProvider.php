<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\Note;
use App\Policies\AddressPolicy;
use App\Policies\ContactListPolicy;
use App\Policies\ContactPolicy;
use App\Policies\NotePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Contact::class => ContactPolicy::class,
        Note::class => NotePolicy::class,
        ContactList::class => ContactListPolicy::class,
        Address::class => AddressPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        //
    }
}
