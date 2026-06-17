<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Notifications\InvoicePaid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_invoice_notification()
    {
        Notification::fake();
        $card = Card::factory()->create();

        $response = $this->getJson('/api/card/send-notification/' . $card->id);

        $response
            ->assertOk()
            ->assertJson(['message' => 'success']);

        Notification::assertSentTo($card, InvoicePaid::class);
    }

    public function test_it_returns_card_notifications()
    {
        $card = Card::factory()->create();

        $response = $this->getJson('/api/card/get-notification/' . $card->id);

        $response->assertOk();
    }
}
