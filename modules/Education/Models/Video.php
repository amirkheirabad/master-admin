<?php

namespace Modules\Education\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Education\Models\Video_category;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = ['title','description', 'file_path' ,'category_id'];

    public function category()
    {
        return $this->belongsTo(Video_category::class);
    }

}
