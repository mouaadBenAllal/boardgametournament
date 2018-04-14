<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->index();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->integer('session_size');
            $table->string('token');
            $table->boolean('private');
            $table->boolean('completed')->default(0);
            $table->boolean('started')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->integer('boardgame_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->foreign('boardgame_id')
                ->references('id')->on('boardgames')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('tournaments');
    }
}
