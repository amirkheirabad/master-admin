<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_lists', function (Blueprint $table) {
            $table->string('site_type')->default('index');
        });
    }

    public function down(): void
    {
        Schema::table('check_lists', function (Blueprint $table) {
            $table->dropColumn('site_type');
        });
    }
};
