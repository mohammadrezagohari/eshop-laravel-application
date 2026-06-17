<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_OPEN = 'open';
    public const STATUS_ANSWERED = 'answered';
    public const STATUS_CLOSED = 'closed';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';

    protected $fillable = [
        'user_id',
        'assigned_to',
        'subject',
        'message',
        'response',
        'status',
        'priority',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function canBeViewedBy(User $user): bool
    {
        return $user->isAdmin()
            || $user->isSeller()
            || $this->user_id === $user->id;
    }
}
