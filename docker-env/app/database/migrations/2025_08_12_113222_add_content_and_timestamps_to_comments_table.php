<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentAndTimestampsToCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'content')) {
                $table->text('content')->after('user_id');
            }
            if (!Schema::hasColumn('comments', 'created_at') && !Schema::hasColumn('comments', 'updated_at')) {
                $table->timestamps(); // created_at / updated_at
            }
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'content')) {
                $table->dropColumn('content');
            }
            if (Schema::hasColumn('comments', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('comments', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
}
