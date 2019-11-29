<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfileLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile_languages', function(Blueprint $table)
		{
			$table->integer('profile_id');
			$table->integer('language_id')->index('fk_profile_languages_languages1_idx');
			$table->primary(['profile_id','language_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profile_languages');
	}

}
