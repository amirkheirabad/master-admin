<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('type');
            $table->index('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('assigned_to');
            $table->index(['team_id', 'status', 'updated_at']);
            $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['team_id', 'status', 'updated_at']);
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['team_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });

        Schema::dropIfExists('teams');
    }
};
