<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('link')->nullable()->change();
            $table->string('province')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('code_posty')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Intentionally left empty.
        // The nullable change is not safely reversible without modifying
        // existing NULL data.
    }
};
