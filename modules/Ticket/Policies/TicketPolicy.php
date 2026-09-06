<?php

namespace Modules\Ticket\Policies;

use Modules\Ticket\Models\Ticket;
use Modules\User\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('seller')
            || $user->team_id !== null;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return Ticket::query()->visibleTo($user)->whereKey($ticket)->exists();
    }
}
