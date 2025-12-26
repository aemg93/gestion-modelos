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
        Schema::create('model_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Nombre real o artístico
            $table->string('nickname')->nullable(); // Apodo opcional
            $table->string('email')->unique()->nullable(); // Email único, puede ser nulo
            $table->timestamps();                   // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_profiles');
    }
};
