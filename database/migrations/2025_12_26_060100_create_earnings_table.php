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
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            // 👇 corregido: referencia a model_profiles
            $table->foreignId('model_profile_id')->constrained('model_profiles')->onDelete('cascade'); 
            $table->decimal('amount', 10, 2); // monto del ingreso
            $table->date('date');             // fecha del ingreso
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earnings');
    }
};
