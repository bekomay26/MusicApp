<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateRatingsTable.
 */
class CreateRatingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ratings', function(Blueprint $table) {
            $table->increments('id');

            $table->string('score');
            $table->string('comment')->nullable();
            $table->unsignedInteger('song_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->foreign('song_id')
                ->references('id')
                ->on('songs');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ratings');
	}
}
