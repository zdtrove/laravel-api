<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profiles', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('full_name', 45);
			$table->string('email', 45)->nullable();
			$table->string('password', 45)->nullable();
			$table->string('phone', 20)->nullable();
			$table->integer('sex')->nullable();
			$table->dateTime('birthday')->nullable();
			$table->string('address', 128)->nullable();
			$table->string('avata', 45)->nullable();
			$table->string('cover', 45)->nullable();
			$table->string('facebook', 45)->nullable();
			$table->string('google', 45)->nullable();
			$table->string('twitter', 45)->nullable();
			$table->string('link', 45)->nullable();
			$table->string('note', 45)->nullable();
			$table->string('introduction')->nullable();
			$table->string('ambition')->nullable();
			$table->integer('occupation_id')->nullable()->index('fk_profiles_occupations1_idx');
			$table->timestamps();
			$table->string('created_by', 45)->nullable();
			$table->string('updated_by', 45)->nullable();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profiles');
	}

}
