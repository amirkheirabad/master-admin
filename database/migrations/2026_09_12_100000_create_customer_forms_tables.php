<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('published_version_id')->nullable();
            $table->unsignedBigInteger('draft_version_id')->nullable();
            $table->timestamps();
        });

        Schema::create('form_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->restrictOnDelete();
            $table->unsignedInteger('version_number');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['form_id', 'version_number']);
        });

        Schema::create('form_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_version_id')->constrained('form_versions')->cascadeOnDelete();
            $table->string('label');
            $table->string('type', 20);
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order');
            $table->timestamps();
            $table->unique(['form_version_id', 'sort_order']);
        });

        Schema::create('form_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_question_id')->constrained('form_questions')->cascadeOnDelete();
            $table->string('label');
            $table->unsignedInteger('sort_order');
            $table->timestamps();
            $table->unique(['form_question_id', 'sort_order']);
        });

        Schema::create('form_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->restrictOnDelete();
            $table->foreignId('form_version_id')->constrained('form_versions')->restrictOnDelete();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->char('token_hash', 64)->unique();
            $table->text('token_encrypted');
            $table->unsignedBigInteger('current_submission_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['form_id', 'store_id']);
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_assignment_id')->constrained('form_assignments')->restrictOnDelete();
            $table->foreignId('form_version_id')->constrained('form_versions')->restrictOnDelete();
            $table->timestamp('submitted_at');
            $table->timestamps();
        });

        Schema::create('form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_submission_id')->constrained('form_submissions')->cascadeOnDelete();
            $table->foreignId('form_question_id')->constrained('form_questions')->restrictOnDelete();
            $table->json('value');
            $table->timestamps();
            $table->unique(['form_submission_id', 'form_question_id']);
        });

        Schema::table('forms', function (Blueprint $table) {
            $table->foreign('published_version_id')->references('id')->on('form_versions')->nullOnDelete();
            $table->foreign('draft_version_id')->references('id')->on('form_versions')->nullOnDelete();
        });

        Schema::table('form_assignments', function (Blueprint $table) {
            $table->foreign('current_submission_id')->references('id')->on('form_submissions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('form_assignments', fn (Blueprint $table) => $table->dropForeign(['current_submission_id']));
        Schema::table('forms', function (Blueprint $table) {
            $table->dropForeign(['published_version_id']);
            $table->dropForeign(['draft_version_id']);
        });

        Schema::dropIfExists('form_answers');
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_assignments');
        Schema::dropIfExists('form_question_options');
        Schema::dropIfExists('form_questions');
        Schema::dropIfExists('form_versions');
        Schema::dropIfExists('forms');
    }
};
