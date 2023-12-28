<?php
use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->integer('piece');
            $table->integer('surfaceTerrain')->nullable();
            $table->integer('surface');
            $table->integer('salleDeBain');
            $table->integer('chambre');
            $table->integer('terrasse')->nullable();
            $table->integer('cave')->nullable();
            $table->string('bilanEnergenique');
            $table->integer('longitude')->nullable();
            $table->integer('latitude')->nullable();
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
