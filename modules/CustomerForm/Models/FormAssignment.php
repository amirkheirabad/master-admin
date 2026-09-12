<?php

namespace Modules\CustomerForm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Stores\Models\Stores;

class FormAssignment extends Model
{
    protected $fillable = [
        'form_id',
        'form_version_id',
        'store_id',
        'token_hash',
        'token_encrypted',
        'current_submission_id',
        'is_active',
    ];

    protected $casts = ['token_encrypted' => 'encrypted', 'is_active' => 'boolean'];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function formVersion()
    {
        return $this->belongsTo(FormVersion::class);
    }

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id');
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function currentSubmission()
    {
        return $this->belongsTo(FormSubmission::class, 'current_submission_id');
    }
}
