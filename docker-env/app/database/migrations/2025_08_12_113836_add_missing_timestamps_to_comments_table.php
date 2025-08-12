<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingTimestampsToCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('content');
            }
            if (!Schema::hasColumn('comments', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('comments', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
}
