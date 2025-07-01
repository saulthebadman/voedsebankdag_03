<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leveranciers', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('contactpersoon')->nullable();
            $table->string('email')->nullable();
            $table->string('mobiel')->nullable();
            $table->string('leveranciernummer')->unique();
            $table->string('leverancier_type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leveranciers');
    }
};
