<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEducationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('educations', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('degree_major', 45)->nullable();
			$table->date('graduation')->nullable();
			$table->string('description')->nullable();
			$table->integer('profile_id')->nullable()->index('fk_educations_profiles1_idx');
			$table->integer('school_id')->nullable()->index('fk_educations_schools1_idx');
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
		Schema::drop('educations');
	}

}
