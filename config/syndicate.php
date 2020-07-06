<?php

use Nuwave\Lighthouse\Schema\Types\Scalars\Date;

return [
    /*
    |--------------------------------------------------------------------------
    | Syndicate Defaults
    |--------------------------------------------------------------------------
    |
    | These options controls the default user model that syndicate will use to generate a members relationship.
    |
    */

    /**
     * The Model class that will be used for the user model
     */
    'user_model' => App\Models\User::class,

    /**
     * The Organization class that will be used for this project.
     */
    'organization_model' => App\Models\Organization::class,

    /**
     * Create a default organization for the user
     */
    'create_default_organization' => true,

    /**
     * If this option is set to true, members that are invited into organizations will need to accept/decline
     */
    'invites' => true,

    /**
     * Define the mail templates to be used. these will be published by default
     */
    'mail' => [
        'member_invite' => resource_path('mail/memberinvite')
    ]
];
