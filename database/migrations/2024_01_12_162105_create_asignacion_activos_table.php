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
        Schema::create('asignacion_activos', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado_id');
			$table->foreign('empleado_id')->references('id')->on('empleados');
            $table->string('descripcion');
            $table->string('observacion');
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
        Schema::dropIfExists('asignacion_activos');
    }
};
