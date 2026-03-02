<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vignettes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');

            $table->string('type', 10);          // pl. D1
            $table->string('category', 50);      // pl. Éves M1 regionális
            $table->string('region', 50)->nullable(); // megyei matricánál
            $table->integer('year');             // pl. 2026

            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vignettes');
    }
};
