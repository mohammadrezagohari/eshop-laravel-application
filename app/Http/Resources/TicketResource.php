<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'message' => $this->message,
            'response' => $this->response,
            'status' => $this->status,
            'priority' => $this->priority,
            'user' => [
                'id' => $this->User?->id,
                'name' => $this->User?->name,
                'email' => $this->User?->email,
            ],
            'assignee' => $this->Assignee ? [
                'id' => $this->Assignee->id,
                'name' => $this->Assignee->name,
                'email' => $this->Assignee->email,
            ] : null,
            'answered_at' => $this->answered_at,
            'created_at' => $this->created_at,
        ];
    }
}
