<?php

namespace Tests;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AuthenticatedTestCase extends TestCase
{
    use RefreshDatabase, MakesGraphQLRequests;

    protected $organization;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);
        $this->user->save();
        $this->organization = $this->user->permanentOrganization();
        Passport::actingAs($this->user);
    }
}
