<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        return TicketResource::collection(
            $this->ticketService->listForUser(request()->user())
        );
    }

    public function staffIndex()
    {
        return TicketResource::collection(
            $this->ticketService->listForStaff()
        );
    }

    public function store(TicketStoreRequest $request)
    {
        return TicketResource::make(
            $this->ticketService->create($request->user(), $request->validated())
        )->response()->setStatusCode(HTTPResponse::HTTP_CREATED);
    }

    public function show($id)
    {
        $ticket = $this->ticketService->find($id);

        if (!$ticket->canBeViewedBy(request()->user())) {
            return response()->json(['message' => 'Forbidden.'], HTTPResponse::HTTP_FORBIDDEN);
        }

        return TicketResource::make($ticket);
    }

    public function reply($id, TicketReplyRequest $request)
    {
        $ticket = $this->ticketService->find($id);

        return TicketResource::make(
            $this->ticketService->reply($ticket, $request->user(), $request->response)
        );
    }

    public function close($id)
    {
        $ticket = $this->ticketService->find($id);

        if (!$ticket->canBeViewedBy(request()->user())) {
            return response()->json(['message' => 'Forbidden.'], HTTPResponse::HTTP_FORBIDDEN);
        }

        return TicketResource::make(
            $this->ticketService->close($ticket)
        );
    }
}
