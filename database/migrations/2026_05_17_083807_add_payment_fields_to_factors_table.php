<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('factors', function (Blueprint $table) {
            $table->string('payment_token')->nullable()->after('hash');
            $table->string('payment_rrn')->nullable()->after('payment_token');
            $table->string('payment_card')->nullable()->after('payment_rrn');
        });
    }

    public function down()
    {
        Schema::table('factors', function (Blueprint $table) {
            $table->dropColumn(['payment_token', 'payment_rrn', 'payment_card']);
        });
    }
};