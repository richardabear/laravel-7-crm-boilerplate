<?php

namespace Tests\Feature;

use App\Models\ContactList;
use App\Models\Organization;
use Tests\AuthenticatedTestCase;

class OrganizationTest extends AuthenticatedTestCase
{
    public function testUserHasOrganization()
    {
        $this->assertInstanceOf(Organization::class, $this->organization);

        $assertion = $this->user->can('member', $this->organization);
        $this->assertTrue($assertion);
    }

    public function testUserCanCreateOrganization()
    {
        $this->graphQL(/** @lang GraphQL */'
           mutation createOrganization($input: OrganizationInput) {
               createOrganization(input: $input) {
                   id
                   name
                }
            }
        ', [
            'input' => [
                'name' => 'Test Organization'
            ]
        ])->assertJson([
            'data' => [
                'createOrganization' => [
                    'name' => 'Test Organization'
                ]
            ]
        ])->json();
    }

    public function testCanGetOrganizationLists()
    {
        $newList = new ContactList([
            'name' => 'Test List',
            'organization_id' => $this->organization->id
        ]);

        $newList->save();

        $lists = $this->organization->lists;
        $this->assertNotEmpty($lists);
        $this->assertTrue($lists->contains($newList));
    }

    public function testUserCanQueryOrganization()
    {
        $this->graphQL('
            query organization($id: ID!) {
                organization(id: $id) {
                    id
                    name
                }
            }
        ', [
            'id' => $this->organization->id
        ])->assertJson([
            'data' => [
                'organization' => [
                    'name' => $this->organization->name,
                    'id' => $this->organization->id
                ]
            ]
        ]);
    }

    // public function testUserCanQueryOrganizationList()
    // {
    //     $newList = new ContactList([
    //         'name' => 'Test List',
    //         'organization_id' => $this->organization->id
    //     ]);

    //     $newList->save();

    //     $response = $this->graphQL('
    //         query organizationLists($first: Int!, $page: Int!, $organization_id: ID!) {

    //         }
    //     ');
    // }
}
