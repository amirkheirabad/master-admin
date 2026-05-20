<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('factors', function (Blueprint $table) {
            $table->softDeletes(); // اضافه کردن ستون deleted_at
        });
    }

    public function down()
    {
        Schema::table('factors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};