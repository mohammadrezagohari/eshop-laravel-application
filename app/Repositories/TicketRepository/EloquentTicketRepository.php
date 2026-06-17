<?php

namespace App\Repositories\TicketRepository;

use App\Models\Ticket;

class EloquentTicketRepository implements IEloquentTicketRepository
{
    public function listForUser($user)
    {
        return Ticket::with(['User', 'Assignee'])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->get();
    }

    public function listForStaff()
    {
        return Ticket::with(['User', 'Assignee'])
            ->orderByDesc('id')
            ->get();
    }

    public function create($user, array $data)
    {
        return Ticket::create([
            'user_id' => $user->id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'priority' => $data['priority'] ?? Ticket::PRIORITY_NORMAL,
            'status' => Ticket::STATUS_OPEN,
        ])->load(['User', 'Assignee']);
    }

    public function find($id)
    {
        return Ticket::with(['User', 'Assignee'])->findOrFail($id);
    }

    public function reply($ticket, $user, string $response)
    {
        $ticket->update([
            'assigned_to' => $user->id,
            'response' => $response,
            'status' => Ticket::STATUS_ANSWERED,
            'answered_at' => now(),
        ]);

        return $ticket->refresh()->load(['User', 'Assignee']);
    }

    public function close($ticket)
    {
        $ticket->update([
            'status' => Ticket::STATUS_CLOSED,
        ]);

        return $ticket->refresh()->load(['User', 'Assignee']);
    }
}
