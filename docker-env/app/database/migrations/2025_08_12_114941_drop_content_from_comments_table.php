<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DropContentFromCommentsTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('comments','content')) {
            DB::statement('ALTER TABLE comments DROP COLUMN content');
        }
    }
    public function down()
    {
        DB::statement('ALTER TABLE comments ADD COLUMN content TEXT NULL');
    }
}
