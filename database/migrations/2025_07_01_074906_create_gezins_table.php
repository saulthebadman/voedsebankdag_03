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
        Schema::create('gezins', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('code')->unique();
            $table->text('omschrijving')->nullable();
            $table->integer('aantal_volwassenen')->default(0);
            $table->integer('aantal_kinderen')->default(0);
            $table->integer('aantal_babys')->default(0);
            $table->integer('totaal_aantal_personen')->default(0);
            
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
        Schema::dropIfExists('gezins');
    }
};
