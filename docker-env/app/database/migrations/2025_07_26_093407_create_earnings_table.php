<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarningsTable extends Migration
{
    public function up()
    {
        Schema::create('earnings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('amount');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('purchase_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('earnings');
    }
}
