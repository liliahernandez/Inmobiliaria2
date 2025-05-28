<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   // database/migrations/xxxx_xx_xx_create_multas_table.php
public function up()
{
    Schema::create('multas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_residente', 100);
        $table->text('motivo');
        $table->decimal('monto', 10, 2);
        $table->date('fecha');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multas');
    }
};
