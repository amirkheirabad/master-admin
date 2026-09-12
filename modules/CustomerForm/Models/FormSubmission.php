<?php

namespace Modules\CustomerForm\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = ['form_assignment_id', 'form_version_id', 'submitted_at'];

    protected $casts = ['submitted_at' => 'datetime'];

    public function assignment()
    {
        return $this->belongsTo(FormAssignment::class, 'form_assignment_id');
    }

    public function formVersion()
    {
        return $this->belongsTo(FormVersion::class);
    }

    public function answers()
    {
        return $this->hasMany(FormAnswer::class);
    }
}
