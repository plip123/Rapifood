<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtrasProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extras_product_orders', function (Blueprint $table) {
            $table->unsignedBigInteger("id");
            $table->unsignedBigInteger("extraID");
            $table->unsignedBigInteger("productOrderID");
            $table->integer("quantly");
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
        Schema::dropIfExists('extras_product_orders');
    }
}
