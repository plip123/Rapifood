<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->unsignedBigInteger("id");
            $table->string("name");
            $table->double("price");
            $table->double("offert_price");
            $table->string("description");
            $table->string("state");
            $table->boolean("offert");
            $table->string("image");
            $table->integer("priority");
            $table->integer("storeID");
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
        Schema::dropIfExists('product');
    }
}
