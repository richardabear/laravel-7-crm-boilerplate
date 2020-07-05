<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\Register;
use Laravel\Passport\Passport;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use RichardAbear\Syndicate\Models\Organization;

class UserTest extends TestCase
{
    use MakesGraphQLRequests, RefreshDatabase;

    protected function createUser(): User
    {
        $user = new User([
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);
        $user->save();
        return $user;
    }

    public function testCanCreateUser(): void
    {
        $user = $this->createUser();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@gmail.com', $user->email);
    }

    public function testUserHasPermanentOrganization(): void
    {
        $user = $this->createUser();
        $this->assertInstanceOf(Organization::class, $user->permanentOrganization());
    }

    public function testUserCanCreateOrganization(): void
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->graphQL(/** @lang GraphQL */'
                mutation {
                    createOrganization(input: {name: "test"}) {
                        id
                        name
                    }
                }
        ')->json();
        $this->expectOutputString('');
        var_dump($response);
    }
}
