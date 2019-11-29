<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProfileGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('profile_groups', function(Blueprint $table)
		{
			$table->foreign('group_id', 'fk_profile_groups_groups1')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('profile_id', 'fk_profile_groups_profiles1')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('profile_groups', function(Blueprint $table)
		{
			$table->dropForeign('fk_profile_groups_groups1');
			$table->dropForeign('fk_profile_groups_profiles1');
		});
	}

}
