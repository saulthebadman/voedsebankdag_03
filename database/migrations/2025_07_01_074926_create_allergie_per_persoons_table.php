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
        Schema::create('allergie_per_persoons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persoon_id')->constrained('persoons')->onDelete('cascade');
            $table->foreignId('allergie_id')->constrained('allergies')->onDelete('cascade');
            
            
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->datetime('datum_aangemaakt', 6)->default(now());
            $table->datetime('datum_gewijzigd', 6)->default(now());
            
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['persoon_id', 'allergie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergie_per_persoons');
    }
};
