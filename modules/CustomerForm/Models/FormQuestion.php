<?php

namespace Modules\CustomerForm\Models;

use Illuminate\Database\Eloquent\Model;

class FormQuestion extends Model
{
    public const TYPES = ['text', 'textarea', 'select', 'radio', 'checkbox', 'boolean'];

    protected $fillable = ['form_version_id', 'label', 'type', 'is_required', 'sort_order'];

    protected $casts = ['is_required' => 'boolean'];

    public function formVersion()
    {
        return $this->belongsTo(FormVersion::class);
    }

    public function options()
    {
        return $this->hasMany(FormQuestionOption::class)->orderBy('sort_order');
    }
}
