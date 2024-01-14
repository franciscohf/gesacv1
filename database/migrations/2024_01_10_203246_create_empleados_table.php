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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('primer_nombre',100);
            $table->string('segundo_nombre',100);
            $table->string('apellido_paterno',150);
            $table->string('apellido_materno',150);
            $table->date('fecha_nacimiento');
            $table->string('tarjeta_identificacion');
            $table->string('ci',50);
            $table->string('direccion');
            $table->string('telefono');
            $table->string('estado',2)->default('A');
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('tipo_empleado_id');
            $table->foreign('tipo_empleado_id')->references('id')->on('tipos_empleados');
            $table->bigInteger('unidad_id');
            $table->foreign('unidad_id')->references('id')->on('unidades');
            $table->bigInteger('institucion_id');
            $table->foreign('institucion_id')->references('id')->on('instituciones');
            $table->bigInteger('archivo_img_id')->unsigned()->nullable();
            $table->foreign('archivo_img_id')->references('id')->on('archivos');
            $table->bigInteger('municipio_id')->unsigned()->nullable();
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
