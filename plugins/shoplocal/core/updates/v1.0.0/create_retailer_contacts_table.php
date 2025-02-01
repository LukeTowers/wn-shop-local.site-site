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
        Schema::create('shoplocal_core_retailer_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('retailer_id')->unsigned()->nullable();
            $table->string('type')->default('website');
            $table->string('value');
            $table->boolean('is_public')->default(false);
            $table->mediumText('metadata')->nullable();
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
        Schema::dropIfExists('shoplocal_core_retailer_contacts');
    }
};
