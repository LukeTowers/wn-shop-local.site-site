<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoplocal_core_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('retailer_id')->nullable()->index(); // Foreign key to Retailer
            $table->string('name'); // Product name
            $table->string('slug')->unique();
            $table->text('description')->nullable(); // Optional product description
            $table->decimal('price', 10, 2)->nullable(); // Price (nullable)
            $table->string('currency', 3)->default('CAD'); // Default currency: CAD
            $table->boolean('is_available')->default(true); // Availability flag
            $table->mediumText('metadata')->nullable(); // Store extra details as JSON
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shoplocal_core_products');
    }
};
