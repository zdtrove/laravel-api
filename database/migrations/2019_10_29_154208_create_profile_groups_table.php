<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfileGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile_groups', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->integer('group_id')->nullable()->index('fk_profile_groups_groups1_idx')->comment('only_me, friend, recruite, public	');
			$table->integer('profile_id')->nullable()->index('fk_profile_groups_profiles1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profile_groups');
	}

}
