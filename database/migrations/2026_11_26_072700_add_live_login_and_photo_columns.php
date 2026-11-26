<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLiveLoginAndPhotoColumns extends Migration
{
    /**
     * Columns the live frontend and the live admin already write.
     *
     * @return void
     */
    public function up()
    {
        $loginColumns = [];
        if (! Schema::hasColumn('login', 'password')) {
            $loginColumns[] = 'password';
        }
        if (! Schema::hasColumn('login', 'datecreated')) {
            $loginColumns[] = 'datecreated';
        }
        if (! Schema::hasColumn('login', 'datemodified')) {
            $loginColumns[] = 'datemodified';
        }
        if (! Schema::hasColumn('login', 'gdpr')) {
            $loginColumns[] = 'gdpr';
        }
        if (! Schema::hasColumn('login', 'tandc')) {
            $loginColumns[] = 'tandc';
        }
        if ($loginColumns) {
            Schema::table('login', function (Blueprint $table) use ($loginColumns) {
                if (in_array('password', $loginColumns, true)) {
                    $table->string('password')->nullable();
                }
                if (in_array('datecreated', $loginColumns, true)) {
                    $table->dateTime('datecreated')->nullable();
                }
                if (in_array('datemodified', $loginColumns, true)) {
                    $table->dateTime('datemodified')->nullable();
                }
                if (in_array('gdpr', $loginColumns, true)) {
                    $table->unsignedTinyInteger('gdpr')->nullable();
                }
                if (in_array('tandc', $loginColumns, true)) {
                    $table->unsignedTinyInteger('tandc')->nullable();
                }
            });
        }

        if (! Schema::hasColumn('mphoto', 'path')) {
            Schema::table('mphoto', function (Blueprint $table) {
                $table->string('path')->nullable();
            });
        }

        if (! Schema::hasTable('passwordreset')) {
            Schema::create('passwordreset', function (Blueprint $table) {
                $table->id();
                $table->string('email')->index();
                $table->string('token')->nullable();
                $table->dateTime('expiry')->nullable();
            });
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passwordreset');

        if (Schema::hasColumn('mphoto', 'path')) {
            Schema::table('mphoto', function (Blueprint $table) {
                $table->dropColumn('path');
            });
        }

        $drop = [];
        foreach (['password', 'datecreated', 'datemodified', 'gdpr', 'tandc'] as $column) {
            if (Schema::hasColumn('login', $column)) {
                $drop[] = $column;
            }
        }
        if ($drop) {
            Schema::table('login', function (Blueprint $table) use ($drop) {
                $table->dropColumn($drop);
            });
        }
    }
}
