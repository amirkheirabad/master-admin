<?php

namespace Modules\Stores\Models;

use Illuminate\Database\Eloquent\Model;

class StoreCheckListComment extends Model
{
    protected $fillable = [
        'store_id',
        'check_list_id',
        'comment',
    ];

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id');
    }

    public function checkList()
    {
        return $this->belongsTo(CheckList::class, 'check_list_id');
    }
}
