<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // nombre visible
            $table->string('slug')->unique(); // identificador tipo estudioabc
            $table->string('base_datos')->unique(); // ej: tsn_estudioabc
            $table->foreignId('plan_id')->constrained('planes');

            $table->string('contacto_nombre');
            $table->string('contacto_email');
            $table->string('contacto_tel')->nullable();

            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_alta')->useCurrent();
            $table->timestamp('fecha_vencimiento')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudios');
    }
};
