<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && ($this->user()->isAdmin() || $this->user()->isSeller());
    }

    public function rules(): array
    {
        return [
            'response' => 'required|string',
        ];
    }
}
