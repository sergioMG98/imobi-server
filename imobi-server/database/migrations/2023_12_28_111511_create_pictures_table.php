<?php

use App\Models\Picture;
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
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->string('picture');
            $table->timestamps();
        });
        Schema::create('picture_product', function(Blueprint $table){
            $table->foreignIdFor(Picture::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->primary(['picture_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pictures');
    }
};
