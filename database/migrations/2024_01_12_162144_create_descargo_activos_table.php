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
        Schema::create('descargo_activos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->string('motivo_descargo');
            $table->integer('asignacion_activo_id');
			$table->foreign('asignacion_activo_id')->references('id')->on('asignacion_activos');
            $table->string('estado', 2)->nullable()->default('A');
            $table->integer('usuario_registro_id');
			$table->foreign('usuario_registro_id')->references('id')->on('users');
            $table->integer('usuario_actualizacion_id');
			$table->foreign('usuario_actualizacion_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descargo_activos');
    }
};
