<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class TicketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'sometimes|in:' . implode(',', [
                Ticket::PRIORITY_LOW,
                Ticket::PRIORITY_NORMAL,
                Ticket::PRIORITY_HIGH,
            ]),
        ];
    }
}
