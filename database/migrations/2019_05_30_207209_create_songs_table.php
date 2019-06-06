<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSongsTable.
 */
class CreateSongsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('songs', function(Blueprint $table) {
			$table->increments('id');
			
            $table->string('name');
            $table->string('description');
            $table->string('image_url');
            $table->string('song_path');
            $table->unsignedInteger('genre_id');
            $table->unsignedInteger('album_id')->nullable();
            // $table->unsignedInteger('artist_id');
            $table->timestamps();
            // $table->foreign('album_id')
            //     ->references('id')
            //     ->on('albums');
            // $table->foreign('artist_id')
            //     ->references('id')
            //     ->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('songs');
	}
}
