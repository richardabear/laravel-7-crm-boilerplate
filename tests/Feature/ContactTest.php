<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Country;
use Tests\AuthenticatedTestCase;

class ContactTest extends AuthenticatedTestCase
{
    protected $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
        $country = Country::where('name', 'Philippines')->first();
        $contact = new Contact([
            'first_name' => 'Richard',
            'last_name' => 'Abear',
            'email' => 'abear@example.com',
            'phone_number' => '082981859',
            'status' => 'Single',
            'mobile_number' => '+639171137700',
            'country_id' => $country->id,
            'fax' => '+082981758',
            'organization_id' => $this->user->permanentOrganization()->id
        ]);

        $contact->save();
        $this->contact = $contact;
    }
    public function testCanCreateContact(): void
    {
        $country = Country::where('name', 'Philippines')->first();
        $contact = new Contact([
            'first_name' => 'Richard',
            'last_name' => 'Abear',
            'email' => 'admin@example.com',
            'phone_number' => '082981859',
            'status' => 'Single',
            'mobile_number' => '+639171137700',
            'country_id' => $country->id,
            'fax' => '+082981758',
            'organization_id' => $this->user->permanentOrganization()->id
        ]);

        $contact->save();
        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('Richard', $contact->first_name);
    }

    public function testCanQueryCreateContact(): void
    {
        $country = Country::where('name', 'Philippines')->first();
        $input = [
            'first_name' => 'Richard',
            'last_name' => 'Abear',
            'email' => 'abear@example.com',
            'phone_number' => '082981859',
            'status' => 'Single',
            'mobile_number' => '+639171137700',
            'country_id' => (string) $country->id,
            'fax' => '+082981758',
            'organization_id' => (string) $this->user->permanentOrganization()->id
        ];

        $this->graphQL(/** @lang GraphQL */'
            mutation createContact($input: CreateContactInput!) {
                createContact(input: $input) {
                    first_name
                    last_name
                    email
                    phone_number
                    date_of_birth
                    status
                    mobile_number
                    country_id
                    fax
                    organization_id
                }
            }
        ', ['input' => $input])->assertJson([
            'data' => [
                'createContact' => $input
            ]
        ]);
    }

    public function testCanQueryUpdateContact()
    {
        $updateData = [
            'id' => $this->contact->id,
            'first_name' => 'Beatrice',
            'last_name' => 'Reyes',
            'email' => 'bea@bearzu.com',
        ];

        $this->graphQL(/**@lang GraphQL */'
                mutation updateContact($input: UpdateContactInput!) {
                    updateContact(input: $input) {
                        id
                        first_name
                        last_name
                        email
                        phone_number
                        date_of_birth
                        status
                        mobile_number
                        country_id
                        fax
                        organization_id
                    }
                }
            ', ['input' => $updateData])->assertJson([
                'data' => [
                    'updateContact' => $updateData
                ]
            ]);
            
        $contact = Contact::find($this->contact->id);
        $this->assertEquals($updateData['first_name'], $contact->first_name);
        $this->assertEquals($updateData['last_name'], $contact->last_name);
        $this->assertEquals($updateData['email'], $contact->email);
    }

    public function testCanQueryDeleteContact()
    {
        $newContact = new Contact([
            'first_name' => 'Richard',
            'last_name' => 'Abear',
            'email' => 'email@testmail.com',
            'organization_id' => $this->user->permanentOrganization()->id
        ]);

        $newContact->save();

        $this->graphQL('
            mutation deleteContact($id: ID!) {
                deleteContact(id: $id) {
                    id
                }
            }
        ', ['id' => $newContact->id])->assertJson([
            'data' => ['deleteContact' => [
                'id' => $newContact->id
            ]]
        ]);

        $this->assertNull(Contact::find($newContact->id));
    }
}
