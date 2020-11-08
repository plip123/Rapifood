<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_extras', function (Blueprint $table) {
            $table->unsignedBigInteger("id");
            $table->unsignedBigInteger("productID");
            $table->unsignedBigInteger("productExtra");
            $table->double("price");
            $table->string("default");
            $table->integer("min");
            $table->integer("max");
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
        Schema::dropIfExists('product_extras');
    }
}
