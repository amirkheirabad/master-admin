<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_check_list_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('check_list_id')->constrained('check_lists');
            $table->text('comment');
            $table->timestamps();
            $table->unique(['store_id', 'check_list_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_check_list_comments');
    }
};
