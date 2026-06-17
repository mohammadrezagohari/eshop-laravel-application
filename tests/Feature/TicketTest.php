<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_ticket()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/tickets', [
            'subject' => 'Payment issue',
            'message' => 'I need support for my order.',
            'priority' => Ticket::PRIORITY_HIGH,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.subject', 'Payment issue')
            ->assertJsonPath('data.status', Ticket::STATUS_OPEN);

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'subject' => 'Payment issue',
        ]);
    }

    public function test_user_cannot_view_another_users_ticket()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $ticket = Ticket::create([
            'user_id' => $owner->id,
            'subject' => 'Private ticket',
            'message' => 'Private message',
        ]);

        Sanctum::actingAs($otherUser);

        $response = $this->getJson('/api/tickets/' . $ticket->id);

        $response->assertForbidden();
    }

    public function test_staff_can_reply_to_ticket()
    {
        $owner = User::factory()->create();
        $seller = User::factory()->create(['role' => User::ROLE_SELLER]);
        $ticket = Ticket::create([
            'user_id' => $owner->id,
            'subject' => 'Order question',
            'message' => 'Where is my order?',
        ]);

        Sanctum::actingAs($seller);

        $response = $this->patchJson('/api/staff/tickets/' . $ticket->id . '/reply', [
            'response' => 'Your order is being prepared.',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', Ticket::STATUS_ANSWERED)
            ->assertJsonPath('data.assignee.id', $seller->id);
    }

    public function test_ticket_owner_can_close_ticket()
    {
        $owner = User::factory()->create();
        $ticket = Ticket::create([
            'user_id' => $owner->id,
            'subject' => 'Close this',
            'message' => 'Resolved.',
        ]);

        Sanctum::actingAs($owner);

        $response = $this->patchJson('/api/tickets/' . $ticket->id . '/close');

        $response
            ->assertOk()
            ->assertJsonPath('data.status', Ticket::STATUS_CLOSED);
    }
}
