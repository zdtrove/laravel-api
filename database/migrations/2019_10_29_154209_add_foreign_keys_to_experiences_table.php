<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToExperiencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('experiences', function(Blueprint $table)
		{
			$table->foreign('company_id', 'fk_experiences_companies1')->references('id')->on('companies')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('profile_id', 'fk_experiences_profiles1')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('experiences', function(Blueprint $table)
		{
			$table->dropForeign('fk_experiences_companies1');
			$table->dropForeign('fk_experiences_profiles1');
		});
	}

}
