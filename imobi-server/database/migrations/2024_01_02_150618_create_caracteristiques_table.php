<?php

use App\Models\Product;
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
        Schema::create('caracteristiques', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->integer('piece');
            $table->integer('surfaceTerrain')->nullable();
            $table->integer('surface');
            $table->integer('salleDeBain');
            $table->integer('chambre');
            $table->integer('terrasse')->nullable();
            $table->integer('cave')->nullable();
            $table->string('bilanEnergetique');
            $table->foreignIdFor(Product::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caracteristiques');
    }
};
