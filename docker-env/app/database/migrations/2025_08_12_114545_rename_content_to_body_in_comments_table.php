<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameContentToBodyInCommentsTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('comments', 'content') && !Schema::hasColumn('comments', 'body')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('content', 'body');
            });
        }
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments','created_at')) $table->timestamp('created_at')->nullable();
            if (!Schema::hasColumn('comments','updated_at')) $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasColumn('comments', 'body') && !Schema::hasColumn('comments', 'content')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->renameColumn('body', 'content');
            });
        }
    }
}
