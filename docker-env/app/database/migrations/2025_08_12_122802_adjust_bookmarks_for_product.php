<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdjustBookmarksForProduct extends Migration
{
    public function up()
    {
        try { DB::statement('ALTER TABLE bookmarks DROP FOREIGN KEY bookmarks_post_id_foreign'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE bookmarks DROP FOREIGN KEY bookmarks_product_id_foreign'); } catch (\Throwable $e) {}

        if (!Schema::hasColumn('bookmarks', 'id')) {
            try { DB::statement('ALTER TABLE bookmarks DROP PRIMARY KEY'); } catch (\Throwable $e) {}
            DB::statement('ALTER TABLE bookmarks ADD COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        }

        DB::statement('ALTER TABLE bookmarks MODIFY post_id INT UNSIGNED NULL');

        if (!Schema::hasColumn('bookmarks', 'product_id')) {
            Schema::table('bookmarks', function (Blueprint $table) {
                $table->unsignedInteger('product_id')->nullable()->after('post_id');
            });

        } else {

            DB::statement('ALTER TABLE bookmarks MODIFY product_id INT UNSIGNED NULL');
        }

        try { DB::statement('CREATE UNIQUE INDEX bookmarks_user_post_unique ON bookmarks (user_id, post_id)'); } catch (\Throwable $e) {}
        try { DB::statement('CREATE UNIQUE INDEX bookmarks_user_product_unique ON bookmarks (user_id, product_id)'); } catch (\Throwable $e) {}

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            if (!Schema::hasColumn('bookmarks', 'created_at')) $table->timestamp('created_at')->nullable();
            if (!Schema::hasColumn('bookmarks', 'updated_at')) $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        try { DB::statement('ALTER TABLE bookmarks DROP FOREIGN KEY bookmarks_post_id_foreign'); } catch (\Throwable $e) {}
        try { DB::statement('ALTER TABLE bookmarks DROP FOREIGN KEY bookmarks_product_id_foreign'); } catch (\Throwable $e) {}

        try { DB::statement('DROP INDEX bookmarks_user_post_unique ON bookmarks'); } catch (\Throwable $e) {}
        try { DB::statement('DROP INDEX bookmarks_user_product_unique ON bookmarks'); } catch (\Throwable $e) {}

    }
}
