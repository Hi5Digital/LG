<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteTables extends Migration
{
    /**
     * Tables the admin reads with raw queries. Columns match the fields
     * HomeController and the Blade screens select, update, or display.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->dateTime('createdate')->nullable();
            $table->dateTime('modifydate')->nullable();
        });

        Schema::create('mdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mid')->index();
            $table->text('profile')->nullable();
            $table->string('screenname')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->string('bust')->nullable();
            $table->unsignedBigInteger('btype')->nullable();
            $table->unsignedBigInteger('orientation')->nullable();
            $table->unsignedBigInteger('ethnic')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->char('smoke', 1)->nullable();
            $table->char('drink', 1)->nullable();
            $table->char('travel', 1)->nullable();
            $table->unsignedTinyInteger('verified')->nullable();
            $table->unsignedTinyInteger('top')->nullable();
            $table->unsignedTinyInteger('imageverified')->nullable();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mid')->index();
            $table->string('transactionid')->nullable();
            $table->string('subscriptionid')->nullable();
            $table->dateTime('paymentdate')->nullable();
            $table->dateTime('validdate')->nullable();
        });

        Schema::create('mphoto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mid')->index();
            $table->string('photo')->nullable();
            $table->string('path')->nullable();
            $table->char('main', 1)->nullable();
        });

        Schema::create('lcountry', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('lprovince', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cid')->index();
            $table->string('name');
        });

        Schema::create('ltown', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cid')->index();
            $table->unsignedBigInteger('pid')->index();
            $table->string('name');
        });

        Schema::create('mlocation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mid')->index();
            $table->unsignedBigInteger('lid')->nullable();
            $table->unsignedBigInteger('pid')->nullable();
            $table->unsignedBigInteger('cid')->nullable();
        });

        Schema::create('mbodytype', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('mbust', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('methnic', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('morientation', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('login', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uid')->index();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->dateTime('datecreated')->nullable();
            $table->dateTime('datemodified')->nullable();
            $table->unsignedTinyInteger('gdpr')->nullable();
            $table->unsignedTinyInteger('tandc')->nullable();
        });

        Schema::create('passwordreset', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('token')->nullable();
            $table->dateTime('expiry')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passwordreset');
        Schema::dropIfExists('login');
        Schema::dropIfExists('morientation');
        Schema::dropIfExists('methnic');
        Schema::dropIfExists('mbust');
        Schema::dropIfExists('mbodytype');
        Schema::dropIfExists('mlocation');
        Schema::dropIfExists('ltown');
        Schema::dropIfExists('lprovince');
        Schema::dropIfExists('lcountry');
        Schema::dropIfExists('mphoto');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('mdetails');
        Schema::dropIfExists('model');
    }
}
