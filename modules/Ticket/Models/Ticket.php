<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;
use Modules\Team\Models\Team;
use Modules\User\Models\User;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';


    protected $fillable = [
        'title',
        'user_id',           // جدید
        'recipient_type',
        'status',
        'priority',
        'contact_name',
        'is_seen',
        'store_id',
        'assigned_to',
        'team_id',
    ];
    protected $casts = [
    'priority' => 'integer',   // اضافه کن
    ];

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->team_id) {
            return $query->where('team_id', $user->team_id);
        }

        if ($user->hasRole('admin')) {
            return $query;
        }

        if ($user->hasRole('seller')) {
            $storeIds = $user->stores()->pluck('id');

            return $query->where(function (Builder $query) use ($storeIds, $user) {
                $query->whereIn('store_id', $storeIds)
                    ->orWhere(function (Builder $query) use ($user) {
                        $query->where('recipient_type', 'user')->where('user_id', $user->id);
                    });
            });
        }

        return $query->whereRaw('1 = 0');
    }

//    public function sender()
//    {
//        return $this->belongsTo(Stores::class);
//    }

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }


    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }


}
