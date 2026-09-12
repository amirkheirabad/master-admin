<?php

namespace Modules\CustomerForm\Models;

use Illuminate\Database\Eloquent\Model;

class FormVersion extends Model
{
    protected $fillable = ['form_id', 'version_number', 'published_at'];

    protected $casts = ['published_at' => 'datetime'];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function questions()
    {
        return $this->hasMany(FormQuestion::class)->orderBy('sort_order');
    }
}
