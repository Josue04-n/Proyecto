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
    Schema::create('operarios', function (Blueprint $table) {
        $table->id(); 
        $table->boolean('is_active')->default(true);
        $table->string('cedula', 20)->unique();
        $table->string('primer_nombre');     
        $table->string('segundo_nombre')->nullable(); 
        $table->string('apellido_paterno');   
        $table->string('apellido_materno')->nullable(); 
        $table->string('telefono')->nullable();
        $table->text('direccion')->nullable();
        $table->boolean('estado')->default(true); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->softDeletes(); 
        $table->timestamps(); 
        
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operarios');
    }
};
