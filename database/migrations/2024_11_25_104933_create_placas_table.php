<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacasTable extends Migration
{
    public function up()
    {
        Schema::create('placas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tramite_id');
            $table->string('placa');
            $table->string('motor');
            $table->string('chasis');
            $table->string('poliza');
            $table->enum('pago', ['Pendiente', 'Pagado'])->default('Pendiente');
            $table->timestamps();

            // Definir la relación con la tabla de trámites
            $table->foreign('tramite_id')->references('id')->on('tramites')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('placas');
    }
}
