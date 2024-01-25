<?php
use App\Models\User;
use App\Models\Client;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->integer('prix');
            $table->string('description');
            $table->integer('surface');   
            $table->string('ges');
            $table->string('dpe');
            $table->string('type');
            $table->integer('piece')->nullable();
            $table->integer('surfaceTerrain')->nullable();
            $table->integer('salleDeBain')->nullable();
            $table->integer('chambre')->nullable();
            $table->integer('terrasse')->nullable();
            $table->integer('balcon')->nullable();
            $table->integer('garage')->nullable();
            $table->integer('piscine')->nullable();
            $table->integer('ascenseur')->nullable();
            $table->integer('cave')->nullable();
            $table->integer('longitude')->nullable();
            $table->integer('latitude')->nullable();
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();
        });

        Schema::create('client_product', function(Blueprint $table){
            $table->foreignIdFor(Client::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->primary(['client_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_produc');
        Schema::dropIfExists('products');
    }
};
