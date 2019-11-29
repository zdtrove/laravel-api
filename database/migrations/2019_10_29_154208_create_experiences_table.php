<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExperiencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('experiences', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->integer('company_id')->nullable()->index('fk_experiences_companies1_idx');
			$table->string('title', 100)->nullable();
			$table->dateTime('from')->nullable();
			$table->dateTime('to')->nullable();
			$table->integer('is_working')->nullable();
			$table->integer('intership')->nullable();
			$table->integer('profile_id')->nullable()->index('fk_experiences_profiles1_idx');
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
		Schema::drop('experiences');
	}

}
