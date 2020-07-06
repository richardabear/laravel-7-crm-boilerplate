<?php

namespace Tests\Feature;

use App\Models\ContactList;
use App\Models\Organization;
use Tests\AuthenticatedTestCase;

class ContactListTest extends AuthenticatedTestCase
{
    /**
    * Can create the list of organizations
    *
    * @return void
    */
    public function testCanCreateContactList(): void
    {
        $list = new ContactList(['name' => 'Sample User List', 'organization_id' => $this->organization->id]);
        $list->save();

        $this->assertInstanceOf(ContactList::class, $list);
        $this->assertEquals('Sample User List', $list->name);
        $this->assertEquals($this->organization->id, $list->organization_id);
    }

    /**
     * Tests that the user is able to create a list and validates the ownership of that user to that list.
     *
     * @return void
     */
    public function testCanCreateContactListAndValidateOwnership(): void
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation createContactList($input: CreateContactListInput!) {
                createContactList(input: $input) {
                    id
                    name
                    organization {
                        id
                        name
                    }
                }
            }
        ',
            [
            'input' => [
                'name' => 'Sample List',
                'organization_id' =>$this->organization->id
                ]
            ]
        )->assertJson([
            'data' => [
                'createContactList' => [
                    'name' => 'Sample List',
                    'organization' => [
                        'id' => $this->organization->id
                    ]
                ]
            ]
        ])->json();

        $list = ContactList::find($response['data']['createContactList']['id']);
        $this->assertInstanceOf(ContactList::class, $list);
        $this->assertEquals($list->organization->id, $this->organization->id);
    }

   

    /**
     * Tests that the user can not create lists for other organizations
     *
     * @return void
     */
    public function testCanNotcreateContactListForUnownedOrganization()
    {
        $dummyOrganization = new Organization(['name' => 'Dummy', 'is_permanent' => true]);
        $dummyOrganization->save();

        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation createContactList($input: CreateContactListInput!) {
                createContactList(input: $input) {
                    id
                    name
                    organization {
                        id
                        name
                    }
                }
            }
        ',
            [
            'input' => [
                'name' => 'Sample List',
                'organization_id' => $dummyOrganization->id
                ]
            ]
        )->json();

        $this->assertNull($response['data']['createContactList']);
        $this->assertArrayHasKey('errors', $response);
        $this->assertEquals('authorization', $response['errors'][0]['extensions']['category']);
    }

    public function testCanQueryUpdateContactList()
    {
        $contactList = new ContactList([
            'name' => 'Sample List',
            'organization_id' => $this->user->permanentOrganization()->id
        ]);
        $contactList->save();

        $updateData = [
            'id' => $contactList->id,
            'name' => 'New Name'
        ];

        $this->graphQL('
            mutation updateContactList($input: UpdateContactListInput!) {
                updateContactList(input: $input) {
                    id
                    name
                }
            }
        ', ['input' => $updateData])->assertJson([
            'data' => [
                'updateContactList' => $updateData
            ]
        ]);

        $contactList->refresh();
        $this->assertEquals('New Name', $contactList->name);
    }

    public function testCanQueryDeleteContactList()
    {
        $contactList = new ContactList([
            'name' => "Sample List",
            'organization_id' => $this->user->permanentOrganization()->id
        ]);
        
        $contactList->save();

        $this->graphQL('
            mutation deleteContactList($id: ID!) {
                deleteContactList(id: $id) {
                    id
                }
            }
        ', ['id' => $contactList->id])->assertJson([
            'data' => [
                'deleteContactList' => ['id' => $contactList->id]
            ]
        ]);

        $this->assertNull(ContactList::find($contactList->id));
    }
}
