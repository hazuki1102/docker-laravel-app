<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToBookmarksTable extends Migration
{
    public function up()
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            if (!Schema::hasColumn('bookmarks', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('post_id');
                $table->unique(['user_id', 'product_id']);
            }
            if (!Schema::hasColumn('bookmarks', 'created_at')) $table->timestamp('created_at')->nullable();
            if (!Schema::hasColumn('bookmarks', 'updated_at')) $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            if (Schema::hasColumn('bookmarks', 'product_id')) {
                $table->dropUnique('bookmarks_user_id_product_id_unique');
                $table->dropColumn('product_id');
            }
        });
    }
}
