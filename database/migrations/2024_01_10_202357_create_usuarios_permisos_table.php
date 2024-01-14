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
        Schema::create('usuarios_permisos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->required();
            $table->bigInteger('permiso_id')->unsigned()->required();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('permiso_id')->references('id')->on('permisos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_permisos');
    }
};
