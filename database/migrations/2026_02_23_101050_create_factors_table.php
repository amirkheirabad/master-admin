<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores');
            $table->integer('price');
            $table->timestamp('factor_date')->nullable();
            $table->string('hash')->unique();
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('national_kod')->nullable();
            $table->text('description')->nullable();
            $table->boolean('show_status');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->tinyInteger('price_status')->default(0);
            $table->timestamp('paid_factor_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factors');
    }
};
