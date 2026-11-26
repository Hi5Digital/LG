<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListedToMdetails extends Migration
{
    public function up()
    {
        if (Schema::hasTable('mdetails') && ! Schema::hasColumn('mdetails', 'listed')) {
            Schema::table('mdetails', function (Blueprint $table) {
                $table->unsignedTinyInteger('listed')->default(1);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('mdetails') && Schema::hasColumn('mdetails', 'listed')) {
            Schema::table('mdetails', function (Blueprint $table) {
                $table->dropColumn('listed');
            });
        }
    }
}
