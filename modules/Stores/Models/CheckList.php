<?php

namespace Modules\Stores\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Factor\Models\Factor;

class CheckList extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'check_lists';



    protected $fillable = ['title'];
}
