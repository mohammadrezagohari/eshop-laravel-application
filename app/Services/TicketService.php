<?php

namespace App\Services;

use App\Repositories\TicketRepository\IEloquentTicketRepository;

class TicketService
{
    protected $tickets;

    public function __construct(IEloquentTicketRepository $tickets)
    {
        $this->tickets = $tickets;
    }

    public function listForUser($user)
    {
        return $this->tickets->listForUser($user);
    }

    public function listForStaff()
    {
        return $this->tickets->listForStaff();
    }

    public function create($user, array $data)
    {
        return $this->tickets->create($user, $data);
    }

    public function find($id)
    {
        return $this->tickets->find($id);
    }

    public function reply($ticket, $user, string $response)
    {
        return $this->tickets->reply($ticket, $user, $response);
    }

    public function close($ticket)
    {
        return $this->tickets->close($ticket);
    }
}
