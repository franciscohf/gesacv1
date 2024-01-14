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
        Schema::create('numeras', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo');
            $table->string('sigla');
            $table->text('descripcion');
            $table->integer('ultimo_numero');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numeras');
    }
};
