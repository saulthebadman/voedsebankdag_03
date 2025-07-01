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
        Schema::create('persoons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gezin_id')->nullable()->constrained('gezins')->onDelete('set null');
            $table->string('voornaam');
            $table->string('tussenvoegsel')->nullable();
            $table->string('achternaam');
            $table->date('geboortedatum');
            $table->enum('type_persoon', ['Manager', 'Medewerker', 'Vrijwilliger', 'Klant']);
            $table->boolean('is_vertegenwoordiger')->default(false);
            
            // Systeem velden
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->datetime('datum_aangemaakt', 6)->default(now());
            $table->datetime('datum_gewijzigd', 6)->default(now());
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persoons');
    }
};
