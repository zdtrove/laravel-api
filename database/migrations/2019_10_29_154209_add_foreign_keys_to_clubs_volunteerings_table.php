<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToClubsVolunteeringsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('clubs_volunteerings', function(Blueprint $table)
		{
			$table->foreign('profile_id', 'fk_clubs_volunteerings_profiles1')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('clubs_volunteerings', function(Blueprint $table)
		{
			$table->dropForeign('fk_clubs_volunteerings_profiles1');
		});
	}

}
