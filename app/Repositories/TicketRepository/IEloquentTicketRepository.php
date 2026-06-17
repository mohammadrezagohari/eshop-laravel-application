<?php

namespace App\Repositories\TicketRepository;

interface IEloquentTicketRepository
{
    public function listForUser($user);

    public function listForStaff();

    public function create($user, array $data);

    public function find($id);

    public function reply($ticket, $user, string $response);

    public function close($ticket);
}
