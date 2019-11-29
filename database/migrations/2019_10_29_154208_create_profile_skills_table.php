<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfileSkillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile_skills', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->integer('profile_id')->nullable()->index('fk_profile_skills_profiles1_idx');
			$table->integer('skill_id')->nullable()->index('fk_profile_skills_skills1_idx');
			$table->integer('level')->nullable();
			$table->string('description')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profile_skills');
	}

}
