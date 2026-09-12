<?php

namespace Modules\CustomerForm\Models;

use Illuminate\Database\Eloquent\Model;

class FormQuestionOption extends Model
{
    protected $fillable = ['form_question_id', 'label', 'sort_order'];

    public function question()
    {
        return $this->belongsTo(FormQuestion::class, 'form_question_id');
    }
}
