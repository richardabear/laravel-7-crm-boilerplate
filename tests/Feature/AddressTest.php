<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Tests\AuthenticatedTestCase;

class AddressTest extends AuthenticatedTestCase
{
    protected $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->contact = new Contact([
            'first_name' => 'Richard',
            'last_name' => 'Abear',
            'organization_id' => $this->user->permanentOrganization()->id
        ]);

        $this->contact->save();
    }


    public function testCanCreateAddress()
    {
        $address = new Address([
            'street_address' => 'Sample Street Address',
            'state' => 'Davao Del Sur',
            'city' => 'City',
            'zip' => '8000',
            'contact_id' => $this->contact->id
        ]);

        $address->save();

        $this->assertEquals('Sample Street Address', $address->street_address);
    }

    public function testCanQueryCreateAddress()
    {
        $input = [
            'street_address' => 'Sample address',
            'city' => 'Davao',
            'state' => 'Davao Del Sur',
            'contact_id' => $this->contact->id
        ];

        $this->graphQL('
            mutation createAddress($input: CreateAddressInput!) {
                createAddress(input: $input) {
                        id
                        street_address
                        state
                        city
                        contact_id
                }
            }
        ', ['input' => $input])->assertJson([
            'data' => [
                'createAddress' => $input
            ]
        ]);
    }

    public function testCanQueryUpdateAddress()
    {
        $address = new Address([
            'street_address' => 'Sample Address',
            'city' => 'Davao',
            'state' => 'Davao Del Sur',
            'contact_id' => $this->contact->id
        ]);

        $address->save();
        $this->assertEquals($address->street_address, 'Sample Address');
        
        $inputData = [
            'id' => $address->id,
            'street_address' => '123 street at village'
        ];

        $this->graphQL('
            mutation updateAddress($input: UpdateAddressInput!) {
                updateAddress(input: $input) {
                    id
                    street_address
                    state
                    city
                }
            }
        ', ['input' => $inputData])->assertJson([
            'data' => [
                'updateAddress' => $inputData
            ]
        ]);

        $address->refresh();
        $this->assertEquals($address->street_address, '123 street at village');
    }
}
