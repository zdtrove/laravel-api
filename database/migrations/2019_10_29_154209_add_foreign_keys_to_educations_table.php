<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEducationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('educations', function(Blueprint $table)
		{
			$table->foreign('profile_id', 'fk_educations_profiles1')->references('id')->on('profiles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('school_id', 'fk_educations_schools1')->references('id')->on('schools')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('educations', function(Blueprint $table)
		{
			$table->dropForeign('fk_educations_profiles1');
			$table->dropForeign('fk_educations_schools1');
		});
	}

}
