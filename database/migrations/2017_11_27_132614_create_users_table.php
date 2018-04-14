<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->index();
            $table->string('username', 255);
            $table->string('email', 255);
            $table->string('first_name', 255)->nullable();
            $table->string('prefix', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('password', 255);
            $table->string('zip_code', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->boolean('confirmed')->nullable()->default(0);
            $table->string('confirmation_code', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('role_id')->unsigned();
        });
        DB::statement("ALTER TABLE `users` ADD `image` LONGBLOB NULL");

        Schema::table('users', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
