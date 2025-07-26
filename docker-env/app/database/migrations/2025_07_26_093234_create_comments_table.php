<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedBigInteger('user_id');  // 修正
        $table->unsignedInteger('post_id');     // 問題なし
        $table->text('body');
        $table->timestamp('created_at')->useCurrent();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
    });

    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
