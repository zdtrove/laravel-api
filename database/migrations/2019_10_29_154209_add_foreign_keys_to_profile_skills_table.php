<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProfileSkillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('profile_skills', function(Blueprint $table)
		{
			$table->foreign('profile_id', 'fk_profile_skills_profiles1')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('skill_id', 'fk_profile_skills_skills1')->references('id')->on('skills')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('profile_skills', function(Blueprint $table)
		{
			$table->dropForeign('fk_profile_skills_profiles1');
			$table->dropForeign('fk_profile_skills_skills1');
		});
	}

}
