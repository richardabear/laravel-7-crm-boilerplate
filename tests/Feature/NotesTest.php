<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Note;
use App\Models\Contact;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedTestCase;

class NotesTest extends AuthenticatedTestCase
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

    public function testCanCreateNote()
    {
        $note = new Note([
            'contact_id' => $this->contact->id,
            'note' => 'Sample Note'
        ]);

        $note->save();
        $this->assertEquals('Sample Note', $note->note);
    }

    public function testCanQueryCreateNote()
    {
        $noteData = [
            'note' => 'Sample Note',
            'contact_id' => $this->contact->id
        ];

        $this->graphQL('
            mutation createNote($input: CreateNoteInput!) {
                createNote(input: $input) {
                    id
                    note
                    contact_id
                }
            }
        ', ['input' => $noteData])->assertJson([
            'data' => [
                'createNote' => $noteData
            ]
        ]);
    }

    public function testCanQueryUpdateNote()
    {
        $note = new Note([
            'note' => 'New Note',
            'contact_id' => $this->contact->id
        ]);
        $note->save();

        $noteData = [
            'id' => $note->id,
            'note' => 'New Note Name',
        ];

        $this->graphQL('
            mutation updateNote($input: UpdateNoteInput!) {
                updateNote(input: $input) {
                    id
                    note
                }
            }
        ', ['input' => $noteData])->assertJson([
            'data' => [
                'updateNote' => [
                    'id' => $note->id,
                    'note' => 'New Note Name'
                ]
            ]
        ]);
    }

    public function testCanQueryDeleteNote()
    {
        $note = new Note([
            'note' => 'Sample',
            'contact_id' => $this->contact->id
        ]);

        $note->save();

        $this->graphQL('
            mutation deleteNote($id: ID!) {
                deleteNote(id: $id) {
                    id
                }
            }
        ')->assertJson([
            'data' => [
                'deleteNote' => [
                    'id' => $note->id
                ]
            ]
        ]);
    }
}
