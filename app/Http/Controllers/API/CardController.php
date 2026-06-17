<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CardService;

class CardController extends Controller
{
    protected $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function sendNotification($cardId)
    {
        $this->cardService->sendInvoiceNotification($cardId);

        return response()->json(['message' => 'success']);
    }

    public function getNotification($cardId)
    {
        return $this->cardService->notifications($cardId);
    }

    public function UnreadNotification($cardId)
    {
        return $this->cardService->unreadNotifications($cardId);
    }

    public function ReadNotification($cardId)
    {
        return $this->cardService->readNotifications($cardId);
    }
}
