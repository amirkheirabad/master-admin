<?php

namespace Tests\Feature\CustomerForm;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\CustomerForm\Models\Form;
use Tests\TestCase;

class CustomerFormSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_form_tables_and_version_relations_exist(): void
    {
        foreach (['forms', 'form_versions', 'form_questions', 'form_question_options', 'form_assignments', 'form_submissions', 'form_answers'] as $table) {
            $this->assertTrue(Schema::hasTable($table));
        }

        $form = Form::create(['title' => 'فرم تست']);
        $version = $form->versions()->create(['version_number' => 1]);
        $form->update(['draft_version_id' => $version->id]);

        $this->assertTrue($form->fresh()->draftVersion->is($version));
    }
}
