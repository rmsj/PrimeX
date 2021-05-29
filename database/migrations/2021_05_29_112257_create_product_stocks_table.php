<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Changed the name of the table as I "child" tables should have the name of the "main table" as a prefix
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->integer('on_hand');
            $table->integer('taken');
            $table->timestamp('production_date');
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
        Schema::dropIfExists('product_stocks');
    }
}
