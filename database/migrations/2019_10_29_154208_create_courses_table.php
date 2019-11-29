<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoursesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('courses', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('title', 45)->nullable();
			$table->string('link', 100)->nullable();
			$table->date('term_from')->nullable();
			$table->date('term_to')->nullable();
			$table->string('description');
			$table->integer('profile_id')->nullable()->index('fk_courses_profiles1_idx');
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
		Schema::drop('courses');
	}

}
