<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVisibilitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('visibilities', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->integer('profile_id')->nullable()->index('fk_visibilities_profiles1_idx');
			$table->integer('group_id')->nullable();
			$table->integer('object_id')->nullable();
			$table->integer('object_type')->nullable();
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
		Schema::drop('visibilities');
	}

}
