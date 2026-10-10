<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactInboxTest extends TestCase
{
    use RefreshDatabase;

    private function enquiry(array $attrs = []): Contact
    {
        return Contact::create(array_merge([
            'name' => 'Anna Schmidt',
            'email' => 'anna@example.com',
            'phone' => '+66958467417',
            'subject' => 'Group of twelve in March',
            'message' => 'We are twelve and would like a full day with the elephants.',
            'submitted_at' => now(),
        ], $attrs));
    }

    public function test_staff_see_the_messages_guests_sent(): void
    {
        $this->enquiry();

        $this->actingAsAdmin()
            ->get(route('admin.contacts.index'))
            ->assertOk()
            ->assertSee('Anna Schmidt')
            ->assertSee('Group of twelve in March')
            ->assertSee('ยังไม่ตอบ 1');
    }

    public function test_a_message_opens_in_full(): void
    {
        $contact = $this->enquiry();

        $this->actingAsAdmin()
            ->get(route('admin.contacts.show', $contact))
            ->assertOk()
            ->assertSee('We are twelve and would like a full day with the elephants.')
            ->assertSee('anna@example.com');
    }

    public function test_an_answered_message_can_be_marked_and_unmarked(): void
    {
        $contact = $this->enquiry();

        $this->actingAsAdmin()->post(route('admin.contacts.toggle', $contact));
        $contact->refresh();
        $this->assertNotNull($contact->handled_at);
        $this->assertNotNull($contact->handled_by, 'The name of whoever answered is kept.');

        $this->actingAsAdmin()->post(route('admin.contacts.toggle', $contact));
        $this->assertNull($contact->refresh()->handled_at);
    }

    public function test_the_list_can_show_only_what_is_still_open(): void
    {
        $this->enquiry(['name' => 'Still waiting']);
        $this->enquiry(['name' => 'Already answered', 'handled_at' => now()]);

        $this->actingAsAdmin()
            ->get(route('admin.contacts.index', ['status' => 'open']))
            ->assertOk()
            ->assertSee('Still waiting')
            ->assertDontSee('Already answered');
    }

    public function test_the_list_can_be_searched(): void
    {
        $this->enquiry(['name' => 'Anna Schmidt']);
        $this->enquiry(['name' => 'Bob Jones', 'email' => 'bob@example.com']);

        $this->actingAsAdmin()
            ->get(route('admin.contacts.index', ['q' => 'bob@']))
            ->assertOk()
            ->assertSee('Bob Jones')
            ->assertDontSee('Anna Schmidt');
    }

    public function test_a_message_can_be_deleted(): void
    {
        $contact = $this->enquiry();

        $this->actingAsAdmin()
            ->delete(route('admin.contacts.destroy', $contact))
            ->assertRedirect(route('admin.contacts.index'));

        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_a_visitor_cannot_read_the_inbox(): void
    {
        $this->get(route('admin.contacts.index'))->assertRedirect();
    }
}
