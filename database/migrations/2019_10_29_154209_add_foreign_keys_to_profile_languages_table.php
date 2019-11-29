<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProfileLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('profile_languages', function(Blueprint $table)
		{
			$table->foreign('language_id', 'fk_profile_languages_languages1')->references('id')->on('languages')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('profile_id', 'fk_profile_languages_profiles1')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('profile_languages', function(Blueprint $table)
		{
			$table->dropForeign('fk_profile_languages_languages1');
			$table->dropForeign('fk_profile_languages_profiles1');
		});
	}

}
