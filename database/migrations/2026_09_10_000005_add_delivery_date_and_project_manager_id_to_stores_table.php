<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->timestamp('delivery_date')->nullable();
            $table->unsignedBigInteger('project_manager_id')->nullable();
            $table->foreign('project_manager_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['project_manager_id']);
            $table->dropColumn(['delivery_date', 'project_manager_id']);
        });
    }
};
