<?php

namespace App\Services;

use App\Http\Resources\InvoiceResource;
use App\Models\Card;
use App\Notifications\InvoicePaid;

class CardService
{
    public function sendInvoiceNotification($cardId): Card
    {
        $card = Card::findOrFail($cardId);
        $card->notify(new InvoicePaid(InvoiceResource::make($card)));

        return $card;
    }

    public function notifications($cardId)
    {
        return Card::findOrFail($cardId)->notifications;
    }

    public function unreadNotifications($cardId)
    {
        return Card::findOrFail($cardId)->unreadNotifications;
    }

    public function readNotifications($cardId)
    {
        return Card::findOrFail($cardId)->readNotifications;
    }
}
