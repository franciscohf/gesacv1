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
        Schema::create('historico_activos', function (Blueprint $table) {
            $table->id();
            $table->integer('activo_tecnologico_id');
			$table->foreign('activo_tecnologico_id')->references('id')->on('activos_tecnologicos');
            $table->string('codigo')->nullable();
            $table->string('nombre');
            $table->bigInteger('marca_id');
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->string('modelo');
            $table->string('serie');
            $table->string('qr')->nullable();
            $table->string('imagen')->nullable();
            $table->string('caracteristicas')->nullable();
            $table->integer('estado_activo_id');
			$table->foreign('estado_activo_id')->references('id')->on('estados_activos');
            $table->integer('tipo_activo_id');
			$table->foreign('tipo_activo_id')->references('id')->on('tipos_activos');
            $table->integer('tipo_ingreso_id');
			$table->foreign('tipo_ingreso_id')->references('id')->on('tipo_ingresos');
            $table->integer('area_id');
			$table->foreign('area_id')->references('id')->on('areas');
            $table->bigInteger('archivo_img_id')->unsigned()->nullable();
            $table->foreign('archivo_img_id')->references('id')->on('archivos');
            $table->bigInteger('estado_id')->unsigned()->nullable();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('historico_activos');
    }
};
