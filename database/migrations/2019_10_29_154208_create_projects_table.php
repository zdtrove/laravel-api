<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('title', 45)->nullable();
			$table->string('link')->nullable();
			$table->dateTime('term_from')->nullable();
			$table->dateTime('term_to')->nullable();
			$table->string('description')->nullable();
			$table->integer('project_member')->nullable();
			$table->integer('profile_id')->nullable()->index('fk_projects_profiles1_idx');
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
		Schema::drop('projects');
	}

}
