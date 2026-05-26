<?php

namespace Modules\Education\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video_category extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'video_categories';

    protected $fillable = ['name'];

    public function videos()
    {
        return $this->hasmany(Video::class);
    }

}
