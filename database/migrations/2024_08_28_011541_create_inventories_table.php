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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->integer('house_id');
            $table->string('house_description', 30);
            $table->decimal('quantity', 10, 2);
            $table->integer('uom_id');
            $table->string('uom_abbreviation', 12);
            $table->date('purchase_date');
            $table->date('expiration_date');
            $table->integer('catalog_id');
            $table->string('catalog_description', 150);
            $table->integer('brand_id');
            $table->string('brand_name', 30);
            $table->integer('category_id');
            $table->string('category_name', 30);
            $table->foreignId('product_status_id')->constrained('product_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
