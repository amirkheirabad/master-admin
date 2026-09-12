<?php

namespace Modules\CustomerForm\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title', 'description', 'is_active', 'published_version_id', 'draft_version_id'];

    protected $casts = ['is_active' => 'boolean'];

    public function versions()
    {
        return $this->hasMany(FormVersion::class);
    }

    public function publishedVersion()
    {
        return $this->belongsTo(FormVersion::class, 'published_version_id');
    }

    public function draftVersion()
    {
        return $this->belongsTo(FormVersion::class, 'draft_version_id');
    }

    public function assignments()
    {
        return $this->hasMany(FormAssignment::class);
    }
}
