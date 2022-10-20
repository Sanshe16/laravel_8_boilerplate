<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->integer('price'); //we store price as cents to avoid any floating point precision issue
            $table->text('details')->nullable();
            $table->boolean('is_promotion')->default(0);
            $table->integer('promotion_price')->nullable();
            $table->integer('shipping_type')->nullable();
            $table->unsignedInteger('min_shipping_days')->nullable();
            $table->unsignedInteger('max_shipping_days')->nullable();
            $table->integer('shipping_cost')->nullable();
            $table->boolean('is_active')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
